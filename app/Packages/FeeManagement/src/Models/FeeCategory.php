<?php

namespace App\Packages\FeeManagement\Models;

use Illuminate\Database\Eloquent\Model;

class FeeCategory extends Model
{
    protected $table = 'fee_categories';
    protected $primaryKey = 'fee_category_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'category_name',
        'description',
    ];

    public function feeStructures()
    {
        return $this->hasMany(FeeStructure::class, 'fee_category_id', 'fee_category_id');
    }
}
