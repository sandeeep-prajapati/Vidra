<?php

namespace App\Packages\StaffManagement\Models;

use Illuminate\Database\Eloquent\Model;

class SalaryDetail extends Model
{
    protected $table = 'salary_details';

    protected $primaryKey = 'salary_id';

    public $incrementing = true;

    protected $keyType = 'int';

    protected $fillable = ['staff_id', 'basic_salary', 'allowances', 'deductions', 'net_salary', 'payment_date'];

    protected $casts = [
        'basic_salary' => 'decimal:2',
        'allowances' => 'decimal:2',
        'deductions' => 'decimal:2',
        'net_salary' => 'decimal:2',
        'payment_date' => 'date',
    ];

    public function staff()
    {
        return $this->belongsTo(Staff::class, 'staff_id');
    }
}
