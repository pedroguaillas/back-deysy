<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalaryAdvanceOfPay extends Model
{
    public $timestamps = true;

    protected $fillable = [
        'salary_id', 'payment_id', 'amount', 'date'
    ];
}
