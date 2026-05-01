<?php

namespace App\Packages\DataTransfer\Helpers\Importers\Salary;

use App\Packages\DataTransfer\Helpers\Importers\AbstractImporter;
use App\Packages\StaffManagement\Models\SalaryDetail;
use App\Packages\StaffManagement\Models\Staff;
use Illuminate\Support\Facades\Validator;

class Importer extends AbstractImporter
{
    protected array $permanentAttributes = ['staff_email', 'payment_date'];

    public function getValidColumnNames(): array
    {
        return [
            'staff_email', 'basic_salary', 'allowances',
            'deductions', 'net_salary', 'payment_date',
        ];
    }

    public function validateRow(array $row, int $rowNumber): bool
    {
        $validator = Validator::make($row, [
            'staff_email'  => 'required|email|exists:staff,email',
            'basic_salary' => 'required|numeric|min:0',
            'payment_date' => 'required|date',
            'allowances'   => 'nullable|numeric|min:0',
            'deductions'   => 'nullable|numeric|min:0',
            'net_salary'   => 'nullable|numeric|min:0',
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
        $staff = Staff::where('email', $row['staff_email'])->first();

        if (! $staff) {
            return;
        }

        $data = [
            'staff_id'     => $staff->staff_id,
            'basic_salary' => (float) $row['basic_salary'],
            'allowances'   => (float) ($row['allowances'] ?? 0),
            'deductions'   => (float) ($row['deductions'] ?? 0),
            'net_salary'   => (float) ($row['net_salary'] ?? ((float) $row['basic_salary'] + (float) ($row['allowances'] ?? 0) - (float) ($row['deductions'] ?? 0))),
            'payment_date' => $row['payment_date'],
        ];

        if ($action === 'delete') {
            SalaryDetail::where('staff_id', $staff->staff_id)
                ->whereDate('payment_date', $row['payment_date'])
                ->delete();
            $this->deletedItemsCount++;

            return;
        }

        $existing = SalaryDetail::where('staff_id', $staff->staff_id)
            ->whereDate('payment_date', $row['payment_date'])
            ->first();

        if ($existing) {
            $existing->update($data);
            $this->updatedItemsCount++;
        } else {
            SalaryDetail::create($data);
            $this->createdItemsCount++;
        }
    }

    public static function sampleHeaders(): array
    {
        return ['staff_email', 'basic_salary', 'allowances', 'deductions', 'net_salary', 'payment_date'];
    }
}
