<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Jobs\SendResvOrderMail;
use App\Jobs\SendResvCommentMail;
use App\Jobs\SendCreateHinbanMail;

class OrderController extends Controller
{
    public function order_index()
    {
        $user = DB::table('users')
        ->where('users.id',Auth::id())
        ->select('users.name','users.id')
        ->first();

        $order_hs = DB::table('orders')
        ->join('order_items','orders.id','order_items.order_id')
        ->join('users','users.id','orders.user_id')
        ->join('skus','skus.id','order_items.sku_id')
        ->join('hinbans','hinbans.id','skus.hinban_id')
        ->join('order_statuses','order_statuses.id','orders.order_status')
        ->where('orders.user_id',$user->id)
        ->where('orders.order_date','>', (Carbon::today()->subDay(60)))
        ->groupBy('orders.id','orders.order_date','orders.user_id','users.name','order_statuses.id','orders.order_status','order_statuses.status_name')
        ->selectRaw('orders.id,orders.order_date,orders.user_id,users.name,sum(order_items.order_pcs) as pcs,orders.order_status as status_id,order_statuses.status_name')
        ->orderBy('orders.id','desc')
        ->get();



        $all_order_hs = DB::table('orders')
        ->join('order_items','orders.id','order_items.order_id')
        ->join('users','users.id','orders.user_id')
        ->join('skus','skus.id','order_items.sku_id')
        ->join('hinbans','hinbans.id','skus.hinban_id')
        ->join('order_statuses','order_statuses.id','orders.order_status')
        ->where('orders.order_date','>', (Carbon::today()->subDay(60)))
        ->groupBy('orders.id','orders.order_date','orders.user_id','users.name','order_statuses.id','order_statuses.status_name')
        ->selectRaw('orders.id,orders.order_date,orders.user_id,users.name,order_statuses.id as status_id,order_statuses.status_name,sum(order_items.order_pcs) as pcs')
        ->orderBy('orders.id','desc')
        ->get();

        $dl_new = DB::table('orders') //未ＤＬ判定用
        ->where('orders.order_status',1)
        ->exists();

        // dd($order_hs,$all_order_hs,$dl_new);

        return view('order.order_index',compact('user','order_hs','all_order_hs','dl_new'));
    }

    public function order_detail($id)
    {
        $user = DB::table('users')
        ->where('users.id',Auth::id())
        ->select('users.name','users.id')
        ->first();
        $cost_rate = DB::table('cost_rates')
            ->first();

        $order_hs = DB::table('orders')
        ->join('users','users.id','orders.user_id')
        ->join('order_statuses','order_statuses.id','orders.order_status')
        ->where('orders.id',$id)
        ->groupBy('orders.id','orders.order_date','orders.user_id','users.name','order_statuses.status_name','orders.order_memo')
        ->selectRaw('orders.id,orders.order_date,orders.user_id,users.name,order_statuses.status_name,orders.order_memo')
        ->first();
        $order_fs = DB::table('orders')
        ->join('order_items','orders.id','order_items.order_id')
        ->join('skus','skus.id','order_items.sku_id')
        ->join('hinbans','hinbans.id','skus.hinban_id')
        ->where('orders.id',$id)
        ->groupBy('order_items.id','order_items.sku_id','skus.hinban_id','skus.col_id','skus.size_id','hinbans.hinban_name','order_items.local_cur_price','order_items.local_yen_price','order_items.expected_price','skus.sku_image')
        ->selectRaw('order_items.id,order_items.sku_id,skus.hinban_id,skus.col_id,skus.size_id,hinbans.hinban_name,sum(order_items.order_pcs) as pcs,order_items.local_cur_price,order_items.local_yen_price,order_items.expected_price,skus.sku_image')
        ->get();
        $order_total = DB::table('orders')
        ->join('order_items','orders.id','order_items.order_id')
        ->join('skus','skus.id','order_items.sku_id')
        ->join('hinbans','hinbans.id','skus.hinban_id')
        ->where('orders.id',$id)
        ->groupBy('orders.id')
        ->selectRaw('orders.id,sum(order_items.order_pcs) as total_pcs,sum(order_items.order_pcs*order_items.local_cur_price) as local_cur_total,sum(order_items.order_pcs*order_items.local_yen_price) as local_yen_total,sum(order_items.order_pcs*order_items.expected_price) as expected_total')
        ->first();
        // dd($user,$order_hs,$order_fs,$order_total);
        return view('order.order_detail',compact('user','order_hs','order_fs','order_total'));
    }

    public function order_image_list(Request $request)
    {
        if($request->order == 'h'){
            $user = DB::table('users')
            ->where('users.id',Auth::id())
            ->select('users.name','users.id as user_id')
            ->first();
            $statuses = DB::table('order_statuses')
            ->get();

            $years=DB::table('hinbans')
            ->select(['year_code'])
            ->groupBy(['year_code'])
            ->orderBy('year_code','asc')
            ->get();

            $brands=DB::table('brands')
            ->select(['id','brand_name'])
            ->groupBy(['id','brand_name'])
            ->orderBy('id','asc')
            ->get();

            $units=DB::table('units')
            ->select(['units.id'])
            ->groupBy(['units.id'])
            ->orderBy('units.id','asc')
            ->get();

            $faces=DB::table('faces')
            ->select(['faces.face_code','faces.face_item'])
            ->groupBy(['faces.face_code','faces.face_item'])
            ->orderBy('faces.face_code','asc')
            ->get();


            $orders = DB::table('orders')
            ->join('order_items','orders.id','order_items.order_id')
            ->join('skus','skus.id','order_items.sku_id')
            ->join('hinbans','hinbans.id','skus.hinban_id')
            ->join('brands','brands.id','hinbans.brand_id')
            ->join('faces','faces.face_code','hinbans.face_code')
            ->where('hinbans.year_code','LIKE','%'.$request->year_code.'%')
            ->where('hinbans.brand_id','LIKE','%'.$request->brand_code.'%')
            ->where('hinbans.unit_id','LIKE','%'.$request->unit_code.'%')
            ->where('hinbans.face_code','LIKE','%'.$request->face_code.'%')
            ->groupBy('order_items.id','order_items.sku_id','skus.hinban_id','skus.col_id','skus.size_id','hinbans.hinban_name','order_items.local_cur_price','order_items.local_yen_price','order_items.expected_price','skus.sku_image','hinbans.year_code','brands.brand_name','hinbans.face_code','hinbans.unit_id')
            ->selectRaw('order_items.id,order_items.sku_id,skus.hinban_id,skus.col_id,skus.size_id,hinbans.hinban_name,sum(order_items.order_pcs) as pcs,order_items.local_cur_price,order_items.local_yen_price,order_items.expected_price,skus.sku_image,hinbans.year_code,brands.brand_name,hinbans.face_code,hinbans.unit_id')
            ->orderBy('hinbans.id','asc')
            ->paginate(100);
            // dd($user,$orders);
            return view('order.order_image_list',compact('user','orders','statuses','brands','units','faces','years'));
        }else{
            $user = DB::table('users')
            ->where('users.id',Auth::id())
            ->select('users.name','users.id as user_id')
            ->first();
            $statuses = DB::table('order_statuses')
            ->get();

            $years=DB::table('hinbans')
            ->select(['year_code'])
            ->groupBy(['year_code'])
            ->orderBy('year_code','asc')
            ->get();

            $brands=DB::table('brands')
            ->select(['id','brand_name'])
            ->groupBy(['id','brand_name'])
            ->orderBy('id','asc')
            ->get();

            $units=DB::table('units')
            ->select(['units.id'])
            ->groupBy(['units.id'])
            ->orderBy('units.id','asc')
            ->get();

            $faces=DB::table('faces')
            ->select(['faces.face_code','faces.face_item'])
            ->groupBy(['faces.face_code','faces.face_item'])
            ->orderBy('faces.face_code','asc')
            ->get();

            $orders = DB::table('orders')
            ->join('order_items','orders.id','order_items.order_id')
            ->join('skus','skus.id','order_items.sku_id')
            ->join('hinbans','hinbans.id','skus.hinban_id')
            ->join('brands','brands.id','hinbans.brand_id')
            ->join('faces','faces.face_code','hinbans.face_code')
            ->where('hinbans.year_code','LIKE','%'.$request->year_code.'%')
            ->where('hinbans.brand_id','LIKE','%'.$request->brand_code.'%')
            ->where('hinbans.unit_id','LIKE','%'.$request->unit_code.'%')
            ->where('hinbans.face_code','LIKE','%'.$request->face_code.'%')
            ->groupBy('order_items.id','order_items.sku_id','skus.hinban_id','skus.col_id','skus.size_id','hinbans.hinban_name','order_items.local_cur_price','order_items.local_yen_price','order_items.expected_price','skus.sku_image','hinbans.year_code','brands.brand_name','hinbans.face_code','hinbans.unit_id')
            ->selectRaw('order_items.id,order_items.sku_id,skus.hinban_id,skus.col_id,skus.size_id,hinbans.hinban_name,sum(order_items.order_pcs) as pcs,order_items.local_cur_price,order_items.local_yen_price,order_items.expected_price,skus.sku_image,hinbans.year_code,brands.brand_name,hinbans.face_code,hinbans.unit_id')
            ->orderBy('orders.created_at','desc')
            ->paginate(100);
            // dd($user,$orders);
            return view('order.order_image_list',compact('user','orders','statuses','brands','units','faces','years'));
        }
    }

    public function order_update(Request $request, $id)
    {
        $order=Order::findOrFail($id);

        $order->status = $request->status_id;
        $order->comment = $request->comment;

        // dd($order);

        $order->save();

        // ここでメール送信

        // $users = User::Where('mailService','=',1)
        // ->where('id','=',$request->user_id2)
        // ->get()
        // ->toArray();

        // $order_info = Order::Where('orders.id',$request->order_id2)
        // ->join('users','users.id','orders.user_id')
        // ->select('orders.id as order_id','users.name','users.email','orders.shop_id')
        // ->first()
        // ->toArray();

        // foreach($users as $user){
        //     SendOrderResponseMail::dispatch($order_info,$user);
        // }

        return to_route('order_index')->with(['message'=>'追加発注情報が更新されました','status'=>'info']);
    }

    public function confirm0()
    {
        // $userId = Auth::id();
        $user = DB::table('users')
        ->where('users.id',Auth::id())
        ->select('users.id')
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
            'memo' => null,
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


    public function confirm(Request $request)
    {
        $user = DB::table('users')
            ->where('users.id', Auth::id())
            ->select('users.name', 'users.id')
            ->first();

        $cartItems = Cart::where('user_id', $user->id)->get();

        $vendor = $request->vendor_id ;

        $ex_rate = DB::table('ex_rates')
            ->first();

        $cost_rate = DB::table('cost_rates')
            ->first();

        if ($cartItems->isEmpty()) {
            return to_route('cart_index')->with(['message' => 'カートに商品が入っていません', 'status' => 'alert']);
        }

        $request->validate([
            'vendor_id' => 'required',

        ]);

        DB::beginTransaction();

        try {
            $order = Order::create([
                'user_id' => $user->id,
                'vendor_id' => $vendor,
                'order_date' => now(),
                'order_status' => 1,
                'order_memo' => $request->memo,
            ]);

            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'sku_id' => $item->sku_id,
                    'order_pcs' => $item->pcs,
                    'local_cur_price' => $item->local_cur_price,
                    'local_yen_price' => floor($item->local_cur_price * $ex_rate->ex_rate / 100),
                    'expected_price' => floor(($item->local_cur_price * $ex_rate->ex_rate / 100) * $cost_rate->cost_rate / 100),
                ]);
            }

            Cart::where('user_id', $user->id)->delete();

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with(['message' => '発注処理中にエラーが発生しました: ' . $e->getMessage(), 'status' => 'alert']);
        }

        // ここでメール送信（非同期）
        // $users = User::where('mailService', 1)
        //     ->where('shop_id', 101)
        //     ->get()
        //     ->toArray();

        // $order_info = Order::where('orders.id', $order->id)
        //     ->join('shops', 'shops.id', 'orders.shop_id')
        //     ->join('users', 'users.id', 'orders.user_id')
        //     ->select(
        //         'orders.id as order_id',
        //         'users.name',
        //         'users.email',
        //         'orders.shop_id',
        //         'shops.shop_name'
        //     )
        //     ->first()
        //     ->toArray();

        // foreach ($users as $user) {
        //     SendOrderResvMail::dispatch($order_info, $user);
        // }

        return redirect()->route('order_index')->with(['message' => '発注が確定しました', 'status' => 'info']);
    }

    public function result_destroy($id)
    {
        $work = Order::findOrFail($id);
        $work->delete();
        return redirect()->route('order_index')->with(['message'=>'削除しました','status'=>'alert']);
    }


}
