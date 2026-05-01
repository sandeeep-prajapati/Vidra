<?php

namespace App\Packages\FeeManagement\Models;

use App\Packages\ClassManagement\Models\AcademicYear;
use App\Packages\ClassManagement\Models\SchoolClass;
use Illuminate\Database\Eloquent\Model;

class FeeStructure extends Model
{
    protected $table = 'fee_structures';
    protected $primaryKey = 'fee_structure_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'class_id',
        'fee_category_id',
        'amount',
        'due_date',
        'academic_year_id',
    ];

    protected $casts = [
        'amount'   => 'decimal:2',
        'due_date' => 'date',
    ];

    public function schoolClass()
    {
        return $this->belongsTo(SchoolClass::class, 'class_id', 'class_id');
    }

    public function feeCategory()
    {
        return $this->belongsTo(FeeCategory::class, 'fee_category_id', 'fee_category_id');
    }

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class, 'academic_year_id', 'academic_year_id');
    }

    public function studentFees()
    {
        return $this->hasMany(StudentFee::class, 'fee_structure_id', 'fee_structure_id');
    }

    public function studentDiscounts()
    {
        return $this->hasMany(StudentDiscount::class, 'fee_structure_id', 'fee_structure_id');
    }
}
