<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    public $timestamps = true;

    protected $fillable = [
        'cliente_auditwhole_ruc', 'year_month', 'amount', 'note', 'type', 'voucher', 'date'
    ];
}
