<?php

namespace App\Packages\DataTransfer\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TransferJobTrack extends Model
{
    protected $table = 'transfer_job_tracks';

    protected $fillable = [
        'transfer_job_id', 'user_id', 'state', 'type', 'action',
        'processed_rows_count', 'invalid_rows_count', 'errors_count',
        'errors', 'file_path', 'output_file_path', 'error_file_path',
        'summary', 'started_at', 'completed_at',
    ];

    protected $casts = [
        'errors'       => 'array',
        'summary'      => 'array',
        'started_at'   => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function transferJob(): BelongsTo
    {
        return $this->belongsTo(TransferJob::class, 'transfer_job_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }
}
