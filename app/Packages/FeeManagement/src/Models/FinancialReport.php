<?php

namespace App\Packages\FeeManagement\Models;

use Illuminate\Database\Eloquent\Model;

class FinancialReport extends Model
{
    protected $table = 'financial_reports';
    protected $primaryKey = 'report_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'report_type',
        'report_period_start',
        'report_period_end',
        'total_amount',
        'generated_at',
    ];

    protected $casts = [
        'report_period_start' => 'date',
        'report_period_end'   => 'date',
        'total_amount'        => 'decimal:2',
        'generated_at'        => 'datetime',
    ];
}
