<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    protected $fillable = [
        'id',
        'brand_name',
        'brand_info',
        'kizoku_g',
    ];
}
