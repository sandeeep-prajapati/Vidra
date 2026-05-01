<?php

namespace App\Packages\CommunicationManagement\Models;

use Illuminate\Database\Eloquent\Model;

class NotificationSetting extends Model
{
    protected $table = 'notification_settings';
    protected $primaryKey = 'setting_id';

    protected $fillable = [
        'user_id', 'allow_sms', 'allow_email',
        'allow_app', 'allow_announcements', 'allow_circulars',
    ];

    protected $casts = [
        'allow_sms'           => 'boolean',
        'allow_email'         => 'boolean',
        'allow_app'           => 'boolean',
        'allow_announcements' => 'boolean',
        'allow_circulars'     => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }
}
