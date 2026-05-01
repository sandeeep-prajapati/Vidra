<?php

namespace App\Packages\ClassManagement\Models;

use Illuminate\Database\Eloquent\Model;

class Batch extends Model
{
    protected $table = 'batches';

    protected $primaryKey = 'batch_id';

    public $incrementing = true;

    protected $keyType = 'int';

    protected $fillable = [
        'class_id',
        'section_id',
        'academic_year_id',
        'batch_name',
        'batch_code',
        'start_date',
        'end_date',
        'start_time',
        'end_time',
        'is_active',
        'description',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'start_date' => 'date',
        'end_date' => 'date',
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
    ];

    public function schoolClass()
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    public function section()
    {
        return $this->belongsTo(Section::class, 'section_id');
    }

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class, 'academic_year_id');
    }

    public function enrollments()
    {
        return $this->hasMany(\App\Packages\StudentManagement\Models\StudentEnrollment::class, 'batch_id');
    }

    public function promotionFrom()
    {
        return $this->hasMany(\App\Packages\StudentManagement\Models\PromotionHistory::class, 'from_batch_id');
    }

    public function promotionTo()
    {
        return $this->hasMany(\App\Packages\StudentManagement\Models\PromotionHistory::class, 'to_batch_id');
    }
}
