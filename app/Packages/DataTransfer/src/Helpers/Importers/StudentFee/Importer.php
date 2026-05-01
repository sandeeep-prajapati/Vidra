<?php

namespace App\Packages\DataTransfer\Helpers\Importers\StudentFee;

use App\Packages\DataTransfer\Helpers\Importers\AbstractImporter;
use App\Packages\FeeManagement\Models\StudentFee;
use App\Packages\StudentManagement\Models\Student;
use Illuminate\Support\Facades\Validator;

class Importer extends AbstractImporter
{
    protected array $permanentAttributes = ['admission_number', 'amount', 'due_date'];

    public function getValidColumnNames(): array
    {
        return [
            'admission_number', 'fee_category', 'amount',
            'due_date', 'paid_amount', 'status', 'remarks',
        ];
    }

    public function validateRow(array $row, int $rowNumber): bool
    {
        $validator = Validator::make($row, [
            'admission_number' => 'required|string|exists:students,admission_number',
            'amount'           => 'required|numeric|min:0',
            'due_date'         => 'required|date',
            'paid_amount'      => 'nullable|numeric|min:0',
            'status'           => 'nullable|in:pending,paid,partial,overdue,waived',
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
        $student = Student::where('admission_number', $row['admission_number'])->first();

        if (! $student) {
            return;
        }

        if ($action === 'delete') {
            StudentFee::where('student_id', $student->student_id)
                ->whereDate('due_date', $row['due_date'])
                ->delete();
            $this->deletedItemsCount++;

            return;
        }

        $existing = StudentFee::where('student_id', $student->student_id)
            ->whereDate('due_date', $row['due_date'])
            ->first();

        $data = [
            'student_id'   => $student->student_id,
            'amount'       => (float) $row['amount'],
            'due_date'     => $row['due_date'],
            'paid_amount'  => (float) ($row['paid_amount'] ?? 0),
            'status'       => $row['status'] ?? 'pending',
            'remarks'      => $row['remarks'] ?? null,
        ];

        if ($existing) {
            $existing->update($data);
            $this->updatedItemsCount++;
        } else {
            StudentFee::create($data);
            $this->createdItemsCount++;
        }
    }

    public static function sampleHeaders(): array
    {
        return ['admission_number', 'fee_category', 'amount', 'due_date', 'paid_amount', 'status', 'remarks'];
    }
}
