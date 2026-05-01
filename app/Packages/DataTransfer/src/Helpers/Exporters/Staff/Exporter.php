<?php

namespace App\Packages\DataTransfer\Helpers\Exporters\Staff;

use App\Packages\DataTransfer\Helpers\Exporters\AbstractExporter;
use App\Packages\StaffManagement\Models\Staff;

class Exporter extends AbstractExporter
{
    public function getHeaders(): array
    {
        return [
            'first_name', 'last_name', 'date_of_birth', 'gender',
            'phone_number', 'email', 'address', 'nationality',
            'joining_date', 'designation', 'employment_type', 'status',
        ];
    }

    public function getRows(): iterable
    {
        $query = Staff::query();

        if (! empty($this->filters['status'])) {
            $query->where('status', $this->filters['status']);
        }

        if (! empty($this->filters['employment_type'])) {
            $query->where('employment_type', $this->filters['employment_type']);
        }

        foreach ($query->cursor() as $staff) {
            yield [
                $staff->first_name,
                $staff->last_name,
                $staff->date_of_birth?->format('Y-m-d'),
                $staff->gender,
                $staff->phone_number,
                $staff->email,
                $staff->address,
                $staff->nationality,
                $staff->joining_date?->format('Y-m-d'),
                $staff->designation,
                $staff->employment_type,
                $staff->status,
            ];
        }
    }
}
