<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Sku;

class Cart extends Model
{
    protected $fillable = [
        'user_id',
        'sku_id',
        'local_cur_price',
        'pcs'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function sku()
    {
        return $this->belongsTo(Sku::class);
    }


}
