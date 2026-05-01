<?php

namespace App\Packages\DataTransfer\Jobs;

use App\Packages\DataTransfer\Helpers\Sources\CsvSource;
use App\Packages\DataTransfer\Repositories\TransferJobTrackRepository;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessExportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public readonly int    $trackId,
        public readonly string $outputPath
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
            $exporterConfig = config('data_transfer.exporters.' . $job->entity_type);

            if (! $exporterConfig) {
                throw new \RuntimeException("No exporter registered for entity: {$job->entity_type}");
            }

            /** @var \App\Packages\DataTransfer\Helpers\Exporters\AbstractExporter $exporter */
            $exporter = app($exporterConfig['exporter']);
            $exporter->setFilters($job->filters ?? []);

            $outputFile = $exporter->export($this->outputPath);

            $trackRepo->update($track->id, [
                'state'            => 'completed',
                'output_file_path' => $outputFile,
                'completed_at'     => now(),
                'summary'          => ['exported' => true],
            ]);
        } catch (\Throwable $e) {
            $trackRepo->update($track->id, [
                'state'        => 'failed',
                'errors'       => [$e->getMessage()],
                'errors_count' => 1,
                'completed_at' => now(),
            ]);
        }
    }
}
