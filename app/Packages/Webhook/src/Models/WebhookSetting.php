<?php

namespace App\Packages\Webhook\Models;

use Illuminate\Database\Eloquent\Model;

class WebhookSetting extends Model
{
    protected $table = 'webhook_settings';

    protected $fillable = ['field', 'value', 'extra'];

    protected $casts = ['extra' => 'array'];
}
