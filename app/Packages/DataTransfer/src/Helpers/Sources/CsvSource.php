<?php

namespace App\Packages\DataTransfer\Helpers\Sources;

use Illuminate\Support\Facades\Storage;

class CsvSource
{
    protected mixed $handle;
    protected array $columnNames = [];
    protected array $currentRow  = [];
    protected int $currentRowNumber = 0;
    protected bool $valid = false;
    protected int $totalColumns = 0;

    public function __construct(string $filePath, protected string $delimiter = ',')
    {
        $path = Storage::disk('local')->path($filePath);

        if (! file_exists($path)) {
            throw new \RuntimeException("File not found: {$filePath}");
        }

        $this->handle = fopen($path, 'r');

        $headers = fgetcsv($this->handle, 4096, $this->delimiter);
        $this->columnNames = array_map('trim', $headers ?: []);
        $this->totalColumns = count($this->columnNames);

        $this->next();
    }

    public function getColumnNames(): array
    {
        return $this->columnNames;
    }

    public function current(): array
    {
        return $this->currentRow;
    }

    public function getCurrentRowNumber(): int
    {
        return $this->currentRowNumber;
    }

    public function valid(): bool
    {
        return $this->valid;
    }

    public function next(): void
    {
        $row = fgetcsv($this->handle, 4096, $this->delimiter);

        if ($row === false || $row === null) {
            $this->valid = false;
            $this->currentRow = [];

            return;
        }

        // Pad or trim to match header count
        if (count($row) < $this->totalColumns) {
            $row = array_pad($row, $this->totalColumns, null);
        } else {
            $row = array_slice($row, 0, $this->totalColumns);
        }

        $this->currentRow = array_combine($this->columnNames, $row);
        $this->currentRowNumber++;
        $this->valid = true;
    }

    public function rewind(): void
    {
        rewind($this->handle);
        fgetcsv($this->handle, 4096, $this->delimiter); // skip header
        $this->currentRowNumber = 0;
        $this->next();
    }

    public function __destruct()
    {
        if (is_resource($this->handle)) {
            fclose($this->handle);
        }
    }

    public static function writeCsv(string $filePath, array $headers, \Closure $rowGenerator): string
    {
        $fullPath = Storage::disk('local')->path($filePath);
        $dir = dirname($fullPath);

        if (! is_dir($dir)) {
            mkdir($dir, 0775, true);
        }

        $handle = fopen($fullPath, 'w');
        fputcsv($handle, $headers);

        foreach ($rowGenerator() as $row) {
            fputcsv($handle, $row);
        }

        fclose($handle);

        return $filePath;
    }
}
