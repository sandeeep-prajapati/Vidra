<?php

namespace App\Packages\ClassManagement\Models;

use App\Packages\StudentManagement\Models\PromotionHistory;
use App\Packages\StudentManagement\Models\StudentEnrollment;
use Illuminate\Database\Eloquent\Model;

class AcademicYear extends Model
{
    protected $table = 'academic_years';

    protected $primaryKey = 'academic_year_id';

    public $incrementing = true;

    protected $keyType = 'int';

    protected $fillable = [
        'year_range',
        'start_date',
        'end_date',
        'is_current',
        'description',
    ];

    protected $casts = [
        'is_current' => 'boolean',
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function classes()
    {
        return $this->hasMany(SchoolClass::class, 'academic_year_id');
    }

    public function batches()
    {
        return $this->hasMany(Batch::class, 'academic_year_id');
    }

    public function enrollments()
    {
        return $this->hasMany(StudentEnrollment::class, 'academic_year_id');
    }

    public function promotionFrom()
    {
        return $this->hasMany(PromotionHistory::class, 'from_academic_year_id');
    }

    public function promotionTo()
    {
        return $this->hasMany(PromotionHistory::class, 'to_academic_year_id');
    }
}
