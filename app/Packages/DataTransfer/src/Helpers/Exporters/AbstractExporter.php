<?php

namespace App\Packages\DataTransfer\Helpers\Exporters;

use App\Packages\DataTransfer\Helpers\Sources\CsvSource;

abstract class AbstractExporter
{
    protected array $filters = [];

    public function setFilters(array $filters): static
    {
        $this->filters = $filters;

        return $this;
    }

    abstract public function getHeaders(): array;

    abstract public function getRows(): iterable;

    public function export(string $filePath): string
    {
        return CsvSource::writeCsv($filePath, $this->getHeaders(), fn () => $this->getRows());
    }

    public static function sampleHeaders(): array
    {
        return [];
    }
}
