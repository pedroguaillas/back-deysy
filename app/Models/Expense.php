<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Expense extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'description', 'amount'
    ];

    public function expenseitems()
    {
        return $this->hasMany(ExpenseItem::class);
    }
}
