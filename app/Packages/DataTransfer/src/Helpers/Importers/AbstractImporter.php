<?php

namespace App\Packages\DataTransfer\Helpers\Importers;

use App\Packages\DataTransfer\Helpers\Sources\CsvSource;

abstract class AbstractImporter
{
    const BATCH_SIZE = 200;

    protected array $validColumnNames = [];
    protected array $permanentAttributes = [];
    protected array $errors = [];
    protected int $processedRowsCount = 0;
    protected int $createdItemsCount  = 0;
    protected int $updatedItemsCount  = 0;
    protected int $deletedItemsCount  = 0;
    protected int $skippedItemsCount  = 0;

    protected ?CsvSource $source = null;

    public function setSource(CsvSource $source): static
    {
        $this->source = $source;

        return $this;
    }

    abstract public function getValidColumnNames(): array;

    abstract public function validateRow(array $row, int $rowNumber): bool;

    abstract public function processRow(array $row, string $action): void;

    public function run(string $action = 'append'): array
    {
        $this->validateColumns();

        if (! empty($this->errors)) {
            return $this->buildSummary();
        }

        $this->source->rewind();

        while ($this->source->valid()) {
            $row = $this->source->current();
            $rowNumber = $this->source->getCurrentRowNumber();

            $this->processedRowsCount++;

            if ($this->validateRow($row, $rowNumber)) {
                try {
                    $this->processRow($row, $action);
                } catch (\Exception $e) {
                    $this->addError($rowNumber, $e->getMessage());
                    $this->skippedItemsCount++;
                }
            } else {
                $this->skippedItemsCount++;
            }

            $this->source->next();
        }

        return $this->buildSummary();
    }

    protected function validateColumns(): void
    {
        $csvColumns = $this->source->getColumnNames();
        $required   = $this->permanentAttributes;

        $missing = array_diff($required, $csvColumns);

        foreach ($missing as $col) {
            $this->addError(0, "Required column missing: {$col}");
        }
    }

    protected function addError(int $row, string $message): void
    {
        $this->errors[] = $row > 0 ? "Row {$row}: {$message}" : $message;
    }

    protected function buildSummary(): array
    {
        return [
            'processed' => $this->processedRowsCount,
            'created'   => $this->createdItemsCount,
            'updated'   => $this->updatedItemsCount,
            'deleted'   => $this->deletedItemsCount,
            'skipped'   => $this->skippedItemsCount,
            'errors'    => $this->errors,
        ];
    }

    public static function sampleHeaders(): array
    {
        return [];
    }
}
