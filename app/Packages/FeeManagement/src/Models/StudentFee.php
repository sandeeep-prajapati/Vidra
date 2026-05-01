<?php

namespace App\Packages\FeeManagement\Models;

use App\Packages\StudentManagement\Models\Student;
use Illuminate\Database\Eloquent\Model;

class StudentFee extends Model
{
    protected $table = 'student_fees';
    protected $primaryKey = 'student_fee_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'student_id',
        'fee_structure_id',
        'amount_due',
        'discount_amount',
        'penalty_amount',
        'total_payable',
        'payment_status',
        'due_date',
    ];

    protected $casts = [
        'amount_due'      => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'penalty_amount'  => 'decimal:2',
        'total_payable'   => 'decimal:2',
        'due_date'        => 'date',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id', 'student_id');
    }

    public function feeStructure()
    {
        return $this->belongsTo(FeeStructure::class, 'fee_structure_id', 'fee_structure_id');
    }

    public function payments()
    {
        return $this->hasMany(FeePayment::class, 'student_fee_id', 'student_fee_id');
    }

    public function syncPaymentStatus(): void
    {
        $paid = $this->payments()->sum('amount_paid');
        if ($paid <= 0) {
            $status = 'Pending';
        } elseif ($paid >= $this->total_payable) {
            $status = 'Paid';
        } else {
            $status = 'Partially Paid';
        }
        $this->update(['payment_status' => $status]);
    }
}
