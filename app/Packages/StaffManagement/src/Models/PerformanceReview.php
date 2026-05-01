<?php

namespace App\Packages\StaffManagement\Models;

use Illuminate\Database\Eloquent\Model;

class PerformanceReview extends Model
{
    protected $table = 'performance_reviews';

    protected $primaryKey = 'review_id';

    public $incrementing = true;

    protected $keyType = 'int';

    protected $fillable = ['staff_id', 'review_period_start', 'review_period_end', 'rating', 'comments', 'reviewed_by'];

    protected $casts = [
        'review_period_start' => 'date',
        'review_period_end' => 'date',
        'rating' => 'decimal:1',
    ];

    public function staff()
    {
        return $this->belongsTo(Staff::class, 'staff_id');
    }
}
