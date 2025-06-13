<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Shop;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Jobs\SendOrderResvMail;
use App\Jobs\SendOrderResponseMail;

class OrderController extends Controller
{
    public function order_index()
    {
        $user = DB::table('users')
        ->join('shops','shops.id','users.shop_id')
        ->where('users.id',Auth::id())
        ->select('users.shop_id','shops.shop_name','users.name','users.id')
        ->first();

        $order_hs = DB::table('orders')
        ->join('order_items','orders.id','order_items.order_id')
        ->join('users','users.id','orders.user_id')
        ->join('shops','shops.id','users.shop_id')
        ->join('skus','skus.id','order_items.sku_id')
        ->join('hinbans','hinbans.id','skus.hinban_id')
        ->join('statuses','statuses.id','orders.status')
        ->where('orders.shop_id',$user->shop_id)
        ->where('orders.order_date','>', (Carbon::today()->subDay(60)))
        ->groupBy('orders.id','orders.order_date','orders.user_id','users.name','shops.shop_name','statuses.id','statuses.status')
        ->selectRaw('orders.id,orders.order_date,orders.user_id,users.name,shops.shop_name,sum(order_items.pcs) as pcs,statuses.id as status_id,statuses.status')
        ->orderBy('orders.id','desc')
        ->get();



        $all_order_hs = DB::table('orders')
        ->join('order_items','orders.id','order_items.order_id')
        ->join('users','users.id','orders.user_id')
        ->join('shops','shops.id','users.shop_id')
        ->join('skus','skus.id','order_items.sku_id')
        ->join('hinbans','hinbans.id','skus.hinban_id')
        ->join('statuses','statuses.id','orders.status')
        ->where('orders.order_date','>', (Carbon::today()->subDay(60)))
        ->groupBy('orders.id','orders.order_date','orders.user_id','users.name','statuses.id','statuses.status','shops.shop_name')
        ->selectRaw('orders.id,orders.order_date,orders.user_id,users.name,statuses.id as status_id,statuses.status,shops.shop_name,sum(order_items.pcs) as pcs')
        ->orderBy('orders.id','desc')
        ->get();

        $dl_new = DB::table('orders') //未ＤＬ判定用
        ->where('orders.status',1)
        ->exists();
        // $order_fs = DB::table('orders')
        // ->join('order_items','orders.id','order_items.order_id')
        // ->join('users','users.id','orders.user_id')
        // ->join('shops','shops.id','users.shop_id')
        // ->join('skus','skus.id','order_items.sku_id')
        // ->join('hinbans','hinbans.id','skus.hinban_id')
        // ->where('orders.shop_id',$user->shop_id)
        // ->groupBy('orders.id','orders.user_id','users.name','shops.shop_name','order_items.sku_id')
        // ->selectRaw('orders.id,orders.user_id,users.name,shops.shop_name,order_items.sku_id,sum(order_items.pcs) as pcs')
        // ->get();
        // dd($dl_new);
        return view('order.order_index',compact('user','order_hs','all_order_hs','dl_new'));
    }

    public function order_detail($id)
    {
        $user = DB::table('users')
        ->join('shops','shops.id','users.shop_id')
        ->where('users.id',Auth::id())
        ->select('users.shop_id','shops.shop_name','users.name','users.id')
        ->first();

        $order_hs = DB::table('orders')
        ->join('users','users.id','orders.user_id')
        ->join('shops','shops.id','users.shop_id')
        ->join('statuses','statuses.id','orders.status')
        ->where('orders.id',$id)
        ->groupBy('orders.id','orders.order_date','orders.user_id','users.name','shops.shop_name','statuses.status','orders.comment')
        ->selectRaw('orders.id,orders.order_date,orders.user_id,users.name,shops.shop_name,statuses.status,orders.comment')
        ->first();
        $order_fs = DB::table('orders')
        ->join('order_items','orders.id','order_items.order_id')
        ->join('skus','skus.id','order_items.sku_id')
        ->join('hinbans','hinbans.id','skus.hinban_id')
        ->where('orders.id',$id)
        ->groupBy('order_items.id','order_items.sku_id','skus.hinban_id','skus.col_id','skus.size_id','hinbans.hinban_name')
        ->selectRaw('order_items.id,order_items.sku_id,skus.hinban_id,skus.col_id,skus.size_id,hinbans.hinban_name,sum(order_items.pcs) as pcs')
        ->get();
        $order_total = DB::table('orders')
        ->join('order_items','orders.id','order_items.order_id')
        ->join('skus','skus.id','order_items.sku_id')
        ->join('shops','shops.id','orders.shop_id')
        ->join('hinbans','hinbans.id','skus.hinban_id')
        ->where('orders.id',$id)
        ->groupBy('orders.id',)
        ->selectRaw('orders.id,sum(order_items.pcs) as total_pcs,sum(order_items.pcs*hinbans.m_price) as total_baika,sum(FLOOR(order_items.pcs*hinbans.m_price*shops.rate/1000)) as total_genka')
        ->first();
        // dd($user,$order_hs,$order_fs,$order_total);
        return view('order.order_detail',compact('user','order_hs','order_fs','order_total'));
    }

    public function order_edit($id)
    {
        $user = DB::table('users')
        ->join('shops','shops.id','users.shop_id')
        ->where('users.id',Auth::id())
        ->select('users.shop_id','shops.shop_name','users.name','users.id as user_id')
        ->first();
        $statuses = DB::table('statuses')
        ->get();
        $order_hs = DB::table('orders')
        ->join('users','users.id','orders.user_id')
        ->join('shops','shops.id','users.shop_id')
        ->join('statuses','statuses.id','orders.status')
        ->where('orders.id',$id)
        ->groupBy('orders.id','orders.order_date','orders.user_id','users.name','shops.shop_name','statuses.status','statuses.id','orders.comment')
        ->selectRaw('orders.id,orders.order_date,orders.user_id,users.name,shops.shop_name,statuses.id as status_id,statuses.status,orders.comment')
        ->first();
        $order_fs = DB::table('orders')
        ->join('order_items','orders.id','order_items.order_id')
        ->join('skus','skus.id','order_items.sku_id')
        ->join('hinbans','hinbans.id','skus.hinban_id')
        ->where('orders.id',$id)
        ->groupBy('order_items.id','order_items.sku_id','skus.hinban_id','skus.col_id','skus.size_id','hinbans.hinban_name')
        ->selectRaw('order_items.id,order_items.sku_id,skus.hinban_id,skus.col_id,skus.size_id,hinbans.hinban_name,sum(order_items.pcs) as pcs')
        ->get();
        // dd($user,$order_hs,$order_fs);
        return view('order.order_edit',compact('user','order_hs','order_fs','statuses'));
    }

    public function order_update(Request $request, $id)
    {
        $order=Order::findOrFail($id);

        $order->status = $request->status_id;
        $order->comment = $request->comment;

        // dd($order);

        $order->save();

        // ここでメール送信

        $users = User::Where('mailService','=',1)
        ->where('id','=',$request->user_id2)
        ->get()
        ->toArray();

        $order_info = Order::Where('orders.id',$request->order_id2)
        ->join('shops','shops.id','orders.shop_id')
        ->join('users','users.id','orders.user_id')
        ->select('orders.id as order_id','users.name','users.email','orders.shop_id','shops.shop_name')
        ->first()
        ->toArray();

        foreach($users as $user){
            SendOrderResponseMail::dispatch($order_info,$user);
        }

        return to_route('order_index')->with(['message'=>'追加発注情報が更新されました','status'=>'info']);
    }

    public function confirm0()
    {
        // $userId = Auth::id();
        $user = DB::table('users')
        ->where('users.id',Auth::id())
        ->select('users.shop_id','users.id')
        ->first();
        $cartItems = Cart::where('user_id', $user->id)->get();

        if ($cartItems->isEmpty()) {
            return to_route('cart_index')->with(['message'=>'カートに商品が入っていません','status'=>'alert']);
        }

        $order = Order::create([
            'user_id' => $user->id,
            'shop_id' => $user->shop_id,
            'order_date' => now(),
            'status' => 1,
            'comment' => null,
        ]);

        foreach ($cartItems as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'sku_id' => $item->sku_id,
                'pcs' => $item->pcs,
            ]);
        }

        Cart::where('user_id', $user->id)->delete();

        // ここでメール送信

        $users = User::Where('mailService','=',1)
        ->where('shop_id',101)
        ->get()->toArray();

        // $order_info = Order::findOrFail($order->id)
        // ->toArray();

        $order_info = Order::Where('orders.id',$order->id)
        ->join('shops','shops.id','orders.shop_id')
        ->join('users','users.id','orders.user_id')
        ->select('orders.id as order_id','users.name','users.email','orders.shop_id','shops.shop_name')
        ->first()
        ->toArray();


        // dd($users,$order_info);
        // dd($users);


        foreach($users as $user){

            // dd($user,$order_info);
            SendOrderResvMail::dispatch($order_info,$user);
        }

        return redirect()->route('order_index')->with(['message'=>'オーダーが確定しました','status'=>'info']);
    }


    public function confirm()
    {
        $user = DB::table('users')
            ->where('users.id', Auth::id())
            ->select('users.shop_id', 'users.id')
            ->first();

        $cartItems = Cart::where('user_id', $user->id)->get();

        if ($cartItems->isEmpty()) {
            return to_route('cart_index')->with(['message' => 'カートに商品が入っていません', 'status' => 'alert']);
        }

        DB::beginTransaction();

        try {
            $order = Order::create([
                'user_id' => $user->id,
                'shop_id' => $user->shop_id,
                'order_date' => now(),
                'status' => 1,
                'comment' => null,
            ]);

            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'sku_id' => $item->sku_id,
                    'pcs' => $item->pcs,
                ]);
            }

            Cart::where('user_id', $user->id)->delete();

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with(['message' => '注文処理中にエラーが発生しました: ' . $e->getMessage(), 'status' => 'alert']);
        }

        // ここでメール送信（非同期）
        $users = User::where('mailService', 1)
            ->where('shop_id', 101)
            ->get()
            ->toArray();

        $order_info = Order::where('orders.id', $order->id)
            ->join('shops', 'shops.id', 'orders.shop_id')
            ->join('users', 'users.id', 'orders.user_id')
            ->select(
                'orders.id as order_id',
                'users.name',
                'users.email',
                'orders.shop_id',
                'shops.shop_name'
            )
            ->first()
            ->toArray();

        foreach ($users as $user) {
            SendOrderResvMail::dispatch($order_info, $user);
        }

        return redirect()->route('order_index')->with(['message' => 'オーダーが確定しました', 'status' => 'info']);
    }

    public function result_destroy($id)
    {
        $work = Order::findOrFail($id);
        $work->delete();
        return redirect()->route('order_index')->with(['message'=>'削除しました','status'=>'alert']);
    }

    public function order_point_edit($id)
    {
        $order_point = DB::table('order_points')
        ->first();


        // dd($order_point);
        return view('order.order_point_edit',compact('order_point'));
    }

    public function order_point_update(Request $request, $id)
    {
        $order_point = DB::table('order_points')
        ->where('id',$id)
        ->update(['order_stop_pcs' => $request->pcs]);

        return redirect()->route('order_index')->with(['message'=>'発注可能在庫数が更新されました','status'=>'info']);
    }
}
