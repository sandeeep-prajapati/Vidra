<?php

namespace App\Packages\FeeManagement\Models;

use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    protected $table = 'discounts';
    protected $primaryKey = 'discount_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'discount_name',
        'discount_amount',
        'discount_type',
        'description',
    ];

    protected $casts = [
        'discount_amount' => 'decimal:2',
    ];

    public function studentDiscounts()
    {
        return $this->hasMany(StudentDiscount::class, 'discount_id', 'discount_id');
    }
}
