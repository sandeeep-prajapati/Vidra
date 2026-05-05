<?php

namespace App\Packages\Pro\TestBundle\Models\Models;

use Illuminate\Database\Eloquent\Model;

class TestBundle extends Model
{
    protected $table = 'test_bundles';

    protected $fillable = [
        'name',
        'description',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}