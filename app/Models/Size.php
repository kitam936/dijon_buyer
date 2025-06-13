<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Sku;

class Size extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'size_name',
    ];

    public function skus()
    {
        return $this->hasMany(Sku::class);
    }
}
