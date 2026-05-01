<?php

namespace App\Packages\CommunicationManagement\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $table = 'messages';
    protected $primaryKey = 'message_id';

    protected $fillable = [
        'title', 'content', 'message_type', 'created_by',
        'scheduled_at', 'priority', 'is_sent',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'is_sent'      => 'boolean',
    ];

    public function sender()
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by');
    }

    public function recipients()
    {
        return $this->hasMany(MessageRecipient::class, 'message_id', 'message_id');
    }
}
