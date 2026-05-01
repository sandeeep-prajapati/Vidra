<?php

namespace App\Packages\DataTransfer\Helpers\Exporters\Student;

use App\Packages\DataTransfer\Helpers\Exporters\AbstractExporter;
use App\Packages\StudentManagement\Models\Student;

class Exporter extends AbstractExporter
{
    public function getHeaders(): array
    {
        return [
            'first_name', 'last_name', 'date_of_birth', 'gender',
            'blood_group', 'nationality', 'religion',
            'current_address', 'permanent_address',
            'phone_number', 'email', 'date_of_admission',
            'admission_number', 'status',
        ];
    }

    public function getRows(): iterable
    {
        $query = Student::query();

        if (! empty($this->filters['status'])) {
            $query->where('status', $this->filters['status']);
        }

        foreach ($query->cursor() as $student) {
            yield [
                $student->first_name,
                $student->last_name,
                $student->date_of_birth?->format('Y-m-d'),
                $student->gender,
                $student->blood_group,
                $student->nationality,
                $student->religion,
                $student->current_address,
                $student->permanent_address,
                $student->phone_number,
                $student->email,
                $student->date_of_admission?->format('Y-m-d'),
                $student->admission_number,
                $student->status,
            ];
        }
    }
}
