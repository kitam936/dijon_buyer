<?php


namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use \SplFileObject;
use Throwable;
use App\Models\Vendor;
use App\Models\Brand;
use App\Models\Col;
use App\Models\Hinban;
use App\Models\Size;
use App\Models\Sku;
use App\Models\Unit;
use App\Models\User;
use App\Models\Face;
use App\Models\PreData;


class DataController extends Controller
{

    public function menu()
    {
        return view('data.data_menu');
    }
    public function index()
    {
        return view('data.data_index');
    }


    public function create()
    {
        return view('data.data_create');
    }

    public function user_index()
    {
        $users=User::All();

        return view('data.user_data',compact('users'));
    }



    public function unit_index(Request $request)
    {
        $units=Unit::All();

        return view('data.unit_data',compact('units'));
    }

    public function face_index(Request $request)
    {
        $faces=DB::table('faces')
        ->select(['face_code','face_item'])
        ->groupBy(['face_code','face_item'])
        ->orderBy('face_code','asc')
        ->get();

        return view('data.face_data',compact('faces'));
    }

    public function brand_index(Request $request)
    {
        $brands=Brand::All();

        return view('data.brand_data',compact('brands'));
    }

    public function sku_index(Request $request)
    {
        $skus=Sku::All();

        return view('data.sku_data',compact('skus'));
    }

    public function col_index(Request $request)
    {
        $cols=Col::All();

        return view('data.col_data',compact('cols'));
    }

    public function size_index(Request $request)
    {
        $sizes=Size::All();

        return view('data.size_data',compact('sizes'));
    }

    public function rate_menu()
    {
        $ex_rate = DB::table('ex_rates')->first();
        $cost_rate = DB::table('cost_rates')->first();
        return view('data.rate_menu',compact('ex_rate','cost_rate'));
    }

    public function hinban_index(Request $request)
    {
        $products=Hinban::with('unit')
        ->where('year_code','LIKE','%'.$request->year_code.'%')
        ->where('brand_id','LIKE','%'.$request->brand_code.'%')
        ->where('unit_id','LIKE','%'.$request->unit_code.'%')
        ->paginate(100);
        $years=DB::table('hinbans')
        ->select(['year_code'])
        ->groupBy(['year_code'])
        ->orderBy('year_code','desc')
        ->get();
        $units=DB::table('units')
        ->select(['id'])
        ->groupBy(['id'])
        ->orderBy('id','asc')
        ->get();
        $brands=DB::table('brands')
        ->select(['id'])
        ->groupBy(['id'])
        ->orderBy('id','asc')
        ->get();
        // dd($products);
        return view('data.hinban_data',compact('products','years','units','brands'));
    }

    public function hinban_index2(Request $request)
    {
        // $products=Hinban::with('unit')
        // ->join('brands','hinbans.brand_id','=','brands.id')
        // ->where('year_code','LIKE','%'.$request->year_code.'%')
        // ->where('brand_id','LIKE','%'.$request->brand_code.'%')
        // ->where('unit_id','LIKE','%'.$request->unit_code.'%')
        // ->where('face_code','LIKE','%'.$request->face_code.'%')
        // ->paginate(100);
        $products=DB::table('hinbans')
        ->join('units','hinbans.unit_id','=','units.id')
        ->join('brands','hinbans.brand_id','=','brands.id')
        ->where('hinbans.year_code','LIKE','%'.$request->year_code.'%')
        ->where('hinbans.brand_id','LIKE','%'.$request->brand_code.'%')
        ->where('hinbans.unit_id','LIKE','%'.$request->unit_code.'%')
        ->where('hinbans.face_code','LIKE','%'.$request->face_code.'%')
        ->select('hinbans.id as hinban_id','hinbans.year_code','hinbans.brand_id','hinbans.unit_id','hinbans.hinban_name','hinbans.face_code','hinbans.unit_id','brands.brand_name')
        ->paginate(100);
        $years=DB::table('hinbans')
        ->select(['year_code'])
        ->groupBy(['year_code'])
        ->orderBy('year_code','desc')
        ->get();
        $units=DB::table('hinbans')
        ->join('units','hinbans.unit_id','=','units.id')
        ->where('hinbans.year_code','LIKE','%'.$request->year_code.'%')
        ->where('hinbans.brand_id','LIKE','%'.$request->brand_code.'%')
        // ->where('hinbans.unit_id','LIKE','%'.$request->unit_code.'%')
        ->where('hinbans.face_code','LIKE','%'.$request->face_code.'%')
        ->select(['hinbans.unit_id'])
        ->groupBy(['hinbans.unit_id'])
        ->orderBy('hinbans.unit_id','asc')
        ->get();
        $faces=DB::table('hinbans')
        ->join('faces','hinbans.face_code','=','faces.face_code')
        ->where('hinbans.year_code','LIKE','%'.$request->year_code.'%')
        ->where('hinbans.brand_id','LIKE','%'.$request->brand_code.'%')
        ->where('hinbans.unit_id','LIKE','%'.$request->unit_code.'%')
        // ->where('hinbans.face_code','LIKE','%'.$request->face_code.'%')
        ->select(['hinbans.face_code','faces.face_item'])
        ->groupBy(['hinbans.face_code','faces.face_item'])
        ->orderBy('hinbans.face_code','asc')
        ->get();
        $brands=DB::table('brands')
        ->select(['id','brand_name'])
        ->groupBy(['id','brand_name'])
        ->orderBy('id','asc')
        ->get();
        // dd($products);
        return view('hinban.hinban_data2',compact('products','years','units','brands','faces'));
    }

    public function predata_index(Request $request)
    {
        $products=DB::table('pre_data')
        ->join('brands','brands.id','=','pre_data.brand_id')
        ->leftJoin('hinbans','pre_data.hinban_id','=','hinbans.id') // Ensure this join condition is correct
        // ->whereNull('hinbans.id') // Uncomment if you want to filter out rows with no matching hinbans
        ->where('pre_data.year_code','LIKE','%'.$request->year_code.'%')
        ->where('pre_data.brand_id','LIKE','%'.$request->brand_code.'%')
        ->where('pre_data.unit_id','LIKE','%'.$request->unit_code.'%')
        ->where('pre_data.face_code','LIKE','%'.$request->face_code.'%')
        ->select('pre_data.hinban_id','pre_data.year_code','pre_data.brand_id','pre_data.unit_id','pre_data.face_code','pre_data.unit_id','brands.brand_name','hinbans.id as done')
        ->orderBy('pre_data.hinban_id','asc')
        ->paginate(100);


        $years=DB::table('pre_data')
        ->select(['year_code'])
        ->groupBy(['year_code'])
        ->orderBy('year_code','desc')
        ->get();
        $faces=DB::table('pre_data')
        ->join('faces','pre_data.face_code','=','faces.face_code')
        ->join('units','pre_data.unit_id','=','units.id')
        ->where('pre_data.year_code','LIKE','%'.$request->year_code.'%')
        ->where('pre_data.brand_id','LIKE','%'.$request->brand_code.'%')
        ->where('pre_data.unit_id','LIKE','%'.$request->unit_code.'%')
        // ->where('pre_data.face_code','LIKE','%'.$request->face_code.'%')
        ->select(['pre_data.face_code','faces.face_item'])
        ->groupBy(['pre_data.face_code','faces.face_item'])
        ->orderBy('pre_data.face_code','asc')
        ->get();
        $units=DB::table('pre_data')
        ->join('faces','pre_data.face_code','=','faces.face_code')
        ->join('units','pre_data.unit_id','=','units.id')
        ->where('pre_data.year_code','LIKE','%'.$request->year_code.'%')
        ->where('pre_data.brand_id','LIKE','%'.$request->brand_code.'%')
        // ->where('pre_data.unit_id','LIKE','%'.$request->unit_code.'%')
        ->where('pre_data.face_code','LIKE','%'.$request->face_code.'%')
        ->select(['pre_data.unit_id','units.season_name'])
        ->groupBy(['pre_data.unit_id','units.season_name'])
        ->orderBy('pre_data.unit_id','asc')
        ->get();
        $brands=DB::table('pre_data')
        ->join('faces','pre_data.face_code','=','faces.face_code')
        ->join('units','pre_data.unit_id','=','units.id')
        ->join('brands','pre_data.brand_id','=','brands.id')
        ->where('pre_data.year_code','LIKE','%'.$request->year_code.'%')
        // ->where('pre_data.brand_id','LIKE','%'.$request->brand_code.'%')
        ->where('pre_data.unit_id','LIKE','%'.$request->unit_code.'%')
        ->where('pre_data.face_code','LIKE','%'.$request->face_code.'%')
        ->select(['pre_data.brand_id','brands.brand_name'])
        ->groupBy(['pre_data.brand_id','brands.brand_name'])
        ->orderBy('pre_data.brand_id','asc')
        ->get();
        // dd($products,$hinbans);
        return view('data.predata',compact('products','faces','units','brands','years'));
    }

    public function predata_create()
    {
        $years=DB::table('pre_data')
        ->select(['year_code'])
        ->groupBy(['year_code'])
        ->orderBy('year_code','desc')
        ->get();
        $guns=DB::table('pre_data')
        ->select(['shohin_gun'])
        ->groupBy(['shohin_gun'])
        ->orderBy('shohin_gun','asc')
        ->get();
        $faces=DB::table('faces')
        ->orderBy('faces.face_code','asc')
        ->get();
        $units=DB::table('units')
        ->orderBy('units.id','asc')
        ->get();
        $brands=DB::table('brands')
        ->orderBy('brands.id','asc')
        ->get();
        // dd($products,$hinbans);
        return view('data.predata_create',compact('years','faces','units','brands','guns'));
    }

    public function predata_store(Request $request)
    {
        if($request->brand_id == 1) {
            PreData::create([
                'year_code' => $request->year_code,
                'shohin_gun' => $request->shohin_gun,
                'brand_id'  => $request->brand_id,
                'seireki_unit'  => "20".$request->year_code.$request->unit_id,
                'unit_id'   => $request->unit_id,
                'face_code' => $request->face_code,
                'hinban_id'  => $request->hinban_id,
                'kyotu_hinban' => substr($request->hinban_id, -5), // 右から5文字を抽出

                ]);

            return to_route('data.predata_index')->with(['message'=>'登録されました','status'=>'info']);
        } else {
            PreData::create([
                'year_code' => $request->year_code,
                'shohin_gun' => $request->shohin_gun,
                'brand_id'  => $request->brand_id,
                'seireki_unit'  => "20".$request->year_code.$request->unit_id,
                'unit_id'   => $request->unit_id,
                'face_code' => $request->face_code,
                'hinban_id'  => $request->hinban_id,
                'kyotu_hinban' =>$request->hinban_id,
                ]);
            return to_route('data.predata_index')->with(['message'=>'登録されました','status'=>'info']);
        }
    }

    public function predata_destroy_one($id)
    {
        $predata = PreData::where('hinban_id', $id)->firstOrFail();
        $predata->delete();


        return to_route('data.predata_index')->with(['message'=>'削除されました','status'=>'alert']);
    }


    public function vendor_index(Request $request)
    {
        $vendors=Vendor::All();

        return view('data.vendor_data',compact('vendors'));
    }



    public function delete_index()
    {

        return view('data.delete_index');

    }



    public function sku_destroy(Request $request)
    {
        $Stocks=Sku::query()->delete();

        return to_route('data.delete_index')->with(['message'=>'削除されました','status'=>'alert']);
    }

    public function hinban_destroy(Request $request)
    {
        // $Stocks=Hinban::query()->delete();
        DB::table('hinbans')
        // ->where('hinbans.year_code','>=',($request->year1))
        // ->where('hinbans.year_code','<=',($request->year2))
        ->delete();

        return to_route('data.delete_index')->with(['message'=>'削除されました','status'=>'alert']);
    }

    public function vendor_destroy(Request $request)
    {
        $vendor=Vendor::query()->delete();

        return to_route('data.delete_index')->with(['message'=>'削除されました','status'=>'alert']);
    }

    public function unit_destroy(Request $request)
    {
        $Stocks=Unit::query()->delete();

        return to_route('data.delete_index')->with(['message'=>'削除されました','status'=>'alert']);
    }

    public function face_destroy(Request $request)
    {
        $Stocks=Face::query()->delete();

        return to_route('data.delete_index')->with(['message'=>'削除されました','status'=>'alert']);
    }

    public function brand_destroy(Request $request)
    {
        $Stocks=Brand::query()->delete();

        return to_route('data.delete_index')->with(['message'=>'削除されました','status'=>'alert']);
    }

    public function col_destroy(Request $request)
    {
        $Stocks=Col::query()->delete();

        return to_route('data.delete_index')->with(['message'=>'削除されました','status'=>'alert']);
    }

    public function size_destroy(Request $request)
    {
        $Stocks=Size::query()->delete();

        return to_route('data.delete_index')->with(['message'=>'削除されました','status'=>'alert']);
    }

    public function predata_destroy(Request $request)
    {
        $predatass=PreData::query()->delete();

        return to_route('data.delete_index')->with(['message'=>'削除されました','status'=>'alert']);
    }

    public function predata_upload(Request $request)
    {
        Predata::query()->delete();
        // タイムアウト対応？
        ini_set('max_execution_time',180);

        setlocale(LC_ALL, 'ja_JP.UTF-8');
        // dd($request);
		$file = $request->file('predata');
        // dd($file);

        file_put_contents($file, mb_convert_encoding(file_get_contents($file), 'UTF-8', 'SJIS-win'));


        DB::beginTransaction();

        try{
			//ファイルの読み込み
			$csv_arr = new \SplFileObject($file);
			$csv_arr->setFlags(\SplFileObject::READ_CSV | \SplFileObject::READ_AHEAD | \SplFileObject::SKIP_EMPTY);

			//csvの値格納用配列
			$data_arr = [];

            $count = 0; // 登録件数確認用

			foreach($csv_arr as $i=>$line){
				if ($line === [null]) continue;
				if($i == 0) continue;

				//配列に格納
				// $data_arr[$i]['id'] = $line[0];
				$data_arr[$i]['year_code'] = $line[0];
				$data_arr[$i]['shohin_gun'] = $line[1];
				$data_arr[$i]['brand_id'] = $line[2];
                $data_arr[$i]['seireki_unit'] = $line[3];
                $data_arr[$i]['unit_id'] = $line[4];
				$data_arr[$i]['face_code'] = $line[5];
                $data_arr[$i]['hinban_id'] = $line[6];
                $data_arr[$i]['kyotu_hinban'] = $line[7];
				// $data_arr[$i]['created_at'] = $line[9];
				// $data_arr[$i]['updated_at'] = $line[10];

                $count++;
			}

                //保存

			foreach(array_chunk($data_arr, 500) as $chunk){
				DB::transaction(function() use ($chunk){
					DB::table('pre_data')->insert($chunk);

				});

			}

			DB::commit();

            return view('data.result2',compact('count'));

		}catch(Throwable $e){
			DB::rollback();
            Log::error($e);
            // throw $e;
            return to_route('data.predata_index')->with(['message'=>'エラーにより処理を中断しました。csvデータを確認してください。','status'=>'alert']);
		}
    }


    public function vendor_upsert(Request $request)
    {

        setlocale(LC_ALL, 'ja_JP.UTF-8');
        // dd($request);
		$file = $request->file('co_data');
        // dd($file);

        file_put_contents($file, mb_convert_encoding(file_get_contents($file), 'UTF-8', 'SJIS-win'));


        DB::beginTransaction();

        try{
			//ファイルの読み込み
			$csv_arr = new \SplFileObject($file);
			$csv_arr->setFlags(\SplFileObject::READ_CSV | \SplFileObject::READ_AHEAD | \SplFileObject::SKIP_EMPTY);

			//csvの値格納用配列
			$data_arr = [];

            $count = 0; // 登録件数確認用

			foreach($csv_arr as $i=>$line){
				if ($line === [null]) continue;
				if($i == 0) continue;


				//配列に格納
				$data_arr[$i]['id'] = $line[0];
				$data_arr[$i]['vendor_name'] = $line[1];
				$data_arr[$i]['vendor_info'] = $line[2];
				// $data_arr[$i]['created_at'] = $line[3];
				// $data_arr[$i]['updated_at'] = $line[4];
                $count++;
			}

                //保存

			foreach(array_chunk($data_arr, 500) as $chunk){
				DB::transaction(function() use ($chunk){
					DB::table('vendors')->upsert($chunk,['id']);

				});

			}

			DB::commit();

            return view('data.result',compact('count'));

		}catch(Throwable $e){
			DB::rollback();
            Log::error($e);
            // throw $e;
            return to_route('data.create')->with(['message'=>'エラーにより処理を中断しました。csvデータを確認してください。','status'=>'alert']);
		}
    }


    public function unit_upsert(Request $request)
    {

        setlocale(LC_ALL, 'ja_JP.UTF-8');
        // dd($request);
		$file = $request->file('unit_data');
        // dd($file);

        file_put_contents($file, mb_convert_encoding(file_get_contents($file), 'UTF-8', 'SJIS-win'));


        DB::beginTransaction();

        try{
			//ファイルの読み込み
			$csv_arr = new \SplFileObject($file);
			$csv_arr->setFlags(\SplFileObject::READ_CSV | \SplFileObject::READ_AHEAD | \SplFileObject::SKIP_EMPTY);

			//csvの値格納用配列
			$data_arr = [];

            $count = 0; // 登録件数確認用

			foreach($csv_arr as $i=>$line){
				if ($line === [null]) continue;
				if($i == 0) continue;


				//配列に格納
				$data_arr[$i]['id'] = $line[0];
                $data_arr[$i]['season_id'] = $line[1];
				$data_arr[$i]['season_name'] = $line[2];
				$data_arr[$i]['created_at'] = $line[3];
				$data_arr[$i]['updated_at'] = $line[4];
                $count++;
			}

                //保存

			foreach(array_chunk($data_arr, 500) as $chunk){
				DB::transaction(function() use ($chunk){
					DB::table('units')->upsert($chunk,['id']);

				});

			}

			DB::commit();

            return view('data.result',compact('count'));

		}catch(Throwable $e){
			DB::rollback();
            Log::error($e);
            // throw $e;
            return to_route('data.create')->with(['message'=>'エラーにより処理を中断しました。csvデータを確認してください。','status'=>'alert']);
		}
    }

    public function face_upsert(Request $request)
    {

        setlocale(LC_ALL, 'ja_JP.UTF-8');
        // dd($request);
		$file = $request->file('face_data');
        // dd($file);

        file_put_contents($file, mb_convert_encoding(file_get_contents($file), 'UTF-8', 'SJIS-win'));


        DB::beginTransaction();

        try{
			//ファイルの読み込み
			$csv_arr = new \SplFileObject($file);
			$csv_arr->setFlags(\SplFileObject::READ_CSV | \SplFileObject::READ_AHEAD | \SplFileObject::SKIP_EMPTY);

			//csvの値格納用配列
			$data_arr = [];

            $count = 0; // 登録件数確認用

			foreach($csv_arr as $i=>$line){
				if ($line === [null]) continue;
				if($i == 0) continue;


				//配列に格納
				// $data_arr[$i]['id'] = $line[0];
                $data_arr[$i]['face_code'] = $line[1];
				$data_arr[$i]['face_item'] = $line[2];
				// $data_arr[$i]['created_at'] = $line[3];
				// $data_arr[$i]['updated_at'] = $line[4];
                $count++;
			}

                //保存

			foreach(array_chunk($data_arr, 500) as $chunk){
				DB::transaction(function() use ($chunk){
					DB::table('faces')->upsert($chunk,['id']);

				});

			}

			DB::commit();

            return view('data.result',compact('count'));

		}catch(Throwable $e){
			DB::rollback();
            Log::error($e);
            // throw $e;
            return to_route('data.create')->with(['message'=>'エラーにより処理を中断しました。csvデータを確認してください。','status'=>'alert']);
		}
    }

    public function brand_upsert(Request $request)
    {

        setlocale(LC_ALL, 'ja_JP.UTF-8');
        // dd($request);
		$file = $request->file('brand_data');
        // dd($file);

        file_put_contents($file, mb_convert_encoding(file_get_contents($file), 'UTF-8', 'SJIS-win'));


        DB::beginTransaction();

        try{
			//ファイルの読み込み
			$csv_arr = new \SplFileObject($file);
			$csv_arr->setFlags(\SplFileObject::READ_CSV | \SplFileObject::READ_AHEAD | \SplFileObject::SKIP_EMPTY);

			//csvの値格納用配列
			$data_arr = [];

            $count = 0; // 登録件数確認用

			foreach($csv_arr as $i=>$line){
				if ($line === [null]) continue;
				if($i == 0) continue;


				//配列に格納
				$data_arr[$i]['id'] = $line[0];
                $data_arr[$i]['brand_name'] = $line[1];
				$data_arr[$i]['brand_info'] = $line[2];
                $data_arr[$i]['kizoku_g'] = $line[3];
				// $data_arr[$i]['created_at'] = $line[4];
				// $data_arr[$i]['updated_at'] = $line[5];
                $count++;
			}

                //保存

			foreach(array_chunk($data_arr, 500) as $chunk){
				DB::transaction(function() use ($chunk){
					DB::table('brands')->upsert($chunk,['id']);

				});

			}

			DB::commit();

            return view('data.result',compact('count'));

		}catch(Throwable $e){
			DB::rollback();
            Log::error($e);
            // throw $e;
            return to_route('data.create')->with(['message'=>'エラーにより処理を中断しました。csvデータを確認してください。','status'=>'alert']);
		}
    }

    public function hinban_upsert(Request $request)
    {

        setlocale(LC_ALL, 'ja_JP.UTF-8');
        // dd($request);
		$file = $request->file('hinban_data');
        // dd($file);

        file_put_contents($file, mb_convert_encoding(file_get_contents($file), 'UTF-8', 'SJIS-win'));


        DB::beginTransaction();

        try{
			//ファイルの読み込み
			$csv_arr = new \SplFileObject($file);
			$csv_arr->setFlags(\SplFileObject::READ_CSV | \SplFileObject::READ_AHEAD | \SplFileObject::SKIP_EMPTY);

			//csvの値格納用配列
			$data_arr = [];

            $count = 0; // 登録件数確認用

			foreach($csv_arr as $i=>$line){
				if ($line === [null]) continue;
				if($i == 0) continue;


				//配列に格納
				// $data_arr[$i]['id'] = $line[0];
                $data_arr[$i]['brand_id'] = $line[1];
                $data_arr[$i]['unit_id'] = $line[2];
				$data_arr[$i]['face_code'] = $line[3];
                $data_arr[$i]['hinban_id'] = $line[4];
                $data_arr[$i]['prod_code'] = $line[5];
                $data_arr[$i]['hinban_name'] = $line[6];
                $data_arr[$i]['season_code'] = $line[7];
                $data_arr[$i]['year_code'] = $line[8];
                $data_arr[$i]['shohin_gun'] = $line[9];
                $data_arr[$i]['kizoku_g'] = $line[10];
                $data_arr[$i]['seireki_unit'] = $line[11];
                $data_arr[$i]['kyotu_hinban'] = $line[12];
                $data_arr[$i]['vendor_id'] = $line[13];
				// $data_arr[$i]['created_at'] = $line[11];
				// $data_arr[$i]['updated_at'] = $line[12];
                $count++;
			}

                //保存

			foreach(array_chunk($data_arr, 500) as $chunk){
				DB::transaction(function() use ($chunk){
					DB::table('hinbans')->upsert($chunk,['id']);

				});

			}

			DB::commit();

            return view('data.result',compact('count'));

		}catch(Throwable $e){
			DB::rollback();
            Log::error($e);
            // throw $e;
            return to_route('data.create')->with(['message'=>'エラーにより処理を中断しました。csvデータを確認してください。','status'=>'alert']);
		}
    }

    public function sku_upsert(Request $request)
    {

        setlocale(LC_ALL, 'ja_JP.UTF-8');
        // dd($request);
		$file = $request->file('sku_data');
        // dd($file);

        file_put_contents($file, mb_convert_encoding(file_get_contents($file), 'UTF-8', 'SJIS-win'));


        DB::beginTransaction();

        try{
			//ファイルの読み込み
			$csv_arr = new \SplFileObject($file);
			$csv_arr->setFlags(\SplFileObject::READ_CSV | \SplFileObject::READ_AHEAD | \SplFileObject::SKIP_EMPTY);

			//csvの値格納用配列
			$data_arr = [];

            $count = 0; // 登録件数確認用

			foreach($csv_arr as $i=>$line){
				if ($line === [null]) continue;
				if($i == 0) continue;


				//配列に格納
				$data_arr[$i]['id'] = $line[0];
                $data_arr[$i]['hinban_id'] = $line[1];
                $data_arr[$i]['sku_code'] = $line[2];
				$data_arr[$i]['col_id'] = $line[3];
                $data_arr[$i]['size_id'] = $line[4];
                $data_arr[$i]['local_cur_price'] = $line[5];
                $data_arr[$i]['local_yen_price'] = $line[6];
                $data_arr[$i]['mix_rate'] = $line[7];
                $data_arr[$i]['length'] = $line[8];
                $data_arr[$i]['width'] = $line[9];
                $data_arr[$i]['sku_image'] = $line[10];
				// $data_arr[$i]['created_at'] = $line[11];
				// $data_arr[$i]['updated_at'] = $line[12];
                $count++;
			}

                //保存

			foreach(array_chunk($data_arr, 500) as $chunk){
				DB::transaction(function() use ($chunk){
					DB::table('skus')->upsert($chunk,['id']);

				});

			}

			DB::commit();

            return view('data.result',compact('count'));

		}catch(Throwable $e){
			DB::rollback();
            Log::error($e);
            // throw $e;
            return to_route('data.create')->with(['message'=>'エラーにより処理を中断しました。csvデータを確認してください。','status'=>'alert']);
		}
    }

    public function col_upsert(Request $request)
    {

        setlocale(LC_ALL, 'ja_JP.UTF-8');
        // dd($request);
		$file = $request->file('col_data');
        // dd($file);

        file_put_contents($file, mb_convert_encoding(file_get_contents($file), 'UTF-8', 'SJIS-win'));


        DB::beginTransaction();

        try{
			//ファイルの読み込み
			$csv_arr = new \SplFileObject($file);
			$csv_arr->setFlags(\SplFileObject::READ_CSV | \SplFileObject::READ_AHEAD | \SplFileObject::SKIP_EMPTY);

			//csvの値格納用配列
			$data_arr = [];

            $count = 0; // 登録件数確認用

			foreach($csv_arr as $i=>$line){
				if ($line === [null]) continue;
				if($i == 0) continue;


				//配列に格納
				$data_arr[$i]['id'] = $line[0];
                $data_arr[$i]['col_name'] = $line[1];
				// $data_arr[$i]['created_at'] = $line[2];
				// $data_arr[$i]['updated_at'] = $line[3];
                $count++;
			}

                //保存

			foreach(array_chunk($data_arr, 500) as $chunk){
				DB::transaction(function() use ($chunk){
					DB::table('cols')->upsert($chunk,['id']);

				});

			}

			DB::commit();

            return view('data.result',compact('count'));

		}catch(Throwable $e){
			DB::rollback();
            Log::error($e);
            // throw $e;
            return to_route('data.create')->with(['message'=>'エラーにより処理を中断しました。csvデータを確認してください。','status'=>'alert']);
		}
    }

    public function size_upsert(Request $request)
    {

        setlocale(LC_ALL, 'ja_JP.UTF-8');
        // dd($request);
		$file = $request->file('size_data');
        // dd($file);

        file_put_contents($file, mb_convert_encoding(file_get_contents($file), 'UTF-8', 'SJIS-win'));


        DB::beginTransaction();

        try{
			//ファイルの読み込み
			$csv_arr = new \SplFileObject($file);
			$csv_arr->setFlags(\SplFileObject::READ_CSV | \SplFileObject::READ_AHEAD | \SplFileObject::SKIP_EMPTY);

			//csvの値格納用配列
			$data_arr = [];

            $count = 0; // 登録件数確認用

			foreach($csv_arr as $i=>$line){
				if ($line === [null]) continue;
				if($i == 0) continue;


				//配列に格納
				$data_arr[$i]['id'] = $line[0];
                $data_arr[$i]['size_name'] = $line[1];
				// $data_arr[$i]['created_at'] = $line[2];
				// $data_arr[$i]['updated_at'] = $line[3];
                $count++;
			}

                //保存

			foreach(array_chunk($data_arr, 500) as $chunk){
				DB::transaction(function() use ($chunk){
					DB::table('sizes')->upsert($chunk,['id']);

				});

			}

			DB::commit();

            return view('data.result',compact('count'));

		}catch(Throwable $e){
			DB::rollback();
            Log::error($e);
            // throw $e;
            return to_route('data.create')->with(['message'=>'エラーにより処理を中断しました。csvデータを確認してください。','status'=>'alert']);
		}
    }

    public function ex_rate_edit($id)
    {
        $ex_rate = DB::table('ex_rates')
        ->first();


        // dd($ex_rate);
        return view('data.ex_rate_edit',compact('ex_rate'));
    }

    public function ex_rate_update(Request $request, $id)
    {
        $ex_rate = DB::table('ex_rates')
        ->where('id',$id)
        ->update(['ex_rate' => $request->ex_rate,'ex_memo' => $request->ex_memo]);

        return redirect()->route('data.rate_menu')->with(['message'=>'為替レートが更新されました','status'=>'info']);
    }

    public function cost_rate_edit($id)
    {
        $cost_rate = DB::table('cost_rates')
        ->first();


        // dd($order_point);
        return view('data.cost_rate_edit',compact('cost_rate'));
    }

    public function cost_rate_update(Request $request, $id)
    {
        $cost_rate = DB::table('cost_rates')
        ->where('id',$id)
        ->update(['cost_rate' => $request->cost_rate,'cost_memo' => $request->cost_memo]);

        return redirect()->route('data.rate_menu')->with(['message'=>'コストレートが更新されました','status'=>'info']);
    }


}
