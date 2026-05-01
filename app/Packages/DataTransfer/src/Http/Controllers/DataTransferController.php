<?php

namespace App\Packages\DataTransfer\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Packages\DataTransfer\Helpers\Sources\CsvSource;
use App\Packages\DataTransfer\Jobs\ProcessExportJob;
use App\Packages\DataTransfer\Jobs\ProcessImportJob;
use App\Packages\DataTransfer\Repositories\TransferJobRepository;
use App\Packages\DataTransfer\Repositories\TransferJobTrackRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DataTransferController extends Controller
{
    public function __construct(
        protected TransferJobRepository      $jobRepo,
        protected TransferJobTrackRepository $trackRepo
    ) {}

    public function index()
    {
        $jobs      = $this->jobRepo->all();
        $importers = config('data_transfer.importers');
        $exporters = config('data_transfer.exporters');

        return view('data-transfer::index', compact('jobs', 'importers', 'exporters'));
    }

    public function storeImport(Request $request)
    {
        $request->validate([
            'entity_type'         => 'required|in:' . implode(',', array_keys(config('data_transfer.importers'))),
            'file'                => 'required|file|mimes:csv,txt|max:10240',
            'action'              => 'required|in:append,delete',
            'validation_strategy' => 'required|in:skip-errors,stop-on-errors',
            'allowed_errors'      => 'required|integer|min:0',
            'field_separator'     => 'required|max:1',
        ]);

        $uploaded = $request->file('file');
        $path     = $uploaded->store('data-transfer/imports', 'local');

        $job = $this->jobRepo->create([
            'code'                => 'import-' . Str::uuid(),
            'entity_type'         => $request->entity_type,
            'type'                => 'import',
            'action'              => $request->action,
            'validation_strategy' => $request->validation_strategy,
            'allowed_errors'      => $request->allowed_errors,
            'field_separator'     => $request->field_separator,
            'file_path'           => $path,
        ]);

        $track = $this->trackRepo->create([
            'transfer_job_id' => $job->id,
            'user_id'         => auth()->id(),
            'type'            => 'import',
            'action'          => $request->action,
            'state'           => 'pending',
            'file_path'       => $path,
        ]);

        ProcessImportJob::dispatch($track->id);

        return redirect()->route('data-transfer.index')
            ->with('success', 'Import job queued successfully.');
    }

    public function storeExport(Request $request)
    {
        $request->validate([
            'entity_type' => 'required|in:' . implode(',', array_keys(config('data_transfer.exporters'))),
        ]);

        $outputPath = sprintf(
            'data-transfer/exports/%s-%s.csv',
            $request->entity_type,
            now()->format('Ymd-His')
        );

        $job = $this->jobRepo->create([
            'code'        => 'export-' . Str::uuid(),
            'entity_type' => $request->entity_type,
            'type'        => 'export',
            'action'      => 'append',
            'filters'     => $request->only(['status', 'from_date', 'to_date', 'employment_type']),
        ]);

        $track = $this->trackRepo->create([
            'transfer_job_id'  => $job->id,
            'user_id'          => auth()->id(),
            'type'             => 'export',
            'action'           => 'append',
            'state'            => 'pending',
            'output_file_path' => $outputPath,
        ]);

        ProcessExportJob::dispatch($track->id, $outputPath);

        return redirect()->route('data-transfer.index')
            ->with('success', 'Export job queued successfully.');
    }

    public function download(int $trackId)
    {
        $track = $this->trackRepo->find($trackId);

        abort_if(! $track || ! $track->output_file_path, 404);

        $fullPath = Storage::disk('local')->path($track->output_file_path);

        abort_if(! file_exists($fullPath), 404, 'File not found.');

        return response()->download($fullPath);
    }

    public function sampleCsv(string $entityType)
    {
        $importers = config('data_transfer.importers');

        abort_if(! isset($importers[$entityType]), 404);

        $class   = $importers[$entityType]['importer'];
        $headers = $class::sampleHeaders();

        $csv  = implode(',', $headers) . "\n";

        return response($csv, 200, [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$entityType}-sample.csv\"",
        ]);
    }

    public function trackStatus(int $trackId)
    {
        $track = $this->trackRepo->find($trackId);

        abort_if(! $track, 404);

        return response()->json([
            'state'      => $track->state,
            'summary'    => $track->summary,
            'errors'     => $track->errors,
            'output_url' => $track->output_file_path ? route('data-transfer.download', $track->id) : null,
        ]);
    }
}
