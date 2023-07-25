<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExpenseItem extends Model
{
    protected $fillable = [
        'expense_id', 'month', 'amount', 'pay_method', 'date'
    ];
}
