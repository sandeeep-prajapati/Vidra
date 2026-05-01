<?php

namespace App\Packages\CommunicationManagement\Models;

use Illuminate\Database\Eloquent\Model;

class MessageRecipient extends Model
{
    protected $table = 'message_recipients';
    protected $primaryKey = 'recipient_id';

    protected $fillable = [
        'message_id', 'user_id', 'status', 'delivered_at', 'read_at',
    ];

    protected $casts = [
        'delivered_at' => 'datetime',
        'read_at'      => 'datetime',
    ];

    public function message()
    {
        return $this->belongsTo(Message::class, 'message_id', 'message_id');
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }
}
