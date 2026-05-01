<?php

namespace App\Packages\FeeManagement\Models;

use App\Packages\StudentManagement\Models\Student;
use Illuminate\Database\Eloquent\Model;

class StudentDiscount extends Model
{
    protected $table = 'student_discounts';
    protected $primaryKey = 'student_discount_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'student_id',
        'discount_id',
        'fee_structure_id',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id', 'student_id');
    }

    public function discount()
    {
        return $this->belongsTo(Discount::class, 'discount_id', 'discount_id');
    }

    public function feeStructure()
    {
        return $this->belongsTo(FeeStructure::class, 'fee_structure_id', 'fee_structure_id');
    }
}
