<?php

namespace App\Packages\DataTransfer\Helpers\Exporters\StudentFee;

use App\Packages\DataTransfer\Helpers\Exporters\AbstractExporter;
use App\Packages\FeeManagement\Models\StudentFee;

class Exporter extends AbstractExporter
{
    public function getHeaders(): array
    {
        return ['admission_number', 'fee_category', 'amount', 'due_date', 'paid_amount', 'status', 'remarks'];
    }

    public function getRows(): iterable
    {
        $query = StudentFee::with('student');

        if (! empty($this->filters['status'])) {
            $query->where('status', $this->filters['status']);
        }

        if (! empty($this->filters['from_date'])) {
            $query->whereDate('due_date', '>=', $this->filters['from_date']);
        }

        if (! empty($this->filters['to_date'])) {
            $query->whereDate('due_date', '<=', $this->filters['to_date']);
        }

        foreach ($query->cursor() as $fee) {
            yield [
                $fee->student?->admission_number,
                null, // fee_category placeholder
                $fee->amount,
                $fee->due_date instanceof \Carbon\Carbon ? $fee->due_date->format('Y-m-d') : $fee->due_date,
                $fee->paid_amount,
                $fee->status,
                $fee->remarks,
            ];
        }
    }
}
