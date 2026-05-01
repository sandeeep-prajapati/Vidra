<?php

namespace App\Packages\FeeManagement\Models;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    protected $table = 'expenses';
    protected $primaryKey = 'expense_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'expense_date',
        'amount',
        'expense_category',
        'description',
    ];

    protected $casts = [
        'expense_date' => 'date',
        'amount'       => 'decimal:2',
    ];
}
