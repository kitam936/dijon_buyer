<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Hinban;
use App\Models\Cart;

class sku extends Model
{
    protected $fillable = [
        'id',
        'seq',
        'hinban_id',
        'sku_code',
        'col_id',
        'size_id',
        'local_cur_price',
        'length',
        'width',
        'sku_image',

    ];

    public function hinban()
    {
        return $this->belongsTo(Hinban::class, 'hinban_id');
    }

    public function carts()
    {
        return $this->hasMany(Cart::class);
    }

}
