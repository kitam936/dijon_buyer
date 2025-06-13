<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CostRate extends Model
{
    protected $fillable = [
        'id',
        'cost_rate',
        'cost_memo',
    ];
}
