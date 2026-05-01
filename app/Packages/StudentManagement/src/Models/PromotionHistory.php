<?php

namespace App\Packages\StudentManagement\Models;

use App\Packages\ClassManagement\Models\AcademicYear;
use App\Packages\ClassManagement\Models\Batch;
use Illuminate\Database\Eloquent\Model;

class PromotionHistory extends Model
{
    protected $table = 'promotion_history';

    protected $primaryKey = 'promotion_id';

    public $incrementing = true;

    protected $keyType = 'int';

    protected $fillable = [
        'student_id',
        'from_batch_id',
        'to_batch_id',
        'from_academic_year_id',
        'to_academic_year_id',
        'promotion_date',
        'remarks',
    ];

    protected $casts = [
        'promotion_date' => 'date',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function fromBatch()
    {
        return $this->belongsTo(Batch::class, 'from_batch_id');
    }

    public function toBatch()
    {
        return $this->belongsTo(Batch::class, 'to_batch_id');
    }

    public function fromAcademicYear()
    {
        return $this->belongsTo(AcademicYear::class, 'from_academic_year_id');
    }

    public function toAcademicYear()
    {
        return $this->belongsTo(AcademicYear::class, 'to_academic_year_id');
    }
}
