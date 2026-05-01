<?php

namespace App\Packages\DataTransfer\Helpers\Exporters\Salary;

use App\Packages\DataTransfer\Helpers\Exporters\AbstractExporter;
use App\Packages\StaffManagement\Models\SalaryDetail;

class Exporter extends AbstractExporter
{
    public function getHeaders(): array
    {
        return ['staff_email', 'basic_salary', 'allowances', 'deductions', 'net_salary', 'payment_date'];
    }

    public function getRows(): iterable
    {
        $query = SalaryDetail::with('staff');

        if (! empty($this->filters['from_date'])) {
            $query->whereDate('payment_date', '>=', $this->filters['from_date']);
        }

        if (! empty($this->filters['to_date'])) {
            $query->whereDate('payment_date', '<=', $this->filters['to_date']);
        }

        foreach ($query->cursor() as $salary) {
            yield [
                $salary->staff?->email,
                $salary->basic_salary,
                $salary->allowances,
                $salary->deductions,
                $salary->net_salary,
                $salary->payment_date?->format('Y-m-d') ?? $salary->payment_date,
            ];
        }
    }
}
