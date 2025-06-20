<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MenuController extends Controller
{
    public function menu()
    {
        $dl_order = DB::table('orders')
        ->join('order_items','order_items.order_id','=','orders.id')
        ->join('skus','skus.id','=','order_items.sku_id')
        ->where('orders.order_status',1)
        ->groupBy('skus.hinban_id')
        ->select('skus.hinban_id')
        ->exists();

        return view('menu',compact('dl_order'));
    }
}
