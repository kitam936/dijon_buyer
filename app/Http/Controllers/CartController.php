<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Hinban;
use App\Models\Sku;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    public function index()
    {
        $vendors = DB::table('vendors')->get();

        $ex_rate = DB::table('ex_rates')
            ->first();

        $cost_rate = DB::table('cost_rates')
            ->first();

        $ex_rate_value = $ex_rate->ex_rate ?? 0; // nullの場合は0を設定
        $cost_rate_value = $cost_rate->cost_rate ?? 0; // nullの場合は0を設定

        $carts = DB::table('carts')
        ->join('users','users.id','carts.user_id')
        ->join('skus','skus.id','carts.sku_id')
        ->join('hinbans','hinbans.id','skus.hinban_id')
        ->where('carts.user_id',Auth::id())
        ->select('carts.user_id','carts.id','carts.sku_id','skus.hinban_id','skus.col_id','skus.size_id','hinbans.prod_code','hinbans.hinban_name','carts.local_cur_price','carts.pcs')
        ->get();

        $cart_total = DB::table('carts')
        ->join('users','users.id','carts.user_id')
        ->join('skus','skus.id','carts.sku_id')
        ->join('hinbans','hinbans.id','skus.hinban_id')
        ->where('carts.user_id',Auth::id())
        ->groupBy('carts.user_id')
        ->selectRaw("carts.user_id,
            sum(carts.pcs) as total_pcs,
            sum(carts.pcs * carts.local_cur_price) as local_cur_total,
            FLOOR(sum(carts.pcs * carts.local_cur_price) * $ex_rate_value / 100) as local_yen_total,
            FLOOR(sum(carts.pcs * carts.local_cur_price) * $ex_rate_value / 100 * $cost_rate_value / 100) as expected_total
        ")
        ->first();

        $user = DB::table('users')
        ->where('users.id',Auth::id())
        ->select('users.name','users.id')
        ->first();

        // dd($carts,$cart_total,$user);

        return view('order.cart_index', compact('carts','user','cart_total',
            'ex_rate_value', 'cost_rate_value', 'vendors','ex_rate','cost_rate'));
    }

    public function edit()
    {
        $ex_rate = DB::table('ex_rates')
            ->first();

        $cost_rate = DB::table('cost_rates')
            ->first();

        $ex_rate_value = $ex_rate->ex_rate ?? 0; // nullの場合は0を設定
        $cost_rate_value = $cost_rate->cost_rate ?? 0; // nullの場合は0を設定

        $carts = DB::table('carts')
        ->join('users','users.id','carts.user_id')
        ->join('skus','skus.id','carts.sku_id')
        ->join('hinbans','hinbans.id','skus.hinban_id')
        ->where('carts.user_id',Auth::id())
        ->select('carts.user_id','carts.id','carts.sku_id','skus.hinban_id','skus.col_id','skus.size_id','hinbans.hinban_name','hinbans.prod_code','carts.local_cur_price','carts.pcs')
        ->get();

        $cart_total = DB::table('carts')
        ->join('users','users.id','carts.user_id')
        ->join('skus','skus.id','carts.sku_id')
        ->join('hinbans','hinbans.id','skus.hinban_id')
        ->where('carts.user_id',Auth::id())
        ->groupBy('carts.user_id')
        ->selectRaw("carts.user_id,
            sum(carts.pcs) as total_pcs,
            sum(carts.pcs * skus.local_cur_price) as local_cur_total,
            FLOOR(sum(carts.pcs * skus.local_cur_price) * $ex_rate_value / 100) as local_yen_total,
            FLOOR(sum(carts.pcs * skus.local_cur_price) * $ex_rate_value / 100 * $cost_rate_value / 100) as expected_total
        ")
        ->first();

        $user = DB::table('users')
        ->where('users.id',Auth::id())
        ->select('users.name','users.id')
        ->first();



        $vendors = DB::table('vendors')->get();
        // dd($carts,$cart_total);
        return view('order.cart_edit', compact('carts','user','cart_total',
            'vendors','ex_rate','cost_rate'));

    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'sku_id' => 'required|exists:skus,id',
            // 'order_date' => 'required|date',
            'pcs' => 'required|integer|min:0',
            'local_cur_price' => 'required|numeric|min:1',
        ]);

        // $sku = Sku::findOrFail($request->sku_id);

        // $vendor = $request->vendor_id;

        // $cartItem = Cart::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        // $cartItem->update(['pcs' => $request->pcs]);
        // $cartItem->update(['local_cur_price' => $request->local_cur_price]);

        $cart=Cart::where('id', $id)->first();
        $cart->pcs = $request->pcs;
        $cart->local_cur_price = $request->local_cur_price;
        $cart->save();

        // dd($cart);

        return back()->with(['message'=>'カートが修正されました','status'=>'info']);
    }

    public function create(Request $request)
    {
        if($request->type1 == 'a') {

            $logIn_user = DB::table('users')
            ->where('users.id',Auth::id())->first();

            $vendors = DB::table('vendors')->get();

            $ex_rate = DB::table('ex_rates')
            ->first();
            $cost_rate = DB::table('cost_rates')
            ->first();

            $carts = DB::table('carts')
            ->join('users','users.id','carts.user_id')
            ->join('skus','skus.id','carts.sku_id')
            ->join('hinbans','hinbans.id','skus.hinban_id')
            ->where('carts.user_id',Auth::id())
            ->groupBy('carts.sku_id')
            ->selectRaw('carts.sku_id,sum(carts.pcs) as pcs');
            // ->get();

            // dd($dc_stocks,$order_stop);

            $products = DB::table('skus')
            ->join('hinbans','hinbans.id','=','skus.hinban_id')
            ->join('units','units.id','=','hinbans.unit_id')
            ->leftjoinSub($carts, 'carts', 'carts.sku_id', '=', 'skus.id')
            ->where('skus.col_id','<>',99)
            ->where('hinbans.year_code','LIKE','%'.($request->year_code).'%')
            ->where('units.id','LIKE','%'.($request->unit_code).'%')
            ->where('hinbans.face_code','LIKE','%'.($request->code).'%')
            ->where('hinbans.brand_id','LIKE','%'.($request->brand_code).'%')

            ->select(['skus.id as sku_id','skus.col_id','size_id','hinbans.year_code','hinbans.brand_id','hinbans.prod_code','hinbans.unit_id','units.season_id','units.season_name','hinbans.id as hinban_id','hinbans.hinban_name','skus.local_cur_price','hinbans.face_code','carts.pcs','skus.sku_image'])
            ->orderBy('hinbans.year_code','desc')
            ->orderBy('hinbans.brand_id','asc')
            ->orderBy('units.season_id','desc')
            ->orderBy('sku_id','asc')
            ->paginate(50);
            // ->get();
            $years=DB::table('hinbans')
            ->select(['year_code'])
            ->where('year_code','<',50)
            ->groupBy(['year_code'])
            ->orderBy('year_code','desc')
            ->get();
            $faces=DB::table('faces')
            ->orderBy('face_code','asc')
            ->get();
            $seasons=DB::table('units')
            ->select(['season_id','season_name'])
            ->groupBy(['season_id','season_name'])
            ->orderBy('season_id','asc')
            ->get();
            $units=DB::table('units')
            ->where('units.season_id','LIKE','%'.$request->season_code.'%')
            // ->select(['id','season_id'])
            // ->groupBy(['id','season_id'])
            ->orderBy('id','asc')
            ->get();
            $brands=DB::table('brands')
            ->select(['id','brand_name'])
            ->groupBy(['id','brand_name'])
            ->orderBy('id','asc')
            ->get();

            // dd($products);

            return view('order.cart_create',
            compact('products','years','faces',
                    'seasons','units','brands','logIn_user','vendors','ex_rate','cost_rate'));

        } else {

            $logIn_user = DB::table('users')
            ->where('users.id',Auth::id())->first();

            $vendors = DB::table('vendors')->get();

            $ex_rate = DB::table('ex_rates')
            ->first();
            $cost_rate = DB::table('cost_rates')
            ->first();

            $carts = DB::table('carts')
            ->join('users','users.id','carts.user_id')
            ->join('skus','skus.id','carts.sku_id')
            ->join('hinbans','hinbans.id','skus.hinban_id')
            ->where('carts.user_id',Auth::id())
            ->groupBy('carts.sku_id')
            ->selectRaw('carts.sku_id,sum(carts.pcs) as pcs');
            // ->get();

            // dd($dc_stocks,$order_stop);

            $products = DB::table('skus')
            ->join('hinbans','hinbans.id','=','skus.hinban_id')
            ->join('units','units.id','=','hinbans.unit_id')
            ->leftjoinSub($carts, 'carts', 'carts.sku_id', '=', 'skus.id')
            ->where('skus.col_id','<>',99)
            ->where('hinbans.year_code','LIKE','%'.($request->year_code).'%')
            ->where('units.id','LIKE','%'.($request->unit_code).'%')
            ->where('hinbans.face_code','LIKE','%'.($request->face).'%')
            ->where('hinbans.brand_id','LIKE','%'.($request->brand_code).'%')
            ->whereNull('carts.pcs') ## カートに入っていない商品を取得
            ->select(['skus.id as sku_id','skus.col_id','size_id','hinbans.year_code','hinbans.brand_id','hinbans.prod_code','hinbans.unit_id','units.season_id','units.season_name','hinbans.id as hinban_id','hinbans.hinban_name','skus.local_cur_price','hinbans.face_code','carts.pcs','skus.sku_image'])
            ->orderBy('hinbans.year_code','desc')
            ->orderBy('hinbans.brand_id','asc')
            ->orderBy('units.season_id','desc')
            ->orderBy('sku_id','asc')
            ->paginate(50);
            // ->get();
            $years=DB::table('hinbans')
            ->select(['year_code'])
            ->where('year_code','<',50)
            ->groupBy(['year_code'])
            ->orderBy('year_code','desc')
            ->get();
            $faces=DB::table('faces')
            ->orderBy('face_code','asc')
            ->get();
            $seasons=DB::table('units')
            ->select(['season_id','season_name'])
            ->groupBy(['season_id','season_name'])
            ->orderBy('season_id','asc')
            ->get();
            $units=DB::table('units')
            ->where('units.season_id','LIKE','%'.$request->season_code.'%')
            // ->select(['id','season_id'])
            // ->groupBy(['id','season_id'])
            ->orderBy('id','asc')
            ->get();
            $brands=DB::table('brands')
            ->select(['id','brand_name'])
            ->groupBy(['id','brand_name'])
            ->orderBy('id','asc')
            ->get();

            // dd($products);

            return view('order.cart_create',
            compact('products','years','faces',
                    'seasons','units','brands','logIn_user','vendors','ex_rate','cost_rate'));
        }

    }

    public function add(Request $request)
    {
        $request->validate([
            'sku_id' => 'required|exists:skus,id',
            // 'order_date' => 'required|date',
            'pcs' => 'required|integer|min:1',
            // 'local_cur_price' => 'required|numeric|min:1',
        ]);

        $sku = Sku::findOrFail($request->sku_id);

        // dd($request->sku_id,$sku);

        Cart::create([
            'user_id' => Auth::id(),
            // 'vendor_id' => $request->vendor_id,
            'order_date' => now(),
            'sku_id' => $request->sku_id,
            'pcs' => $request->pcs,
            'local_cur_price' => $sku->local_cur_price,
        ]);

        // return to_route('cart_index')->with(['message'=>'Cartに追加されました','status'=>'info']);
        return back()->with(['message'=>'カートに追加されました','status'=>'info']);
    }

    public function destroy($id)
{
    $cartItem = Cart::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
    $cartItem->delete();

    return back()->with(['message'=>'カートから削除されました','status'=>'alert']);
}
}
