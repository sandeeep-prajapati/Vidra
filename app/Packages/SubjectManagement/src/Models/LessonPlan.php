<?php

namespace App\Packages\SubjectManagement\Models;

use Illuminate\Database\Eloquent\Model;

class LessonPlan extends Model
{
    protected $table = 'lesson_plans';

    protected $primaryKey = 'lesson_plan_id';

    public $incrementing = true;

    protected $keyType = 'int';

    protected $fillable = [
        'curriculum_id',
        'topic_name',
        'objectives',
        'start_date',
        'end_date',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date'   => 'date',
    ];

    public function curriculum()
    {
        return $this->belongsTo(Curriculum::class, 'curriculum_id', 'curriculum_id');
    }
}
