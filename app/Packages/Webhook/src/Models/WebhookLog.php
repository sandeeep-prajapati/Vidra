<?php

namespace App\Packages\Webhook\Models;

use Illuminate\Database\Eloquent\Model;

class WebhookLog extends Model
{
    protected $table = 'webhook_logs';

    protected $fillable = [
        'event',
        'entity_type',
        'entity_id',
        'triggered_by',
        'status',
        'payload',
        'response',
    ];

    protected $casts = [
        'payload'  => 'array',
        'response' => 'array',
        'status'   => 'boolean',
    ];
}
