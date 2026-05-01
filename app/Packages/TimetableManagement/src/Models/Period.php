<?php

namespace App\Packages\TimetableManagement\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Period extends Model
{
    protected $table = 'periods';
    protected $primaryKey = 'period_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'start_time',
        'end_time',
    ];

    public function timetables(): HasMany
    {
        return $this->hasMany(Timetable::class, 'period_id', 'period_id');
    }

    public function getLabelAttribute(): string
    {
        return $this->start_time . ' – ' . $this->end_time;
    }
}
