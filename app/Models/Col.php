<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Sku;

class Col extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'col_name',
    ];

    public function skus()
    {
        return $this->hasMany(Sku::class);
    }
}
