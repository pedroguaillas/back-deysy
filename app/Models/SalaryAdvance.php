<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalaryAdvance extends Model
{
    public $timestamps = true;

    protected $fillable = [
        'salary_id', 'description', 'amount', 'date'
    ];
}
