<?php

namespace App\Packages\DataTransfer\Helpers\Importers\Staff;

use App\Packages\DataTransfer\Helpers\Importers\AbstractImporter;
use App\Packages\StaffManagement\Models\Staff;
use Illuminate\Support\Facades\Validator;

class Importer extends AbstractImporter
{
    protected array $permanentAttributes = ['first_name', 'last_name', 'email'];

    public function getValidColumnNames(): array
    {
        return [
            'first_name', 'last_name', 'date_of_birth', 'gender',
            'phone_number', 'email', 'address', 'nationality',
            'joining_date', 'designation', 'employment_type', 'status',
        ];
    }

    public function validateRow(array $row, int $rowNumber): bool
    {
        $validator = Validator::make($row, [
            'first_name'      => 'required|string|max:100',
            'last_name'       => 'required|string|max:100',
            'email'           => 'required|email',
            'gender'          => 'nullable|in:male,female,other',
            'date_of_birth'   => 'nullable|date',
            'joining_date'    => 'nullable|date',
            'employment_type' => 'nullable|in:full-time,part-time,contract,temporary',
            'status'          => 'nullable|in:active,inactive,on_leave,terminated',
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
        $existing = Staff::where('email', $row['email'])->first();

        $data = array_filter([
            'first_name'      => $row['first_name'] ?? null,
            'last_name'       => $row['last_name'] ?? null,
            'date_of_birth'   => $row['date_of_birth'] ?? null,
            'gender'          => $row['gender'] ?? null,
            'phone_number'    => $row['phone_number'] ?? null,
            'email'           => $row['email'],
            'address'         => $row['address'] ?? null,
            'nationality'     => $row['nationality'] ?? null,
            'joining_date'    => $row['joining_date'] ?? null,
            'designation'     => $row['designation'] ?? null,
            'employment_type' => $row['employment_type'] ?? 'full-time',
            'status'          => $row['status'] ?? 'active',
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
            Staff::create($data);
            $this->createdItemsCount++;
        }
    }

    public static function sampleHeaders(): array
    {
        return [
            'first_name', 'last_name', 'date_of_birth', 'gender',
            'phone_number', 'email', 'address', 'nationality',
            'joining_date', 'designation', 'employment_type', 'status',
        ];
    }
}
