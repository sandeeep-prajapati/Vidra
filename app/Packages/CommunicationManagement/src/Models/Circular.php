<?php

namespace App\Packages\CommunicationManagement\Models;

use Illuminate\Database\Eloquent\Model;

class Circular extends Model
{
    protected $table = 'circulars';
    protected $primaryKey = 'circular_id';

    protected $fillable = [
        'title', 'content', 'issued_by', 'issued_date',
        'target_audience', 'attachment_url',
    ];

    protected $casts = [
        'issued_date' => 'date',
    ];

    public function issuer()
    {
        return $this->belongsTo(\App\Models\User::class, 'issued_by');
    }
}
