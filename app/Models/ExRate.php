<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExRate extends Model
{
    protected $fillable = [
        'id',
        'ex_rate',
        'ex_memo',
    ];
}
