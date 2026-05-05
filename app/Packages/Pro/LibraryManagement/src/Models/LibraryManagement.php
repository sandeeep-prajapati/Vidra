<?php

namespace App\Packages\Pro\LibraryManagement\Models\Models;

use Illuminate\Database\Eloquent\Model;

class LibraryManagement extends Model
{
    protected $table = 'library_managements';

    protected $fillable = [
        'name',
        'description',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}