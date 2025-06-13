<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Shop;
use App\Models\OrderItem;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'vendor_id',
        'order_date',
        'order_status',
        'order_memo'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
