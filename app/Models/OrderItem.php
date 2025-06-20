<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Order;
use App\Models\Sku;



class OrderItem extends Model
{
    protected $fillable = [
        'order_id',
        'sku_id',
        'local_cur_price',
        'local_yen_price',
        'expected_price',
        'order_pcs'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function sku()
    {
        return $this->belongsTo(Sku::class);
    }


}
