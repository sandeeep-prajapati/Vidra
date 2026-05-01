<?php

namespace App\Packages\DataTransfer\Helpers\Importers\Student;

use App\Packages\DataTransfer\Helpers\Importers\AbstractImporter;
use App\Packages\StudentManagement\Models\Student;
use Illuminate\Support\Facades\Validator;

class Importer extends AbstractImporter
{
    protected array $permanentAttributes = ['first_name', 'last_name', 'admission_number'];

    public function getValidColumnNames(): array
    {
        return [
            'first_name', 'last_name', 'date_of_birth', 'gender',
            'blood_group', 'nationality', 'religion',
            'current_address', 'permanent_address',
            'phone_number', 'email', 'date_of_admission',
            'admission_number', 'status',
        ];
    }

    public function validateRow(array $row, int $rowNumber): bool
    {
        $validator = Validator::make($row, [
            'first_name'       => 'required|string|max:100',
            'last_name'        => 'required|string|max:100',
            'admission_number' => 'required|string|max:50',
            'email'            => 'nullable|email',
            'gender'           => 'nullable|in:male,female,other',
            'date_of_birth'    => 'nullable|date',
            'date_of_admission'=> 'nullable|date',
            'status'           => 'nullable|in:active,inactive,graduated,transferred',
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $msg) {
                $this->addError($rowNumber, $msg);
            }

            return false;
        }

        return true;
    }

    public function processRow(array $row, string $action): void
    {
        $existing = Student::where('admission_number', $row['admission_number'])->first();

        $data = array_filter([
            'first_name'        => $row['first_name'] ?? null,
            'last_name'         => $row['last_name'] ?? null,
            'date_of_birth'     => $row['date_of_birth'] ?? null,
            'gender'            => $row['gender'] ?? null,
            'blood_group'       => $row['blood_group'] ?? null,
            'nationality'       => $row['nationality'] ?? null,
            'religion'          => $row['religion'] ?? null,
            'current_address'   => $row['current_address'] ?? null,
            'permanent_address' => $row['permanent_address'] ?? null,
            'phone_number'      => $row['phone_number'] ?? null,
            'email'             => $row['email'] ?? null,
            'date_of_admission' => $row['date_of_admission'] ?? null,
            'admission_number'  => $row['admission_number'],
            'status'            => $row['status'] ?? 'active',
        ], fn ($v) => $v !== null && $v !== '');

        if ($action === 'delete') {
            if ($existing) {
                $existing->delete();
                $this->deletedItemsCount++;
            }

            return;
        }

        if ($existing) {
            $existing->update($data);
            $this->updatedItemsCount++;
        } else {
            Student::create($data);
            $this->createdItemsCount++;
        }
    }

    public static function sampleHeaders(): array
    {
        return [
            'first_name', 'last_name', 'date_of_birth', 'gender',
            'blood_group', 'nationality', 'religion',
            'current_address', 'permanent_address',
            'phone_number', 'email', 'date_of_admission',
            'admission_number', 'status',
        ];
    }
}
