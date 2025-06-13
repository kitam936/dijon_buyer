<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Shop;
use App\Models\Sale;
use App\Models\Stock;
use App\Models\Company;
use App\Models\Area;
use App\Models\Hinban;
use App\Models\SalesData;

class ProductController extends Controller
{


    public function index(Request $request)
    {
        if($request->type == "h"){
            $products = Hinban::with('unit')->paginate(50);

            $products_sele = DB::table('hinbans')
            ->join('units','units.id','=','hinbans.unit_id')
            ->where('hinbans.year_code','<>',99)
            ->where('hinbans.vendor_id','<>',8200)
            ->where('hinbans.year_code','LIKE','%'.($request->year_code).'%')
            ->where('units.season_id','LIKE','%'.($request->season_code).'%')
            ->where('units.unit_code','LIKE','%'.($request->unit_code).'%')
            ->where('hinbans.face','LIKE','%'.($request->face).'%')
            ->where('hinbans.brand_id','LIKE','%'.($request->brand_code).'%')
            ->where('hinbans.id','LIKE','%'.($request->hinban_code).'%')
            ->select(['hinbans.year_code','hinbans.brand_id','hinbans.unit_id','units.season_id','units.season_name','hinbans.id as hinban_id','hinbans.hinban_name','hinbans.m_price','hinbans.price','hinbans.face'])
            ->orderBy('hinbans.year_code','desc')
            ->orderBy('hinbans.brand_id','asc')
            ->orderBy('units.season_id','desc')
            ->orderBy('hinban_id','asc')
            ->paginate(100);
            // ->get();
            $years=DB::table('hinbans')
            ->select(['year_code'])
            ->groupBy(['year_code'])
            ->orderBy('year_code','desc')
            ->get();
            $faces=DB::table('hinbans')
            ->whereNotNull('face')
            ->select(['face'])
            ->groupBy(['face'])
            ->orderBy('face','asc')
            ->get();
            $seasons=DB::table('units')
            ->select(['season_id','season_name'])
            ->groupBy(['season_id','season_name'])
            ->orderBy('season_id','asc')
            ->get();
            $units=DB::table('units')
            ->where('units.season_id','LIKE','%'.$request->season_code.'%')
            ->select(['id','unit_code'])
            ->groupBy(['id','unit_code'])
            ->orderBy('id','asc')
            ->get();
            $brands=DB::table('brands')
            ->select(['id'])
            ->groupBy(['id'])
            ->orderBy('id','asc')
            ->get();
            return view('product.index',compact('products','seasons','units','years','products_sele','brands','faces'));
            }else{
            $products = Hinban::with('unit')->paginate(50);

            $products_sele = DB::table('hinbans')
            ->join('units','units.id','=','hinbans.unit_id')
            ->where('hinbans.year_code','<>',99)
            // ->where('hinbans.vendor_id','<>',8200)
            ->where('hinbans.year_code','LIKE','%'.($request->year_code).'%')
            ->where('units.season_id','LIKE','%'.($request->season_code).'%')
            ->where('units.unit_code','LIKE','%'.($request->unit_code).'%')
            ->where('hinbans.face','LIKE','%'.($request->face).'%')
            ->where('hinbans.brand_id','LIKE','%'.($request->brand_code).'%')
            ->where('hinbans.id','LIKE','%'.($request->hinban_code).'%')
            ->select(['hinbans.year_code','hinbans.brand_id','hinbans.unit_id','units.season_id','units.season_name','hinbans.id as hinban_id','hinbans.hinban_name','hinbans.m_price','hinbans.price','hinbans.face'])
            ->orderBy('hinbans.year_code','desc')
            ->orderBy('units.season_id','desc')
            ->orderBy('hinbans.brand_id','asc')
            ->orderBy('hinban_id','asc')
            ->paginate(100);
            // ->get();
            $years=DB::table('hinbans')
            ->select(['year_code'])
            ->groupBy(['year_code'])
            ->orderBy('year_code','desc')
            ->get();
            $faces=DB::table('hinbans')
            ->whereNotNull('face')
            ->select(['face'])
            ->groupBy(['face'])
            ->orderBy('face','asc')
            ->get();
            $seasons=DB::table('units')
            ->select(['season_id','season_name'])
            ->groupBy(['season_id','season_name'])
            ->orderBy('season_id','asc')
            ->get();
            $units=DB::table('units')
            ->where('units.season_id','LIKE','%'.$request->season_code.'%')
            ->select(['id','unit_code'])
            ->groupBy(['id','unit_code'])
            ->orderBy('id','asc')
            ->get();
            $brands=DB::table('brands')
            ->select(['id'])
            ->groupBy(['id'])
            ->orderBy('id','asc')
            ->get();
            return view('product.index',compact('products','seasons','units','years','products_sele','brands','faces'));
            }

        // return view('product.index',compact('products','seasons','units','years','products_sele','brands','faces'));
    }

    public function show0($id)
    {
        $product = DB::table('hinbans')
        ->leftJoin('images','images.hinban_id','hinbans.id')
        ->join('units','units.id','=','hinbans.unit_id')
        ->where('hinbans.id',$id)
        ->select(['hinbans.year_code','hinbans.brand_id','hinbans.unit_id','units.season_name','hinbans.id','hinbans.hinban_name','hinbans.hinban_info','hinbans.shohin_gun','hinbans.m_price','hinbans.price','images.filename'])
        ->first();

        $sku_stocks = DB::table('stocks')
        ->join('skus','skus.id','=','stocks.sku_id')
        ->where('skus.hinban_id',$id)
        ->select('sku_id')
        ->selectRaw('SUM(pcs) as pcs')
        ->groupBy('sku_id');
        // ->get();

        $skus = DB::table('skus')
        ->leftJoinSub($sku_stocks,'sku_stocks',function($join){
            $join->on('skus.id','=','sku_stocks.sku_id');})
        ->where('skus.hinban_id',$id)
        ->where('skus.col_id','<','99')
        ->select(['skus.id','skus.hinban_id','skus.col_id','skus.size_id','sku_stocks.pcs'])
        ->get();

        // dd($id,$product,$skus);

        return view('product.show0',compact('product','skus'));
    }

    public function show(Request $request, $id)
    {

        if($request->type == 'sku'){
            $product = DB::table('hinbans')
            ->leftJoin('images','images.hinban_id','hinbans.id')
            ->join('units','units.id','=','hinbans.unit_id')
            // ->where('hinbans.id',$id)
            ->where('hinbans.id',$request->hin_id2)
            ->select(['hinbans.year_code','hinbans.brand_id','hinbans.unit_id','units.season_name','hinbans.id','hinbans.hinban_name','hinbans.hinban_info','hinbans.shohin_gun','hinbans.m_price','hinbans.price','images.filename'])
            ->first();

            $sku_stocks = DB::table('stocks')
            ->join('skus','skus.id','=','stocks.sku_id')
            // ->where('skus.hinban_id',$id)
            ->where('skus.hinban_id',$request->hin_id2)
            ->select('sku_id')
            ->selectRaw('SUM(pcs) as pcs')
            ->groupBy('sku_id');
            // ->get();

            $skus = DB::table('skus')
            ->leftJoinSub($sku_stocks,'sku_stocks',function($join){
                $join->on('skus.id','=','sku_stocks.sku_id');})
            // ->where('skus.hinban_id',$id)
            ->where('skus.hinban_id',$request->hin_id2)
            ->where('skus.col_id','<','99')
            ->select(['skus.id','skus.hinban_id','skus.col_id','skus.size_id','sku_stocks.pcs'])
            ->get();

            // $datas = DB::table('hinbans')
            // ->join('units','units.id','=','hinbans.unit_id')
            // ->where('hinbans.id',$id)
            // ->select(['hinbans.year_code','hinbans.brand_id','hinbans.unit_id','units.season_name','hinbans.id as hinban_id','hinbans.hinban_name','hinbans.hinban_info','hinbans.shohin_gun','hinbans.m_price','hinbans.price'])
            // ->first();

            // dd($skus);

            return view('product.show',compact('product','skus'));
        }

        if($request->type == '' ){
            $product = DB::table('hinbans')
            ->leftJoin('images','images.hinban_id','hinbans.id')
            ->join('units','units.id','=','hinbans.unit_id')
            ->where('hinbans.id',$id)
            ->select(['hinbans.year_code','hinbans.brand_id','hinbans.unit_id','units.season_name','hinbans.id','hinbans.hinban_name','hinbans.hinban_info','hinbans.shohin_gun','hinbans.m_price','hinbans.price','images.filename'])
            ->first();

            $sku_stocks = DB::table('stocks')
            ->join('skus','skus.id','=','stocks.sku_id')
            ->where('skus.hinban_id',$id)
            ->select('sku_id')
            ->selectRaw('SUM(pcs) as pcs')
            ->groupBy('sku_id');
            // ->get();

            $skus = DB::table('skus')
            ->leftJoinSub($sku_stocks,'sku_stocks',function($join){
                $join->on('skus.id','=','sku_stocks.sku_id');})
            ->where('skus.hinban_id',$id)
            ->where('skus.col_id','<>','99')
            ->select(['skus.id','skus.hinban_id','skus.col_id','skus.size_id','sku_stocks.pcs'])
            ->get();



            // dd($skus);

            return view('product.show',compact('product','skus'));
        }


        if($request->type == 'sh_total'){
            $product = DB::table('hinbans')
            ->leftJoin('images','images.hinban_id','hinbans.id')
            ->join('units','units.id','=','hinbans.unit_id')
            ->where('hinbans.id',$id)
            ->select(['hinbans.year_code','hinbans.brand_id','hinbans.unit_id','units.season_name','hinbans.id','hinbans.hinban_name','hinbans.hinban_info','hinbans.shohin_gun','hinbans.m_price','hinbans.price','images.filename'])
            ->first();

            $sku_stocks = DB::table('stocks')
            ->join('skus','skus.id','=','stocks.sku_id')
            ->where('skus.hinban_id',$id)
            ->select('sku_id')
            ->selectRaw('SUM(pcs) as pcs')
            ->groupBy('sku_id');
            // ->get();

            $skus = DB::table('skus')
            ->leftJoinSub($sku_stocks,'sku_stocks',function($join){
                $join->on('skus.id','=','sku_stocks.sku_id');})
            ->where('skus.hinban_id',$id)
            ->where('skus.col_id','<','99')
            ->select(['skus.id','skus.hinban_id','skus.col_id','skus.size_id','sku_stocks.pcs'])
            ->get();
            $datas = DB::table('sales')
            ->join('skus','skus.id','=','sales.sku_id')
            ->join('shops','shops.id','=','sales.shop_id')
            ->where('skus.hinban_id',$id)
            ->select('sales.shop_id as shop_id','shops.shop_name as shop_name')
            ->selectRaw('SUM(pcs) as pcs,SUM(kingaku) as total')
            ->groupBy('shop_id','shop_name')
            ->orderBy('pcs','desc')
            ->get();



            return view('product.show',compact('product','skus','datas'));
        }

        if($request->type == 'co_total'){
            $product = DB::table('hinbans')
            ->leftJoin('images','images.hinban_id','hinbans.id')
            ->join('units','units.id','=','hinbans.unit_id')
            ->where('hinbans.id',$id)
            ->select(['hinbans.year_code','hinbans.brand_id','hinbans.unit_id','units.season_name','hinbans.id','hinbans.hinban_name','hinbans.hinban_info','hinbans.shohin_gun','hinbans.m_price','hinbans.price','images.filename'])
            ->first();

            $sku_stocks = DB::table('stocks')
            ->join('skus','skus.id','=','stocks.sku_id')
            ->where('skus.hinban_id',$id)
            ->select('sku_id')
            ->selectRaw('SUM(pcs) as pcs')
            ->groupBy('sku_id');
            // ->get();

            $skus = DB::table('skus')
            ->leftJoinSub($sku_stocks,'sku_stocks',function($join){
                $join->on('skus.id','=','sku_stocks.sku_id');})
            ->where('skus.hinban_id',$id)
            ->where('skus.col_id','<','99')
            ->select(['skus.id','skus.hinban_id','skus.col_id','skus.size_id','sku_stocks.pcs'])
            ->get();
            $datas = DB::table('sales')
            ->join('skus','skus.id','=','sales.sku_id')
            ->join('shops','shops.id','=','sales.shop_id')
            ->join('companies','companies.id','=','shops.company_id')
            ->where('skus.hinban_id',$id)
            ->select('shops.company_id as company_id','companies.co_name as co_name')
            ->selectRaw('SUM(pcs) as pcs,SUM(kingaku) as total')
            ->groupBy('company_id','co_name')
            ->orderBy('pcs','desc')
            ->get();

            // dd($datas);

            return view('product.show',compact('product','skus','datas'));
        }

        if($request->type == 'h_trans'){
            $product = DB::table('hinbans')
            ->leftJoin('images','images.hinban_id','hinbans.id')
            ->join('units','units.id','=','hinbans.unit_id')
            ->where('hinbans.id',$id)
            ->select(['hinbans.year_code','hinbans.brand_id','hinbans.unit_id','units.season_name','hinbans.id','hinbans.hinban_name','hinbans.hinban_info','hinbans.shohin_gun','hinbans.m_price','hinbans.price','images.filename'])
            ->first();

            $sku_stocks = DB::table('stocks')
            ->join('skus','skus.id','=','stocks.sku_id')
            ->where('skus.hinban_id',$id)
            ->select('sku_id')
            ->selectRaw('SUM(pcs) as pcs')
            ->groupBy('sku_id');
            // ->get();

            $skus = DB::table('skus')
            ->leftJoinSub($sku_stocks,'sku_stocks',function($join){
                $join->on('skus.id','=','sku_stocks.sku_id');})
            ->where('skus.hinban_id',$id)
            ->where('skus.col_id','<','99')
            ->select(['skus.id','skus.hinban_id','skus.col_id','skus.size_id','sku_stocks.pcs'])
            ->get();
            $datas = DB::table('sales')
            ->join('skus','skus.id','=','sales.sku_id')
            ->where('skus.hinban_id',$id)
            ->select('YW as date')
            ->selectRaw('SUM(pcs) as pcs,SUM(kingaku) as total')
            ->groupBy('date')
            ->orderBy('date','desc')
            ->get();

            // dd($datas);

            return view('product.show',compact('product','skus','datas'));
        }
    }


    public function sku_zaiko($id)
    {
        // $hinbans = Hinban::findOrFail($id)->first();
        $skus = DB::table('stocks')
        ->where('sku_id','=',$id)
        ->select(['sku_id'])
        // ->groupBy(['tocks.hinban_id'])
        ->orderBy('sku_id','asc')
        ->first();


        $sku_shop_stocks = DB::table('stocks')
        ->join('shops','shops.id','=','stocks.shop_id')
        ->join('companies','companies.id','=','shops.company_id')
        ->join('areas','areas.id','=','shops.area_id')
        ->where('stocks.sku_id','=',$id)
        ->select(['stocks.sku_id','shops.company_id','companies.co_name','stocks.shop_id','shops.shop_name','stocks.pcs','areas.id','areas.area_name'])
        ->orderBy('stocks.pcs','desc')
        ->orderBy('areas.id','asc')
        ->orderBy('companies.id','asc')
        ->orderBy('stocks.shop_id','asc')
        ->paginate(15);

        // dd($h_shop_stocks,$hinbans);
        if(is_null($skus)){
            return to_route('product_index')
            ->with(['message'=>'在庫データがありません','status'=>'alert']);
            // dd($h_shop_stocks);
        }else{
            return view('product.sku_shop_zaiko',compact('sku_shop_stocks','skus'));
        }


    }
}
