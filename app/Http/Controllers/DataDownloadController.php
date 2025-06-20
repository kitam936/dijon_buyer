<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\StreamedResponse;
use App\Jobs\SendOrderResponseMail;
use App\Models\User;
use App\Models\Order;
use App\Models\OrderItem;

class DataDownloadController extends Controller
{



    public function manual_download()
    {
        $user_role = User::findOrFail(Auth::id())->role_id;
        // dd($user_role);
        if($user_role > 3){
            $dl_filename1='マニュアル.pdf';
            $file_path1 = 'public/manual/'.$dl_filename1;
            $mimeType1 = Storage::mimeType($file_path1);
            $headers1 = [['Content-Type' =>$mimeType1]];
            // dd($dl_filename1,$file_path1,$user_role);
            return Storage::download($file_path1,  $dl_filename1, $headers1);
        }

        if($user_role <= 2){
            $dl_filename2='管理者マニュアル.pdf';
            $file_path2 = 'public/manual/'.$dl_filename2;
            $mimeType2 = Storage::mimeType($file_path2);
            $headers2 = [['Content-Type' =>$mimeType2]];
            // dd($dl_filename2,$file_path2,$mimeType2,$user_role);
            return Storage::download($file_path2,  $dl_filename2, $headers2);
        }

        // return to_route('doc_index',['event'=>$event_id])->with(['message'=>'ファイルをダウンロードしました','status'=>'info']);
    }


    // 成功したコード
    public function orderCSV_download(Request $request)
    {

        // dd($dl_order);
        $dl_order = DB::table('orders')
        ->join('order_items','order_items.order_id','=','orders.id')
        ->join('skus','skus.id','=','order_items.sku_id')
        ->where('orders.order_status',1)
        ->where('orders.id',$request->id2)
        ->groupBy('skus.hinban_id')
        ->select('skus.hinban_id');
        // ->get();

        // dd($dl_order);

        $order_sku = DB::table('orders')
        ->join('order_items','order_items.order_id','=','orders.id')
        ->where('orders.order_status',1)
        ->where('orders.id',$request->id2)
        ->groupBy('order_items.sku_id','orders.vendor_id','order_items.expected_price')
        ->selectRaw('order_items.sku_id,orders.vendor_id,order_items.expected_price,sum(order_items.order_pcs) as pcs');
        // ->get();

        // dd($order_sku);

        $orders = DB::table('skus')
        ->join('hinbans','hinbans.id','=','skus.hinban_id')
        ->joinsub($dl_order, 'dl_order', function ($join) {
            $join->on('dl_order.hinban_id', '=', 'skus.hinban_id');
        })
        ->leftjoinSub($order_sku, 'orders', 'orders.sku_id', '=', 'skus.id')
        ->join('brands','brands.id','=','hinbans.brand_id')
        ->join('units','units.id','=','hinbans.unit_id')
        ->join('faces','faces.face_code','=','hinbans.face_code')
        // ->where('orders.id',$request->id2)
        ->selectRaw('
            skus.id,
            orders.vendor_id as factory,
            orders.vendor_id  as vendor,
            "" as kijihinban,
            hinbans.prod_code as kijimei,
            skus.hinban_id,hinbans.hinban_name,
            "" as tenkaibi,
            units.season_name,
            hinbans.year_code,
            hinbans.shohin_gun,
            hinbans.kizoku_g,
            hinbans.unit_id,
            hinbans.face_code,
            "" as face2,
            faces.face_item,
            "" as nouki,
            orders.pcs ,
            skus.col_id,
            skus.size_id,
            orders.expected_price as cost ,
            "" as jodai,"" as shiharai,
            brands.brand_name,
            hinbans.mix_rate,
            skus.length,
            skus.width,
            hinbans.seireki_unit,
            hinbans.kyotu_hinban,
            "" as hachuubi,
            "9" as desiginer ')
            ->orderBy('skus.id')
            ->distinct()
            // ->groupBy('my_karts.maker_id')
            // ->orderBy('order_items.sku_id')
            ->get();

        // dd($orders);
        $csvHeader = [
            'sku_id',
            'factory' ,'vendor','kiji-hinban','kiji-name',
            'hinban','hinnmei','tenkaibi','season',
            'year','shohin-gun','kizoku','ACT','FACE1','FACE2',
            'item','nouki','pcs','col','SZ',
            'cost','jodai','shiharai','brand','mix_rate',
            'length','width','seireki_-unit','kyotu-hinban','hachubi','designer',
            // 'sku_id'
        ];

        $csvData = $orders->toArray();

        // dd($request,$orders,$csvHeader,$csvData);

        $response = new StreamedResponse(function () use ($csvHeader, $csvData) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, $csvHeader);

            foreach ($csvData as $row) {
                $row = (array)$row; // 必要に応じてオブジェクトを配列に変換
                mb_convert_variables('sjis-win', 'utf-8', $row);
                fputcsv($handle, $row);
            }

            fclose($handle);
        });

        $timestamp = date('ymd_His');

        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename="発注Data_' . $timestamp . '.csv"');

        // Status変更

        $orders=Order::where('order_status',1)
        ->get();
        // dd($orders);
        foreach ($orders as $order) {
            $order->order_status = 5;
            $order->save();
        }


        // Status変更Mail

        // $users = Order::Where('orders.id',$request->id2)
        // ->join('users','users.id','orders.user_id')
        // ->where('mailService','=',1)
        // ->select('orders.user_id','users.name','users.email')
        // ->get()
        // ->toArray();

        // $order_info = Order::Where('orders.id',$request->id2)
        // ->join('shops','shops.id','orders.shop_id')
        // ->join('users','users.id','orders.user_id')
        // ->select('orders.id as order_id','users.name','users.email','orders.shop_id','shops.shop_name')
        // ->first()
        // ->toArray();

        // foreach($users as $user){
        //     SendOrderResponseMail::dispatch($order_info,$user);
        // }

        return $response;
    }

    public function orderCSV_download_all()
    {
        $dl_order = DB::table('orders')
        ->join('order_items','order_items.order_id','=','orders.id')
        ->join('skus','skus.id','=','order_items.sku_id')
        ->where('orders.order_status',1)
        ->groupBy('skus.hinban_id')
        ->select('skus.hinban_id');
        // ->get();

        // dd($dl_order);

        $order_sku = DB::table('orders')
        ->join('order_items','order_items.order_id','=','orders.id')
        ->where('orders.order_status',1)
        ->groupBy('order_items.sku_id','orders.vendor_id','order_items.expected_price')
        ->selectRaw('order_items.sku_id,orders.vendor_id,order_items.expected_price,sum(order_items.order_pcs) as pcs');
        // ->get();

        // dd($order_sku);

        $orders = DB::table('skus')
        ->join('hinbans','hinbans.id','=','skus.hinban_id')
        ->joinsub($dl_order, 'dl_order', function ($join) {
            $join->on('dl_order.hinban_id', '=', 'skus.hinban_id');
        })
        ->leftjoinSub($order_sku, 'orders', 'orders.sku_id', '=', 'skus.id')
        ->join('brands','brands.id','=','hinbans.brand_id')
        ->join('units','units.id','=','hinbans.unit_id')
        ->join('faces','faces.face_code','=','hinbans.face_code')
        // ->where('orders.id',$request->id2)
        ->selectRaw('
            skus.id,
            orders.vendor_id as factory,
            orders.vendor_id  as vendor,
            "" as kijihinban,
            hinbans.prod_code as kijimei,
            skus.hinban_id,hinbans.hinban_name,
            "" as tenkaibi,
            units.season_name,
            hinbans.year_code,
            hinbans.shohin_gun,
            hinbans.kizoku_g,
            hinbans.unit_id,
            hinbans.face_code,
            "" as face2,
            faces.face_item,
            "" as nouki,
            orders.pcs ,
            skus.col_id,
            skus.size_id,
            orders.expected_price as cost ,
            "" as jodai,"" as shiharai,
            brands.brand_name,
            hinbans.mix_rate,
            skus.length,
            skus.width,
            hinbans.seireki_unit,
            hinbans.kyotu_hinban,
            "" as hachuubi,
            "9" as desiginer ')
            ->orderBy('skus.id')
            ->distinct()
            // ->groupBy('my_karts.maker_id')
            // ->orderBy('order_items.sku_id')
            ->get();

        // dd($orders);
        $csvHeader = [
            'sku_id',
            'factory' ,'vendor','kiji-hinban','kiji-name',
            'hinban','hinnmei','tenkaibi','season',
            'year','shohin-gun','kizoku','ACT','FACE1','FACE2',
            'item','nouki','pcs','col','SZ',
            'cost','jodai','shiharai','brand','mix_rate',
            'length','width','seireki_-unit','kyotu-hinban','hachubi','designer',
            // 'sku_id'
        ];

        $csvData = $orders->toArray();

        // dd($request,$orders,$csvHeader,$csvData);

        $response = new StreamedResponse(function () use ($csvHeader, $csvData) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, $csvHeader);

            foreach ($csvData as $row) {
                $row = (array)$row; // 必要に応じてオブジェクトを配列に変換
                mb_convert_variables('sjis-win', 'utf-8', $row);
                fputcsv($handle, $row);
            }

            fclose($handle);
        });

        $timestamp = date('ymd_His');

        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename="発注Data_' . $timestamp . '.csv"');

        // Status変更

        $orders=Order::where('order_status',1)
        ->get();
        // dd($orders);
        foreach ($orders as $order) {
            $order->order_status = 5;
            $order->save();
        }


        // Status変更Mail

        // $users = Order::Where('orders.id',$request->id2)
        // ->join('users','users.id','orders.user_id')
        // ->where('mailService','=',1)
        // ->select('orders.user_id','users.name','users.email')
        // ->get()
        // ->toArray();

        // $order_info = Order::Where('orders.id',$request->id2)
        // ->join('shops','shops.id','orders.shop_id')
        // ->join('users','users.id','orders.user_id')
        // ->select('orders.id as order_id','users.name','users.email','orders.shop_id','shops.shop_name')
        // ->first()
        // ->toArray();

        // foreach($users as $user){
        //     SendOrderResponseMail::dispatch($order_info,$user);
        // }

        return $response;
    }


    public function orderCSV_download_ws()
    {
        $orders = DB::table('orders')
        ->join('order_items','order_items.order_id','=','orders.id')
        ->join('shops','shops.id','=','orders.shop_id')
        ->join('skus','skus.id','=','order_items.sku_id')
        ->join('hinbans','hinbans.id','=','skus.hinban_id')
        ->where('orders.status',1)
        ->where('shops.company_id',400)
        ->selectRaw('orders.shop_id ,skus.hinban_id,skus.col_id,skus.size_id,order_items.expected_price,FLOOR(order_items.expected_price * shops.rate /1000) as gedai,order_items.pcs')
        ->distinct()
        // ->groupBy('my_karts.maker_id')
        // ->orderBy('order_items.sku_id')
        ->get();

        // dd($orders);
        $csvHeader = [
            'shop_id' ,'hinban_id','col_id','size_id','m_price','gedai','pcs'];

        $csvData = $orders->toArray();

        // dd($request,$orders,$csvHeader,$csvData);

        $response = new StreamedResponse(function () use ($csvHeader, $csvData) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, $csvHeader);

            foreach ($csvData as $row) {
                $row = (array)$row; // 必要に応じてオブジェクトを配列に変換
                mb_convert_variables('sjis-win', 'utf-8', $row);
                fputcsv($handle, $row);
            }

            fclose($handle);
        });

        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename="orders.csv"');

        // Status変更
        $orders=Order::where('status',1)
        ->where('shop_id','>',4000)
        ->where('shop_id','<',5000)->get();
        // dd($orders);
        foreach ($orders as $order) {
            $order->status = 3;
            $order->save();
        }

        return $response;
    }


    // エラーがでたコード



}
