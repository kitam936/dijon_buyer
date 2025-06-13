<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Sku;
use App\Models\Unit;

class Hinban extends Model
{
    protected $fillable = [
        'id',
        'brand_id',
        'unit_id',
        'face_code',
        // 'hinban_id',
        'prod_code',
        'hinban_name',
        'hinban_info',
        'mix_rate',
        'season_code',
        'year_code',
        'shohin_gun',
        'kizoku_g',
        'seireki_unit',
        'kyotu_hinban',
        'vendor_id',
    ];

    public function skus()
    {
        return $this->hasMany(Sku::class, 'hinban_id');
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }
}
