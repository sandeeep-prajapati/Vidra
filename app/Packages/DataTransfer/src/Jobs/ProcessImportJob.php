<?php

namespace App\Packages\DataTransfer\Jobs;

use App\Packages\DataTransfer\Helpers\Sources\CsvSource;
use App\Packages\DataTransfer\Models\TransferJobTrack;
use App\Packages\DataTransfer\Repositories\TransferJobTrackRepository;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessImportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public readonly int $trackId
    ) {}

    public function handle(TransferJobTrackRepository $trackRepo): void
    {
        $track = $trackRepo->find($this->trackId);

        if (! $track) {
            return;
        }

        $trackRepo->update($track->id, [
            'state'      => 'processing',
            'started_at' => now(),
        ]);

        try {
            $job = $track->transferJob;
            $importerConfig = config('data_transfer.importers.' . $job->entity_type);

            if (! $importerConfig) {
                throw new \RuntimeException("No importer registered for entity: {$job->entity_type}");
            }

            $source = new CsvSource($track->file_path, $job->field_separator);

            /** @var \App\Packages\DataTransfer\Helpers\Importers\AbstractImporter $importer */
            $importer = app($importerConfig['importer']);
            $importer->setSource($source);

            $summary = $importer->run($job->action);

            $trackRepo->update($track->id, [
                'state'                => empty($summary['errors']) ? 'completed' : 'completed',
                'processed_rows_count' => $summary['processed'],
                'invalid_rows_count'   => $summary['skipped'],
                'errors_count'         => count($summary['errors']),
                'errors'               => $summary['errors'],
                'summary'              => $summary,
                'completed_at'         => now(),
            ]);
        } catch (\Throwable $e) {
            $trackRepo->update($track->id, [
                'state'       => 'failed',
                'errors'      => [$e->getMessage()],
                'errors_count'=> 1,
                'completed_at'=> now(),
            ]);
        }
    }
}
