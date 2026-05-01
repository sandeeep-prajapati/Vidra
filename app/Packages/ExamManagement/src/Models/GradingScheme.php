<?php

namespace App\Packages\ExamManagement\Models;

use Illuminate\Database\Eloquent\Model;

class GradingScheme extends Model
{
    protected $table = 'grading_schemes';
    protected $primaryKey = 'grading_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'min_percentage', 'max_percentage', 'grade', 'remarks',
    ];
}
