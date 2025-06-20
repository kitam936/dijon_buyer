<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;
use App\Models\MoveDetail;
use App\Models\MoveHeader;
use App\Models\MoveWork;
use App\Models\Hinban;
use App\Models\User;
use App\Models\Shop;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Jobs\SendMoveMail;

class MoveController extends Controller
{
    public function scan() {

        $works = DB::table('move_works')
        ->leftjoin('hinbans','hinbans.id','move_works.hinban_id')
        ->leftjoin('skus','skus.id','move_works.sku_id')
        ->where('move_works.user_id', Auth::user()->id)
        ->selectRaw('move_works.id,move_works.raw_cd,hinbans.id as hin_ck,skus.id as sku_ck,move_works.hinban_id,hinbans.price,move_works.price as move_price,move_works.pcs,move_works.created_at')
        ->orderBy('created_at', 'desc') // 新しい順
        ->limit(5)                      // 5件に絞る
        ->get();

        $h_exist = DB::table('move_works')
        ->leftjoin('hinbans','hinbans.id','move_works.hinban_id')
        ->leftjoin('skus','skus.id','move_works.sku_id')
        ->where('move_works.user_id', Auth::user()->id)
        ->limit(5)   // 5件に絞る
        ->whereNull('hinbans.id')
        ->exists();

        $s_exist = DB::table('move_works')
        ->leftjoin('hinbans','hinbans.id','move_works.hinban_id')
        ->leftjoin('skus','skus.id','move_works.sku_id')
        ->where('move_works.user_id', Auth::user()->id)
        ->limit(5)   // 5件に絞る
        ->whereNull('skus.id')
        ->exists();

        $works_total = MoveWork::where('user_id', Auth::user()->id)
        ->groupBy('move_works.user_id')
        ->selectRaw('move_works.user_id,sum(move_works.pcs) as pcs')
        ->first();
        // dd($h_exist,$s_exist);
        return view('move.scan',compact('works','works_total','s_exist','h_exist'));
    }

    public function store(Request $request)
    {
        $hinbanId = substr($request->raw_cd, 0, 6);

        // hinbansテーブルから対応するレコードを取得
        $hinban = Hinban::where('id', $hinbanId)->first();

        // m_priceを取得（該当がない場合は0やnullなどを適切に処理）
        $price = $hinban ? $hinban->m_price : 0;

        // dd($hinbanId,$hinban,$price);

        MoveWork::create([
            'user_id' => Auth::user()->id ,
            'from_shop_id' => Auth::user()->shop_id ,
            'raw_cd' => $request->raw_cd,
            'hinban_id' => substr($request->raw_cd, 0, 6),
            'sku_id' => substr($request->raw_cd, 0, 8).substr($request->raw_cd, 9, 1),
            'price' => $price,
            'pcs' => 1,
        ]);

        return redirect()->route('move_scan')->with(['message'=>'バーコードを読み取りました','status'=>'info']);
    }

    public function manual(Request $request)
    {
        $hinbanId = substr($request->raw_cd, 0, 6);

        // hinbansテーブルから対応するレコードを取得
        $hinban = Hinban::where('id', $hinbanId)->first();

        // m_priceを取得（該当がない場合は0やnullなどを適切に処理）
        $price = $hinban ? $hinban->m_price : 0;

        // dd($hinbanId,$hinban,$price);
        MoveWork::create([
            'user_id' => Auth::user()->id,
            'from_shop_id' => Auth::user()->shop_id ,
            'raw_cd' => $request->raw_cd,
            'sku_id' => substr($request->raw_cd, 0, 8).substr($request->raw_cd, 9, 1),
            'hinban_id' => substr($request->raw_cd, 0, 6),
            'price' => $price,
            'pcs' => 1,
        ]);
        return redirect()->back()->with(['message'=>'手入力で追加しました','status'=>'info']);
    }

    public function confirm() {
        $items = MoveWork::all();
        return view('move.confirm', compact('items'));
    }

    public function complete(Request $request)
    {
        $headerId = null;

        // メールテスト

        // $users = User::Where('mailService','=',1)
        // ->join('shops','shops.id','users.shop_id')
        // ->where('shop_id','=',$request->to_shop_id)
        // ->get();

        // $move_info = Shop::Where('id',Auth::User()->shop_id)
        // ->select('shops.id as shop_id','shops.shop_name')
        // ->first();

        // dd($users,$move_info);

        DB::transaction(function () use ($request, &$headerId) {
            $user_id = Auth::user()->id;
            $shop_id = Auth::user()->shop_id;
            $works = MoveWork::where('from_shop_id', $shop_id)->get();
            if ($works->isEmpty()) {
                throw new \Exception("商品移動データがありません");
            }

            $header = MoveHeader::create([
                'from_shop_id' => $shop_id,
                'to_shop_id' => $request->to_shop_id,
                'user_id' => $user_id,
                'move_date' => now(),
                'memo' => $request->memo,
            ]);

            // dd($works);

            foreach ($works as $work) {
                MoveDetail::create([
                    'move_header_id' => $header->id,
                    'user_id' => $user_id,
                    'sku_id' => $work->sku_id,
                    'price' => $work->price,
                    'pcs' => $work->pcs,
                ]);
            }

            MoveWork::where('user_id', $user_id)->delete();

            $headerId = $header->id;
        });

        // ここでメール送信

        $users = User::Where('mailService','=',1)
        ->join('shops','shops.id','users.shop_id')
        ->where('shop_id','=',$request->to_shop_id)
        ->get()
        ->toArray();

        $move_info = Shop::Where('id',Auth::User()->shop_id)
        ->select('shops.id as shop_id','shops.shop_name')
        ->first()
        ->toArray();

        foreach($users as $user){
            SendMoveMail::dispatch($user,$move_info);
        }

            return to_route('move_result_index');
        }



    public function download($id)
    {
        $inventorys = DB::table('move_headers')
        ->join('move_details','move_details.move_header_id','move_headers.id')
        ->join('users','users.id','move_details.user_id')
        ->join('shops','shops.id','move_headers.from_shop_id')
        ->join('shops as to_shops','to_shops.id','move_headers.to_shop_id')
        ->leftjoin('skus','skus.id','move_details.sku_id')
        ->where('move_headers.id',$id)
        ->groupBy('move_headers.id','move_headers.move_date','move_headers.from_shop_id','shops.shop_name','move_headers.to_shop_id','to_shops.shop_name','users.name','move_details.sku_id','skus.hinban_id','skus.col_id','skus.size_id','move_details.price','move_headers.memo')
        ->selectRaw('move_headers.id,move_headers.move_date,move_headers.from_shop_id,shops.shop_name,move_headers.to_shop_id,to_shops.shop_name as to_shop_name,users.name,move_details.sku_id,skus.hinban_id,skus.col_id,skus.size_id,move_details.price,sum(move_details.pcs) as pcs,move_headers.memo')
        ->orderBy('move_headers.id','asc')
        ->distinct()
        // ->groupBy('my_karts.maker_id')
        // ->orderBy('order_items.sku_id')
        ->get();

        // dd($request->order,$orders[0]);
        $csvHeader = [
            'id','date','from_shop_id','from_shop_name','to_shop_id','to_shop_name','staff_name','sku_id','hinban_id','col_id','size_id','price','pcs','memo'];

        $csvData = $inventorys->toArray();

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
        $response->headers->set('Content-Disposition', 'attachment; filename="商品移動Data.csv"');

         // Status変更
        $header=MoveHeader::findOrFail($id);
        $header->status_id = 5;
        $header->save();

        return $response;

    }

    public function download_all()
    {
        $inventorys = DB::table('move_headers')
        ->join('move_details','move_details.move_header_id','move_headers.id')
        ->join('users','users.id','move_details.user_id')
        ->join('shops','shops.id','move_headers.from_shop_id')
        ->join('shops as to_shops','to_shops.id','move_headers.to_shop_id')
        ->leftjoin('skus','skus.id','move_details.sku_id')
        ->where('move_headers.status_id',1)
        ->groupBy('move_headers.id','move_headers.move_date','move_headers.from_shop_id','shops.shop_name','move_headers.to_shop_id','to_shops.shop_name','users.name','move_details.sku_id','skus.hinban_id','skus.col_id','skus.size_id','move_details.price','move_headers.memo')
        ->selectRaw('move_headers.id,move_headers.move_date,move_headers.from_shop_id,shops.shop_name,move_headers.to_shop_id,to_shops.shop_name as to_shop_name,users.name,move_details.sku_id,skus.hinban_id,skus.col_id,skus.size_id,move_details.price,sum(move_details.pcs) as pcs,move_headers.memo')
        ->orderBy('move_headers.id','asc')
        ->distinct()
        // ->groupBy('my_karts.maker_id')
        // ->orderBy('order_items.sku_id')
        ->get();

        // dd($inventorys);

        $csvHeader = [
            'id','date','from_shop_id','from_shop_name','to_shop_id','to_shop_name','staff_name','sku_id','hinban_id','col_id','size_id','price','pcs','memo'];

        $csvData = $inventorys->toArray();

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
        $response->headers->set('Content-Disposition', 'attachment; filename="商品移動Data.csv"');

         // Status変更
         $headers=MoveHeader::where('status_id',1)->get();
        //  dd($headers);
         foreach ($headers as $header) {
            $header->status_id = 5;
            $header->save();
        }

        return $response;

    }

    public function download_shop()
    {
        $inventorys = DB::table('move_headers')
        ->join('move_details','move_details.move_header_id','move_headers.id')
        ->join('users','users.id','move_details.user_id')
        ->join('shops','shops.id','move_headers.from_shop_id')
        ->join('shops as to_shops','to_shops.id','move_headers.to_shop_id')
        ->leftjoin('skus','skus.id','move_details.sku_id')
        ->where('move_headers.status_id',1)
        ->where('move_headers.to_shop_id','<>',106)
        ->groupBy('move_headers.id','move_headers.move_date','move_headers.from_shop_id','shops.shop_name','move_headers.to_shop_id','to_shops.shop_name','users.name','move_details.sku_id','skus.hinban_id','skus.col_id','skus.size_id','move_details.price','move_headers.memo')
        ->selectRaw('move_headers.id,move_headers.move_date,move_headers.from_shop_id,shops.shop_name,move_headers.to_shop_id,to_shops.shop_name as to_shop_name,users.name,move_details.sku_id,skus.hinban_id,skus.col_id,skus.size_id,move_details.price,sum(move_details.pcs) as pcs,move_headers.memo')
        ->orderBy('move_headers.id','asc')
        ->distinct()
        // ->groupBy('my_karts.maker_id')
        // ->orderBy('order_items.sku_id')
        ->get();

        // dd($inventorys);

        $csvHeader = [
            'id','date','from_shop_id','from_shop_name','to_shop_id','to_shop_name','staff_name','sku_id','hinban_id','col_id','size_id','price','pcs','memo'];
        $csvData = $inventorys->toArray();

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
        $response->headers->set('Content-Disposition', 'attachment; filename="商品移動Data.csv"');

         // Status変更
        $headers=MoveHeader::where('status_id',1)
        ->where('move_headers.to_shop_id','<>',106)
        ->get();
        //  dd($headers);
         foreach ($headers as $header) {
            $header->status_id = 5;
            $header->save();
        }

        return $response;

    }

    public function download_dc()
    {
        $inventorys = DB::table('move_headers')
        ->join('move_details','move_details.move_header_id','move_headers.id')
        ->join('users','users.id','move_details.user_id')
        ->join('shops','shops.id','move_headers.from_shop_id')
        ->join('shops as to_shops','to_shops.id','move_headers.to_shop_id')
        ->leftjoin('skus','skus.id','move_details.sku_id')
        ->where('move_headers.status_id',1)
        ->where('move_headers.to_shop_id',106)
        ->groupBy('move_headers.id','move_headers.move_date','move_headers.from_shop_id','shops.shop_name','move_headers.to_shop_id','to_shops.shop_name','users.name','move_details.sku_id','skus.hinban_id','skus.col_id','skus.size_id','move_details.price','move_headers.memo')
        ->selectRaw('move_headers.id,move_headers.move_date,move_headers.from_shop_id,shops.shop_name,move_headers.to_shop_id,to_shops.shop_name as to_shop_name,users.name,move_details.sku_id,skus.hinban_id,skus.col_id,skus.size_id,move_details.price,sum(move_details.pcs) as pcs,move_headers.memo')
        ->orderBy('move_headers.id','asc')
        ->distinct()
        // ->groupBy('my_karts.maker_id')
        // ->orderBy('order_items.sku_id')
        ->get();

        // dd($inventorys);

        $csvHeader = [
            'id','date','from_shop_id','from_shop_name','to_shop_id','to_shop_name','staff_name','sku_id','hinban_id','col_id','size_id','price','pcs','memo'];

        $csvData = $inventorys->toArray();

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
        $response->headers->set('Content-Disposition', 'attachment; filename="商品移動Data_' . $timestamp . '.csv"');

         // Status変更
        $headers=MoveHeader::where('status_id',1)
        ->where('move_headers.to_shop_id',106)
        ->get();
        //  dd($headers);
        foreach ($headers as $header) {
            $header->status_id = 5;
            $header->save();
        }

        return $response;

    }


    public function result_index() {
        $move_hs = DB::table('move_headers')
        ->join('move_details','move_details.move_header_id','move_headers.id')
        ->join('users','users.id','move_details.user_id')
        ->join('shops','shops.id','move_headers.from_shop_id')
        ->join('shops as to_shops','to_shops.id','move_headers.to_shop_id')
        ->join('move_statuses','move_statuses.id','move_headers.status_id')
        ->where('move_headers.from_shop_id',Auth::user()->shop_id)
        ->groupBy('move_details.move_header_id','move_headers.from_shop_id','move_headers.to_shop_id','move_headers.status_id','move_statuses.status_name','shops.shop_name','to_shops.shop_name','users.name','move_headers.move_date')
        ->selectRaw('move_details.move_header_id as id,move_headers.move_date,move_headers.from_shop_id,move_headers.to_shop_id,move_headers.status_id,move_statuses.status_name,shops.shop_name,to_shops.shop_name as to_shop_name,users.name,sum(move_details.pcs) as pcs')
        ->orderBy('move_header_id','desc')
        ->paginate(50);

        $move_hs2 = DB::table('move_headers')
        ->join('move_details','move_details.move_header_id','move_headers.id')
        ->join('users','users.id','move_details.user_id')
        ->join('shops','shops.id','move_headers.from_shop_id')
        ->join('shops as to_shops','to_shops.id','move_headers.to_shop_id')
        ->join('move_statuses','move_statuses.id','move_headers.status_id')
        ->groupBy('move_details.move_header_id','move_headers.from_shop_id','move_headers.status_id','move_statuses.status_name','shops.shop_name','to_shops.shop_name','users.name','move_headers.move_date')
        ->selectRaw('move_details.move_header_id as id,move_headers.move_date,move_headers.from_shop_id,move_headers.status_id,move_statuses.status_name,shops.shop_name,to_shops.shop_name as to_shop_name,users.name,sum(move_details.pcs) as pcs')
        ->orderBy('move_header_id','desc')
        ->paginate(50);

        $move_ks = DB::table('move_headers')
        ->join('move_details','move_details.move_header_id','move_headers.id')
        ->join('users','users.id','move_details.user_id')
        ->join('shops','shops.id','move_headers.from_shop_id')
        ->join('shops as to_shops','to_shops.id','move_headers.to_shop_id')
        ->join('move_statuses','move_statuses.id','move_headers.status_id')
        ->where('move_headers.to_shop_id',106)
        ->groupBy('move_details.move_header_id','move_headers.from_shop_id','move_headers.to_shop_id','move_headers.status_id','move_statuses.status_name','shops.shop_name','to_shops.shop_name','users.name','move_headers.move_date')
        ->selectRaw('move_details.move_header_id as id,move_headers.move_date,move_headers.from_shop_id,move_headers.to_shop_id,move_headers.status_id,move_statuses.status_name,shops.shop_name,to_shops.shop_name as to_shop_name,users.name,sum(move_details.pcs) as pcs')
        ->orderBy('move_header_id','desc')
        ->paginate(50);

        $dl_new = DB::table('move_headers') //未ＤＬ判定用
        ->where('move_headers.status_id',1)
        ->exists();

        $user = DB::table('users')
        ->where('users.id',Auth::id())->first();
            //    dd($move_hs,$user,$dl_new);
        return view('move.result_index', compact('move_hs','user','move_hs2','move_ks','dl_new'));
    }

    public function result_show($id) {
        $move_h = DB::table('move_headers')
        ->join('move_details','move_details.move_header_id','move_headers.id')
        ->join('users','users.id','move_details.user_id')
        ->join('shops','shops.id','move_headers.from_shop_id')
        ->join('shops as to_shops','to_shops.id','move_headers.to_shop_id')
        ->join('move_statuses','move_statuses.id','move_headers.status_id')
        ->where('move_headers.id',$id)
        ->groupBy('move_details.move_header_id','move_headers.from_shop_id','move_headers.status_id','move_statuses.status_name','shops.shop_name','users.name','move_headers.move_date','move_headers.memo')
        ->selectRaw('move_details.move_header_id as id,move_headers.move_date,move_headers.from_shop_id,move_headers.status_id,move_statuses.status_name,move_headers.memo,shops.shop_name,users.name,sum(move_details.pcs) as total_pcs')
        ->orderBy('move_header_id','desc')
        ->first();

        $move_fs = DB::table('move_headers')
        ->join('move_details','move_details.move_header_id','move_headers.id')
        ->join('users','users.id','move_details.user_id')
        ->join('shops','shops.id','move_headers.from_shop_id')
        ->join('shops as to_shops','to_shops.id','move_headers.to_shop_id')
        ->leftjoin('skus','skus.id','move_details.sku_id')
        ->leftjoin('hinbans','hinbans.id','skus.hinban_id')
        ->where('move_headers.id',$id)
        ->groupBy('move_details.sku_id','skus.hinban_id','hinbans.hinban_name','skus.col_id','skus.size_id')
        ->selectRaw('move_details.sku_id,skus.hinban_id,hinbans.hinban_name,skus.col_id,skus.size_id,sum(move_details.pcs) as f_pcs')
        ->orderBy('sku_id','asc')
        ->paginate(50);

        $user = DB::table('users')
        ->where('users.id',Auth::id())->first();
            //    dd($move_h,$user,$move_fs);
        return view('move.result_show', compact('move_h','move_fs','user'));
    }

    public function result_destroy($id)
    {
        $work = MoveHeader::findOrFail($id);
        $work->delete();
        return redirect()->route('move_result_index')->with(['message'=>'削除しました','status'=>'alert']);
    }
}
