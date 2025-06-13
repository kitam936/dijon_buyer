<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PreData extends Model
{
    protected $fillable = [
        'id',
        'year_code',
        'shohin_gun',
        'brand_id',
        'seireki_unit',
        'unit_id',
        'face_code',
        'hinban_id' ,
        'kyotu_hinban'
    ];
}
