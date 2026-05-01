<?php

namespace App\Packages\DataTransfer\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TransferJob extends Model
{
    protected $table = 'transfer_jobs';

    protected $fillable = [
        'code', 'entity_type', 'type', 'action',
        'validation_strategy', 'allowed_errors',
        'field_separator', 'file_path', 'filters',
    ];

    protected $casts = [
        'filters' => 'array',
    ];

    public function tracks(): HasMany
    {
        return $this->hasMany(TransferJobTrack::class, 'transfer_job_id');
    }

    public function latestTrack()
    {
        return $this->hasOne(TransferJobTrack::class, 'transfer_job_id')->latestOfMany();
    }
}
