<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Storage;
use InterventionImage;
use App\Models\Role;
use App\Models\Hinban;
use App\Models\PreData;
use App\Models\User;
use App\Models\Sku;
use Throwable;
use Illuminate\Support\Facades\Log;

class HinbanController extends Controller
{

    public function index(Request $request)
    {
    //
    }

    public function create(Request $request)
    {
        $latest = DB::table('hinbans')
        ->latest()->first();

        $user = User::findOrFail(Auth::id());

        $vendors = DB::table('vendors')->get();

        $brands=DB::table('brands')
        ->select(['id','brand_name'])
        ->groupBy(['id','brand_name'])
        ->orderBy('id','asc')
        ->get();

        $units=DB::table('pre_data')
        ->where('pre_data.brand_id','LIKE','%'.$request->brand_id.'%')
        // ->where('pre_data.unit_id','LIKE','%'.$request->unit_id.'%')
        ->where('pre_data.face_code','LIKE','%'.$request->face_code.'%')
        ->select(['pre_data.unit_id'])
        ->groupBy(['pre_data.unit_id'])
        ->orderBy('pre_data.unit_id','asc')
        ->get();

        $faces=DB::table('pre_data')
        ->join('faces','pre_data.face_code','=','faces.face_code')
        ->where('pre_data.brand_id','LIKE','%'.$request->brand_id.'%')
        ->where('pre_data.unit_id','LIKE','%'.$request->unit_id.'%')
        // ->where('pre_data.face_code','LIKE','%'.$request->face_code.'%')
        ->select(['pre_data.face_code','faces.face_item'])
        ->groupBy(['pre_data.face_code','faces.face_item'])
        ->orderBy('pre_data.face_code','asc')
        ->get();

        $hinbans=DB::table('pre_data')
        ->leftJoin('hinbans','hinbans.id','=','pre_data.hinban_id')
        ->where('pre_data.brand_id','LIKE','%'.$request->brand_id.'%')
        ->where('pre_data.unit_id','LIKE','%'.$request->unit_id.'%')
        ->where('pre_data.face_code','LIKE','%'.$request->face_code.'%')
        ->whereNull('hinbans.id')
        ->select(['pre_data.hinban_id','pre_data.year_code','pre_data.brand_id','pre_data.unit_id','pre_data.face_code'])
        ->get();


        $product=DB::table('pre_data')
        ->join('brands','pre_data.brand_id','=','brands.id')
        ->join('units','pre_data.unit_id','=','units.id')
        ->join('faces','pre_data.face_code','=','faces.face_code')
        ->leftJoin('hinbans','hinbans.id','=','pre_data.hinban_id')
        ->where('pre_data.brand_id','LIKE','%'.$request->brand_id.'%')
        ->where('pre_data.unit_id','LIKE','%'.$request->unit_id.'%')
        ->where('pre_data.face_code','LIKE','%'.$request->face_code.'%')
        ->where('pre_data.hinban_id','LIKE','%'.$request->hinban_id.'%')
        ->whereNull('hinbans.id')
        ->select(['pre_data.hinban_id','pre_data.year_code','pre_data.brand_id','brands.brand_name','pre_data.unit_id','pre_data.face_code','faces.face_item'])
        ->first();


        $cols=DB::table('cols')
        ->select(['id','col_name'])
        ->groupBy(['id','col_name'])
        ->orderBy('id','asc')
        ->get();

        $sizes=DB::table('sizes')
        ->select(['id','size_name'])
        ->groupBy(['id','size_name'])
        ->orderBy('id','asc')
        ->get();




        // dd($request,$latest,$units,$hinbans);
        return view('hinban.hinban_create',compact('latest','user','vendors','product','units','faces','brands','hinbans','cols','sizes'));
    }

    public function create0()
    {
        $latest = DB::table('hinbans')
        ->latest()->first();

        $user = User::findOrFail(Auth::id());

        $vendors = DB::table('vendors')->get();

        $brands=DB::table('brands')
        ->select(['id','brand_name'])
        ->groupBy(['id','brand_name'])
        ->orderBy('id','asc')
        ->get();

        $units=DB::table('pre_data')
        ->select(['pre_data.unit_id'])
        ->groupBy(['pre_data.unit_id'])
        ->orderBy('pre_data.unit_id','asc')
        ->get();

        $faces=DB::table('pre_data')
        ->join('faces','pre_data.face_code','=','faces.face_code')
        ->select(['pre_data.face_code','faces.face_item'])
        ->groupBy(['pre_data.face_code','faces.face_item'])
        ->orderBy('pre_data.face_code','asc')
        ->get();

        $hinbans=DB::table('pre_data')
        ->leftJoin('hinbans','hinbans.hinban_id','=','pre_data.hinban_id')
        ->whereNull('hinbans.hinban_id')
        ->select(['pre_data.hinban_id','pre_data.year_code','pre_data.brand_id','pre_data.unit_id','pre_data.face_code'])
        ->get();


        $products=DB::table('pre_data')
        ->join('brands','pre_data.brand_id','=','brands.id')
        ->join('units','pre_data.unit_id','=','units.id')
        ->join('faces','pre_data.face_code','=','faces.face_code')
        ->leftJoin('hinbans','hinbans.id','=','pre_data.id')
        ->whereNull('hinbans.id')
        ->select(['pre_data.hinban_id','pre_data.year_code','pre_data.brand_id','brands.brand_name','pre_data.unit_id','pre_data.face_code','faces.face_item'])
        ->get();


        $cols=DB::table('cols')
        ->select(['id','col_name'])
        ->groupBy(['id','col_name'])
        ->orderBy('id','asc')
        ->get();

        $sizes=DB::table('sizes')
        ->select(['id','size_name'])
        ->groupBy(['id','size_name'])
        ->orderBy('id','asc')
        ->get();




        // dd($hinbans);
        return view('hinban.hinban_create',compact('latest','user','vendors','products','units','faces','brands','hinbans','cols','sizes'));
    }

    public function create2(Request $request)
    {
        $latest = DB::table('hinbans')
        ->join('brands', 'brands.id', '=', 'hinbans.brand_id')
        ->select(['hinbans.id as hinban_id', 'hinbans.brand_id', 'hinbans.unit_id','hinbans.mix_rate','hinbans.face_code', 'hinbans.hinban_name', 'hinbans.hinban_info', 'hinbans.mix_rate', 'hinbans.season_code', 'hinbans.year_code', 'hinbans.shohin_gun', 'hinbans.kizoku_g', 'hinbans.seireki_unit', 'hinbans.kyotu_hinban', 'hinbans.vendor_id', 'hinbans.prod_code','brands.brand_name', 'hinbans.created_at', 'hinbans.updated_at'])
        ->orderBy('hinbans.created_at', 'desc') // 明確にテーブルを指定
        ->first();

        $latest_sku1 = DB::table('skus')
        ->where('skus.hinban_id', $latest->hinban_id)
        ->where('skus.seq', 1)
        ->first();
        $latest_sku2 = DB::table('skus')
        ->where('skus.hinban_id', $latest->hinban_id)
        ->where('skus.seq', 2)
        ->first();
        $latest_sku3 = DB::table('skus')
        ->where('skus.hinban_id', $latest->hinban_id)
        ->where('skus.seq', 3)
        ->first();
        $latest_sku4 = DB::table('skus')
        ->where('skus.hinban_id', $latest->hinban_id)
        ->where('skus.seq', 4)
        ->first();
        $latest_sku5 = DB::table('skus')
        ->where('skus.hinban_id', $latest->hinban_id)
        ->where('skus.seq', 5)
        ->first();
        $latest_sku6 = DB::table('skus')
        ->where('skus.hinban_id', $latest->hinban_id)
        ->where('skus.seq', 6)
        ->first();

        $latest_vendor = DB::table('vendors')
        ->where('vendors.id', $latest->vendor_id)
        ->first();

        $user = User::findOrFail(Auth::id());

        $vendors = DB::table('vendors')->get();

        $brands=DB::table('brands')
        ->select(['id','brand_name'])
        ->groupBy(['id','brand_name'])
        ->orderBy('id','asc')
        ->get();

        $units=DB::table('pre_data')
        ->where('pre_data.brand_id','LIKE','%'.$request->brand_id.'%')
        // ->where('pre_data.unit_id','LIKE','%'.$request->unit_id.'%')
        ->where('pre_data.face_code','LIKE','%'.$request->face_code.'%')
        ->select(['pre_data.unit_id'])
        ->groupBy(['pre_data.unit_id'])
        ->orderBy('pre_data.unit_id','asc')
        ->get();

        $faces=DB::table('pre_data')
        ->join('faces','pre_data.face_code','=','faces.face_code')
        ->where('pre_data.brand_id','LIKE','%'.$request->brand_id.'%')
        ->where('pre_data.unit_id','LIKE','%'.$request->unit_id.'%')
        // ->where('pre_data.face_code','LIKE','%'.$request->face_code.'%')
        ->select(['pre_data.face_code','faces.face_item'])
        ->groupBy(['pre_data.face_code','faces.face_item'])
        ->orderBy('pre_data.face_code','asc')
        ->get();

        $hinbans=DB::table('pre_data')
        ->leftJoin('hinbans','hinbans.id','=','pre_data.hinban_id')
        ->where('pre_data.brand_id','LIKE','%'.$request->brand_id.'%')
        ->where('pre_data.unit_id','LIKE','%'.$request->unit_id.'%')
        ->where('pre_data.face_code','LIKE','%'.$request->face_code.'%')
        ->whereNull('hinbans.id')
        ->select(['pre_data.hinban_id','pre_data.year_code','pre_data.brand_id','pre_data.unit_id','pre_data.face_code'])
        ->get();


        $product=DB::table('pre_data')
        ->join('brands','pre_data.brand_id','=','brands.id')
        ->join('units','pre_data.unit_id','=','units.id')
        ->join('faces','pre_data.face_code','=','faces.face_code')
        ->leftJoin('hinbans','hinbans.id','=','pre_data.hinban_id')
        ->where('pre_data.brand_id','LIKE','%'.$request->brand_id.'%')
        ->where('pre_data.unit_id','LIKE','%'.$request->unit_id.'%')
        ->where('pre_data.face_code','LIKE','%'.$request->face_code.'%')
        ->where('pre_data.hinban_id','LIKE','%'.$request->hinban_id.'%')
        ->whereNull('hinbans.id')
        ->select(['pre_data.hinban_id','pre_data.year_code','pre_data.brand_id','brands.brand_name','pre_data.unit_id','pre_data.face_code','faces.face_item'])
        ->first();


        $cols=DB::table('cols')
        ->select(['id','col_name'])
        ->groupBy(['id','col_name'])
        ->orderBy('id','asc')
        ->get();

        $sizes=DB::table('sizes')
        ->select(['id','size_name'])
        ->groupBy(['id','size_name'])
        ->orderBy('id','asc')
        ->get();

        // dd($latest,$latest_sku1,$latest_vendor,$latest_sku2,$latest_sku3,$latest_sku4,$latest_sku5,$latest_sku6,$user,$vendors,$product,$units,$faces,$brands,$hinbans,$cols,$sizes);
        // dd($latest);
        return view('hinban.hinban_create2',compact('latest','latest_sku1','latest_vendor','user','vendors','product','units','faces','brands','hinbans','cols','sizes','latest_sku2','latest_sku3','latest_sku4','latest_sku5','latest_sku6'));
    }

    public function show($id)
    {
        $product = DB::table('hinbans')
            ->join('units','units.id','=','hinbans.unit_id')
            ->join('brands','brands.id','=','hinbans.brand_id')
            ->where('hinbans.id',$id)
            ->select('hinbans.id as hinban_id','hinbans.year_code','hinbans.brand_id','hinbans.unit_id','hinbans.hinban_name','hinbans.face_code','hinbans.unit_id','hinbans.hinban_info','hinbans.mix_rate','hinbans.season_code','hinbans.year_code','hinbans.shohin_gun','hinbans.kizoku_g','hinbans.seireki_unit','hinbans.kyotu_hinban','hinbans.vendor_id','hinbans.prod_code','hinbans.created_at','hinbans.updated_at')
            ->first();

        $skus = DB::table('skus')
            ->where('skus.hinban_id',$id)
            // ->where('skus.col_id','<>','99')
            // ->select(['skus.id','skus.hinban_id','skus.col_id','skus.size_id'])
            ->get();

        $sku1 = DB::table('skus')
        ->where('skus.hinban_id',$id)
        ->where('skus.seq',1)
        ->first();

        $sku2 = DB::table('skus')
        ->where('skus.hinban_id',$id)
        ->where('skus.seq',2)
        ->first();

        $sku3 = DB::table('skus')
        ->where('skus.hinban_id',$id)
        ->where('skus.seq',3)
        ->first();

        $sku4 = DB::table('skus')
        ->where('skus.hinban_id',$id)
        ->where('skus.seq',4)
        ->first();

        $sku5 = DB::table('skus')
        ->where('skus.hinban_id',$id)
        ->where('skus.seq',5)
        ->first();

        $sku6 = DB::table('skus')
        ->where('skus.hinban_id',$id)
        ->where('skus.seq',6)
        ->first();

        $ex_rate = DB::table('ex_rates')
            ->first();

        $cost_rate = DB::table('cost_rates')
            ->first();

        // dd($product,$skus,$ex_rate,$cost_rate);
        // dd($product,$sku1,$sku2,$sku3,$sku4,$sku5,$sku6);

            return view('hinban.hinban_show',compact('product','skus','ex_rate','cost_rate','sku1','sku2','sku3','sku4','sku5','sku6'));
    }

    public function store(Request $request)
    {
        // dd($request->file('image1') );

        $request->validate([
            'vendor_id2' => 'required',
            'hinban_id2' => 'required',
            'hinban_name' => 'required|max:100',
            'hinban_info' => 'nullable|max:500',
            'local_cur_price' => 'nullable|numeric',
            'image1' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'image2' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'image3' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'image4' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'image5' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'image6' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $hinban = $request['hinban_id2'];
        $sku1 = $request['sku_id1'];
        $col1 = $request['col_id1'];
        $size1 = $request['size_id1'];
        $sku2 = $request['sku_id2'];
        $col2 = $request['col_id2'];
        $size2 = $request['size_id2'];
        $sku3 = $request['sku_id3'];
        $col3 = $request['col_id3'];
        $size3 = $request['size_id3'];
        $sku4 = $request['sku_id4'];
        $col4 = $request['col_id4'];
        $size4 = $request['size_id4'];
        $sku5 = $request['sku_id5'];
        $col5 = $request['col_id5'];
        $size5 = $request['size_id5'];
        $sku6 = $request['sku_id6'];
        $col6 = $request['col_id6'];
        $size6 = $request['size_id6'];

        $sku_id1 = $hinban . $sku1;
        $sku_code1 = $hinban . $col1 .$size1;
        $sku_id2 = $hinban . $sku2;
        $sku_code2 = $hinban . $col2 .$size2;
        $sku_id3 = $hinban . $sku3;
        $sku_code3 = $hinban . $col3 .$size3;
        $sku_id4 = $hinban . $sku4;
        $sku_code4 = $hinban . $col4 .$size4;
        $sku_id5 = $hinban . $sku5;
        $sku_code5 = $hinban . $col5 .$size5;
        $sku_id6 = $hinban . $sku6;
        $sku_code6 = $hinban . $col6 .$size6;

        $data= DB::table('pre_data')
        ->join('brands','pre_data.brand_id','=','brands.id')
        ->join('units','pre_data.unit_id','=','units.id')
        ->where('hinban_id',$hinban)
        ->first();

        // dd($datas,$hinban,);

        $folderName='sku_images';

        if(!is_null($request->file('image1')) && ($col1 && $size1) ){
            $extension1 = $request->file('image1')->extension();
            $fileNameToStore1 = $sku_code1. '.' . $extension1;
            $resizedImage1 = InterventionImage::make($request->file('image1'))
            ->orientate()
            ->resize(400, 400,function($constraint){$constraint->aspectRatio();})->encode();
            Storage::put('public/sku_images/' . $fileNameToStore1, $resizedImage1 );
        }else{
            $fileNameToStore1 = '';
        };

        // dd($fileNameToStore1);

        if(!is_null($request->file('image2')) && ($col2 && $size2) ){
            $extension2 = $request->file('image2')->extension();
            $fileNameToStore2 = $sku_code2. '.' . $extension2;
            $resizedImage2 = InterventionImage::make($request->file('image2'))
            ->orientate()
            ->resize(400, 400,function($constraint){$constraint->aspectRatio();})->encode();
            Storage::put('public/sku_images/' . $fileNameToStore2, $resizedImage2 );
        }else{
            $fileNameToStore2 = '';
        };
        if(!is_null($request->file('image3')) && ($col3 && $size3) ){
            $extension3 = $request->file('image3')->extension();
            $fileNameToStore3 = $sku_code3. '.' . $extension3;
            $resizedImage3 = InterventionImage::make($request->file('image3'))
            ->orientate()
            ->resize(400, 400,function($constraint){$constraint->aspectRatio();})->encode();
            Storage::put('public/sku_images/' . $fileNameToStore3, $resizedImage3 );
        }else{
            $fileNameToStore3 = '';
        };
        if(!is_null($request->file('image4')) && ($col4 && $size4) ){
            $extension4 = $request->file('image4')->extension();
            $fileNameToStore4 = $sku_code4. '.' . $extension4;
            $resizedImage4 = InterventionImage::make($request->file('image4'))
            ->orientate()
            ->resize(400, 400,function($constraint){$constraint->aspectRatio();})->encode();
            Storage::put('public/sku_images/' . $fileNameToStore4, $resizedImage4 );
        }else{
            $fileNameToStore4 = '';
        };
        if(!is_null($request->file('image5')) && ($col5 && $size5) ){
            $extension5 = $request->file('image5')->extension();
            $fileNameToStore5 = $sku_code5. '.' . $extension5;
            $resizedImage5 = InterventionImage::make($request->file('image5'))
            ->orientate()
            ->resize(400, 400,function($constraint){$constraint->aspectRatio();})->encode();
            Storage::put('public/sku_images/' . $fileNameToStore5, $resizedImage5 );
        }else{
            $fileNameToStore5 = '';
        };
        if(!is_null($request->file('image6')) && ($col6 && $size6) ){
            $extension6 = $request->file('image6')->extension();
            $fileNameToStore6 = $sku_code6. '.' . $extension6;
            $resizedImage6 = InterventionImage::make($request->file('image6'))
            ->orientate()
            ->resize(400, 400,function($constraint){$constraint->aspectRatio();})->encode();
            Storage::put('public/sku_images/' . $fileNameToStore6, $resizedImage6 );
        }else{
            $fileNameToStore6 = '';
        };
        // dd($fileNameToStore1,$fileNameToStore2,$fileNameToStore3,$fileNameToStore4,$fileNameToStore5,$fileNameToStore6);

        DB::beginTransaction();

        try {
            $hinban = Hinban::create([
                'brand_id' => $data->brand_id,
                'unit_id' => $data->unit_id,
                'face_code' => $data->face_code,
                'id' => $request['hinban_id2'],
                'prod_code' =>$request['prod_code'],
                'hinban_name' => $request['hinban_name'],
                'hinban_info' => $request['hinban_info'],
                // 'local_cur_price' => $request['local_cur_price'],
                'mix_rate' => $request['mix_rate'],
                'season_code' => $data->season_id,
                'year_code' => $data->year_code,
                'shohin_gun' => $data->shohin_gun,
                'kizoku_g' => $data->kizoku_g,
                'seireki_unit' => $data->seireki_unit,
                'kyotu_hinban' => $data->kyotu_hinban,
                'vendor_id' => $request['vendor_id2'],
            ]);

            //  dd($fileNameToStore1,$fileNameToStore2,$fileNameToStore3,$fileNameToStore4,$fileNameToStore5,$fileNameToStore6);


            if($col1 && $size1){ {
                // dd($fileNameToStore1);
                Sku::create([
                    'id' => $sku_id1,
                    'seq' => $request['sku_id1'],
                    'hinban_id' => $request['hinban_id2'],
                    'sku_code' => $sku_code1,
                    'col_id' => $col1,
                    'size_id' => $size1,
                    'local_cur_price' => $request['local_cur_price'],
                    'length' => $request['length1'],
                    'width' => $request['width1'],
                    'sku_image' => $fileNameToStore1 ,
                    // 'sku_image' => "rrr" ,
                    ]);
                }
            }else{

                Sku::create([
                    'id' => $sku_id1,
                    'seq' => $request['sku_id1'],
                    'hinban_id' => $request['hinban_id2'],
                    'col_id' => $col1,
                    'size_id' => $size1,
                    'local_cur_price' => $request['local_cur_price'],
                    'length' => $request['length1'],
                    'width' => $request['width1'],
                    'sku_image' => $fileNameToStore1 ,
                ]);
            }

            if($col2 && $size2){ {
                // dd($fileNameToStore1,$fileNameToStore2,$fileNameToStore3,$fileNameToStore4,$fileNameToStore5,$fileNameToStore6);
                Sku::create([
                    'id' => $sku_id2,
                    'seq' => $request['sku_id2'],
                    'hinban_id' => $request['hinban_id2'],
                    'sku_code' => $sku_code2,
                    'col_id' => $col2,
                    'size_id' => $size2,
                    'local_cur_price' => $request['local_cur_price'],
                    'length' => $request['length2'],
                    'width' => $request['width2'],
                    'sku_image' => $fileNameToStore2,
                ]);
                }
            }else{
                Sku::create([
                    'id' => $sku_id2,
                    'seq' => $request['sku_id2'],
                    'hinban_id' => $request['hinban_id2'],
                    'col_id' => $col2,
                    'size_id' => $size2,
                    'local_cur_price' => $request['local_cur_price'],
                    'length' => $request['length2'],
                    'width' => $request['width2'],
                    'sku_image' => $fileNameToStore2 ,
                ]);
            }

            if($col3 && $size3){ {
                Sku::create([
                    'id' => $sku_id3,
                    'seq' => $request['sku_id3'],
                    'hinban_id' => $request['hinban_id2'],
                    'sku_code' => $sku_code3,
                    'col_id' => $col3,
                    'size_id' => $size3,
                    'local_cur_price' => $request['local_cur_price'],
                    'length' => $request['length3'],
                    'width' => $request['width3'],
                    'sku_image' => $fileNameToStore3 ,
                ]);
                }
            }else{
                Sku::create([
                    'id' => $sku_id3,
                    'seq' => $request['sku_id3'],
                    'hinban_id' => $request['hinban_id2'],
                    'col_id' => $col3,
                    'size_id' => $size3,
                    'local_cur_price' => $request['local_cur_price'],
                    'length' => $request['length3'],
                    'width' => $request['width3'],
                    'sku_image' => $fileNameToStore3 ,
                ]);
            }
            if($col4 && $size4){ {
                Sku::create([
                    'id' => $sku_id4,
                    'seq' => $request['sku_id4'],
                    'hinban_id' => $request['hinban_id2'],
                    'sku_code' => $sku_code4,
                    'col_id' => $col4,
                    'size_id' => $size4,
                    'local_cur_price' => $request['local_cur_price'],
                    'length' => $request['length4'],
                    'width' => $request['width4'],
                    'sku_image' => $fileNameToStore4 ,
                ]);
                }
            }else{
                Sku::create([
                    'id' => $sku_id4,
                    'seq' => $request['sku_id4'],
                    'hinban_id' => $request['hinban_id2'],
                    'col_id' => $col4,
                    'size_id' => $size4,
                    'local_cur_price' => $request['local_cur_price'],
                    'length' => $request['length4'],
                    'width' => $request['width4'],
                    'sku_image' =>  $fileNameToStore4 ,
                ]);
            }
            if($col5 && $size5){ {
            Sku::create([
                    'id' => $sku_id5,
                    'seq' => $request['sku_id5'],
                    'hinban_id' => $request['hinban_id2'],
                    'sku_code' => $sku_code5,
                    'col_id' => $col5,
                    'size_id' => $size5,
                    'local_cur_price' => $request['local_cur_price'],
                    'length' => $request['length5'],
                    'width' => $request['width5'],
                    'sku_image' => $fileNameToStore5 ,
                ]);
                }
            }else{
                Sku::create([
                    'id' => $sku_id5,
                    'seq' => $request['sku_id5'],
                    'hinban_id' => $request['hinban_id2'],
                    'col_id' => $col5,
                    'size_id' => $size5,
                    'local_cur_price' => $request['local_cur_price'],
                    'length' => $request['length5'],
                    'width' => $request['width5'],
                    'sku_image' => $fileNameToStore5 ,
                ]);
            }
            if($col6 && $size6){ {
                //   dd($fileNameToStore1,$fileNameToStore2,$fileNameToStore3,$fileNameToStore4,$fileNameToStore5,$fileNameToStore6);
                Sku::create([
                    'id' => $sku_id6,
                    'seq' => $request['sku_id6'],
                    'hinban_id' => $request['hinban_id2'],
                    'sku_code' => $sku_code6,
                    'col_id' => $col6,
                    'size_id' => $size6,
                    'local_cur_price' => $request['local_cur_price'],
                    'length' => $request['length6'],
                    'width' => $request['width6'],
                    'sku_image' => $fileNameToStore6 ,
                ]);
                }
            }else{
                // dd($fileNameToStore1,$fileNameToStore2,$fileNameToStore3,$fileNameToStore4,$fileNameToStore5,$fileNameToStore6);
                Sku::create([
                    'id' => $sku_id6,
                    'seq' => $request['sku_id6'],
                    'hinban_id' => $request['hinban_id2'],
                    'col_id' => $col6,
                    'size_id' => $size6,
                    'local_cur_price' => $request['local_cur_price'],
                    'length' => $request['length6'],
                    'width' => $request['width6'],
                    'sku_image' => $fileNameToStore6 ,
                ]);
            }
            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();
            Log::error('HinbanController@store: ' . $e->getMessage());
            return back()->withErrors(['message' => '品番の登録に失敗しました。']);
        }


        $users = User::Where('mailService', 1)
        ->distinct()
        // ->select('id')
        ->get()
        ->toArray();

        $touroku = Hinban::findOrFail($request['hinban_id2'])
        ->toArray();


        // foreach($users as $user){
        //     $user = $user['user'];
        //     SendStintMail::dispatch($touroku,$user);
        // }

        return to_route('hinban.hinban_index2')->with(['message'=>'品番が登録されました','status'=>'info']);
    }

    public function edit($id)
    {
        $prod = DB::table('hinbans')
        ->join('vendors', 'vendors.id', '=', 'hinbans.vendor_id')
        ->join('brands', 'brands.id', '=', 'hinbans.brand_id')
        ->join('units', 'units.id', '=', 'hinbans.unit_id')
        ->join('faces', 'faces.face_code', '=', 'hinbans.face_code')
        ->where('hinbans.id', $id)
        ->select(['hinbans.id as hinban_id', 'hinbans.brand_id', 'hinbans.unit_id','hinbans.mix_rate','hinbans.face_code', 'hinbans.hinban_name', 'hinbans.hinban_info', 'hinbans.mix_rate', 'hinbans.season_code', 'hinbans.year_code', 'hinbans.shohin_gun', 'hinbans.kizoku_g', 'hinbans.seireki_unit', 'hinbans.kyotu_hinban', 'hinbans.vendor_id', 'hinbans.prod_code','brands.brand_name','faces.face_item','vendors.vendor_name'])
        ->first();

        $prod_sku1 = DB::table('skus')
        ->where('skus.hinban_id', $id)
        ->where('skus.seq', 1)
        ->first();

        $prod_sku2 = DB::table('skus')
        ->where('skus.hinban_id', $id)
        ->where('skus.seq', 2)
        ->first();

        $prod_sku3 = DB::table('skus')
        ->where('skus.hinban_id', $id)
        ->where('skus.seq', 3)
        ->first();

        $prod_sku4 = DB::table('skus')
        ->where('skus.hinban_id', $id)
        ->where('skus.seq', 4)
        ->first();

        $prod_sku5 = DB::table('skus')
        ->where('skus.hinban_id', $id)
        ->where('skus.seq', 5)
        ->first();

        $prod_sku6 = DB::table('skus')
        ->where('skus.hinban_id', $id)
        ->where('skus.seq', 6)
        ->first();

        $vendors = DB::table('vendors')->get();

        $brands=DB::table('brands')
        ->select(['id','brand_name'])
        ->groupBy(['id','brand_name'])
        ->orderBy('id','asc')
        ->get();

        $units=DB::table('units')
        ->get();

        $faces=DB::table('faces')
        ->select(['face_code','face_item'])
        ->orderBy('face_code','asc')
        ->get();

        $cols=DB::table('cols')
        ->select(['id','col_name'])
        ->groupBy(['id','col_name'])
        ->orderBy('id','asc')
        ->get();

        $sizes=DB::table('sizes')
        ->select(['id','size_name'])
        ->groupBy(['id','size_name'])
        ->orderBy('id','asc')
        ->get();

        // dd($request,$latest,$units,$hinbans);
        return view('hinban.hinban_edit',compact('prod','prod_sku1','prod_sku2','prod_sku3','prod_sku4','prod_sku5','prod_sku6','vendors','brands','units','faces','cols','sizes'));
    }

    public function update(Request $request,$id)
    {
        $hinban = Hinban::findOrFail($id);

        $request->validate([
            'vendor_id' => 'required',
            'hinban_name' => 'required|max:100',
            'hinban_info' => 'nullable|max:500',
            'local_cur_price' => 'nullable|numeric',
            'image1' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'image2' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'image3' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'image4' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'image5' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'image6' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $hinban_id = $hinban->id;
        $sku1 = $request['sku_id1'];
        $col1 = $request['col_id1'];
        $size1 = $request['size_id1'];
        $sku2 = $request['sku_id2'];
        $col2 = $request['col_id2'];
        $size2 = $request['size_id2'];
        $sku3 = $request['sku_id3'];
        $col3 = $request['col_id3'];
        $size3 = $request['size_id3'];
        $sku4 = $request['sku_id4'];
        $col4 = $request['col_id4'];
        $size4 = $request['size_id4'];
        $sku5 = $request['sku_id5'];
        $col5 = $request['col_id5'];
        $size5 = $request['size_id5'];
        $sku6 = $request['sku_id6'];
        $col6 = $request['col_id6'];
        $size6 = $request['size_id6'];

        $sku_id1 = $hinban_id . $sku1;
        $sku_code1 = $hinban_id . $col1 .$size1;
        $sku_id2 = $hinban_id . $sku2;
        $sku_code2 = $hinban_id . $col2 .$size2;
        $sku_id3 = $hinban_id . $sku3;
        $sku_code3 = $hinban_id . $col3 .$size3;
        $sku_id4 = $hinban_id . $sku4;
        $sku_code4 = $hinban_id . $col4 .$size4;
        $sku_id5 = $hinban_id . $sku5;
        $sku_code5 = $hinban_id . $col5 .$size5;
        $sku_id6 = $hinban_id . $sku6;
        $sku_code6 = $hinban_id . $col6 .$size6;

        // dd($request->all(),$hinban_id,$sku_id1,$sku_code1,$sku_id2,$sku_code2,$sku_id3,$sku_code3,$sku_id4,$sku_code4,$sku_id5,$sku_code5,$sku_id6,$sku_code6);

        $folderName='sku_images';

        // 確認用で実際は不要
        $sku_images1=Sku::where('id', $sku_id1)->first();
        $sku_images2=Sku::where('id', $sku_id2)->first();
        $sku_images3=Sku::where('id', $sku_id3)->first();
        $sku_images4=Sku::where('id', $sku_id4)->first();
        $sku_images5=Sku::where('id', $sku_id5)->first();
        $sku_images6=Sku::where('id', $sku_id6)->first();
        $filePath1 = 'public/sku_images/' . $sku_images1->sku_image;
        $filePath2 = 'public/sku_images/' . $sku_images2->sku_image;
        $filePath3 = 'public/sku_images/' . $sku_images3->sku_image;
        $filePath4 = 'public/sku_images/' . $sku_images4->sku_image;
        $filePath5 = 'public/sku_images/' . $sku_images5->sku_image;
        $filePath6 = 'public/sku_images/' . $sku_images6->sku_image;
        dd($filePath1,$filePath2,$filePath3,$filePath4,$filePath5,$filePath6);

        // 画像のアップロードとリサイズ
        if(!is_null($request->file('image1')) && ($col1 && $size1) ){
            $extension1 = $request->file('image1')->extension();
            $fileNameToStore1 = $sku_code1. '.' . $extension1;
            $resizedImage1 = InterventionImage::make($request->file('image1'))
            ->orientate()
            ->resize(400, 400,function($constraint){$constraint->aspectRatio();})->encode();
            Storage::put('public/sku_images/' . $fileNameToStore1, $resizedImage1 );
        }else{
            $fileNameToStore1 = '';
        };

        if(!is_null($request->file('image2')) && ($col2 && $size2) ){
            $extension2 = $request->file('image2')->extension();
            $fileNameToStore2 = $sku_code2. '.' . $extension2;
            $resizedImage2 = InterventionImage::make($request->file('image2'))
            ->orientate()
            ->resize(400, 400,function($constraint){$constraint->aspectRatio();})->encode();
            Storage::put('public/sku_images/' . $fileNameToStore2, $resizedImage2 );
        }else{
            $fileNameToStore2 = '';
        };

        if(!is_null($request->file('image3')) && ($col3 && $size3) ){
            $extension3 = $request->file('image3')->extension();
            $fileNameToStore3 = $sku_code3. '.' . $extension3;
            $resizedImage3 = InterventionImage::make($request->file('image3'))
            ->orientate()
            ->resize(400, 400,function($constraint){$constraint->aspectRatio();})->encode();
            Storage::put('public/sku_images/' . $fileNameToStore3, $resizedImage3 );
        }else{
            $fileNameToStore3 = '';
        };

        if(!is_null($request->file('image4')) && ($col4 && $size4) ){
            $extension4 = $request->file('image4')->extension();
            $fileNameToStore4 = $sku_code4. '.' . $extension4;
            $resizedImage4 = InterventionImage::make($request->file('image4'))
            ->orientate()
            ->resize(400, 400,function($constraint){$constraint->aspectRatio();})->encode();
            Storage::put('public/sku_images/' . $fileNameToStore4, $resizedImage4 );
        }else{
            $fileNameToStore4 = '';
        };

        if(!is_null($request->file('image5')) && ($col5 && $size5) ){
            $extension5 = $request->file('image5')->extension();
            $fileNameToStore5 = $sku_code5. '.' . $extension5;
            $resizedImage5 = InterventionImage::make($request->file('image5'))
            ->orientate()
            ->resize(400, 400,function($constraint){$constraint->aspectRatio();})->encode();
            Storage::put('public/sku_images/' . $fileNameToStore5, $resizedImage5 );
        }else{
            $fileNameToStore5 = '';
        };

        if(!is_null($request->file('image6')) && ($col6 && $size6) ){
            $extension6 = $request->file('image6')->extension();
            $fileNameToStore6 = $sku_code6. '.' . $extension6;
            $resizedImage6 = InterventionImage::make($request->file('image6'))
            ->orientate()
            ->resize(400, 400,function($constraint){$constraint->aspectRatio();})->encode();
            Storage::put('public/sku_images/' . $fileNameToStore6, $resizedImage6 );
        }else{
            $fileNameToStore6 = '';
        };

        // dd($fileNameToStore1,$fileNameToStore2,$fileNameToStore3,$fileNameToStore4,$fileNameToStore5,$fileNameToStore6);

        $data= DB::table('pre_data')
        ->join('brands','pre_data.brand_id','=','brands.id')
        ->join('units','pre_data.unit_id','=','units.id')
        ->where('hinban_id',$hinban)
        ->first();

        // トランザクション開始
        // DB::beginTransaction();

        try {
            // 品番の更新
                // $hinban->brand_id = $request['brand_id'];
                // $hinban->unit_id = $request['unit_id'];
                // $hinban->face_code = $request['face_code'];
                $hinban->prod_code =$request['prod_code'];
                $hinban->hinban_name = $request['hinban_name'];
                $hinban->hinban_info = $request['hinban_info'];
                $hinban->mix_rate = $request['mix_rate'];
                // $hinban->season_code = $request['season_code'];
                // $hinban->year_code = $request['year_code'];
                // $hinban->shohin_gun = $request['shohin_gun'];
                // $hinban->kizoku_g = $request['kizoku_g'];
                // $hinban->seireki_unit = $request['seireki_unit'];
                // $hinban->kyotu_hinban = $request['kyotu_hinban'];
                $hinban->vendor_id = $request['vendor_id'];
                $hinban->save();

            // dd($hinban->toArray(),$fileNameToStore1,$fileNameToStore2,$fileNameToStore3,$fileNameToStore4,$fileNameToStore5,$fileNameToStore6);

            // SKUの更新
            if($col1 && $size1 && ($fileNameToStore1)){ {
                $sku=Sku::where('id', $sku_id1)->first();
                $sku->seq = $sku1;
                $sku->hinban_id = $hinban_id;
                $sku->sku_code = $sku_code1;
                $sku->col_id = $col1;
                $sku->size_id = $size1;
                $sku->local_cur_price = $request['local_cur_price'];
                $sku->length = $request['length1'];
                $sku->width = $request['width1'];
                $sku->sku_image = $fileNameToStore1 ;
                $sku->save();
                }
            }else{
                $sku=Sku::where('id', $sku_id1)->first();
                $sku->seq = $sku1;
                $sku->hinban_id = $hinban_id;
                $sku->sku_code = $sku_code1;
                $sku->col_id = $col1;
                $sku->size_id = $size1;
                $sku->local_cur_price = $request['local_cur_price'];
                $sku->length = $request['length1'];
                $sku->width = $request['width1'];
                $sku->save();
            }
            if($col2 && $size2 && ($fileNameToStore2)){ {
                $sku=Sku::where('id', $sku_id2)->first();
                $sku->seq = $sku2;
                $sku->hinban_id = $hinban_id;
                $sku->sku_code = $sku_code2;
                $sku->col_id = $col2;
                $sku->size_id = $size2;
                $sku->local_cur_price = $request['local_cur_price'];
                $sku->length = $request['length2'];
                $sku->width = $request['width2'];
                $sku->sku_image = $fileNameToStore2;
                $sku->save();
                }
            }else{
                $sku=Sku::where('id', $sku_id2)->first();
                $sku->seq = $sku2;
                $sku->hinban_id = $hinban_id;
                $sku->col_id = $col2;
                $sku->size_id = $size2;
                $sku->local_cur_price = $request['local_cur_price'];
                $sku->length = $request['length2'];
                $sku->width = $request['width2'];
                $sku->save();
            }
            if($col3 && $size3 && ($fileNameToStore3)){ {
                $sku=Sku::where('id', $sku_id3)->first();
                $sku->seq = $sku3;
                $sku->hinban_id = $hinban_id;
                $sku->sku_code = $sku_code3;
                $sku->col_id = $col3;
                $sku->size_id = $size3;
                $sku->local_cur_price = $request['local_cur_price'];
                $sku->length = $request['length3'];
                $sku->width = $request['width3'];
                $sku->sku_image = $fileNameToStore3 ;
                $sku->save();
                }
            }else{
                $sku=Sku::where('id', $sku_id3)->first();
                $sku->seq = $sku3;
                $sku->hinban_id = $hinban_id;
                $sku->col_id = $col3;
                $sku->size_id = $size3;
                $sku->local_cur_price = $request['local_cur_price'];
                $sku->length = $request['length3'];
                $sku->width = $request['width3'];
                $sku->save();
            }
            if($col4 && $size4 && ($fileNameToStore4)){ {
                $sku=Sku::where('id', $sku_id4)->first();
                $sku->seq = $sku4;
                $sku->hinban_id = $hinban_id;
                $sku->sku_code = $sku_code4;
                $sku->col_id = $col4;
                $sku->size_id = $size4;
                $sku->local_cur_price = $request['local_cur_price'];
                $sku->length = $request['length4'];
                $sku->width = $request['width4'];
                $sku->sku_image = $fileNameToStore4 ;
                $sku->save();
                }
            }else{
                $sku=Sku::where('id', $sku_id4)->first();
                $sku->seq = $sku4;
                $sku->hinban_id = $hinban_id;
                $sku->col_id = $col4;
                $sku->size_id = $size4;
                $sku->local_cur_price = $request['local_cur_price'];
                $sku->length = $request['length4'];
                $sku->width = $request['width4'];
                $sku->save();
            }
            if($col5 && $size5 && ($fileNameToStore5)){ {
                $sku=Sku::where('id', $sku_id5)->first();
                $sku->seq = $sku5;
                $sku->hinban_id = $hinban_id;
                $sku->sku_code = $sku_code5;
                $sku->col_id = $col5;
                $sku->size_id = $size5;
                $sku->local_cur_price = $request['local_cur_price'];
                $sku->length = $request['length5'];
                $sku->width = $request['width5'];
                $sku->sku_image = $fileNameToStore5 ;
                $sku->save();
                }
            }else{
                $sku=Sku::where('id', $sku_id5)->first();
                $sku->seq = $sku5;
                $sku->hinban_id = $hinban_id;
                $sku->col_id = $col5;
                $sku->size_id = $size5;
                $sku->local_cur_price = $request['local_cur_price'];
                $sku->length = $request['length5'];
                $sku->width = $request['width5'];
                $sku->save();
            }
            if($col6 && $size6 && ($fileNameToStore6)){ {
                $sku=Sku::where('id', $sku_id6)->first();
                $sku->seq = $sku6;
                $sku->hinban_id = $hinban_id;
                $sku->sku_code = $sku_code6;
                $sku->col_id = $col6;
                $sku->size_id = $size6;
                $sku->local_cur_price = $request['local_cur_price'];
                $sku->length = $request['length6'];
                $sku->width = $request['width6'];
                $sku->sku_image = $fileNameToStore6 ;
                $sku->save();
                }
            }else{
                $sku=Sku::where('id', $sku_id6)->first();
                $sku->seq = $sku6;
                $sku->hinban_id = $hinban_id;
                $sku->col_id = $col6;
                $sku->size_id = $size6;
                $sku->local_cur_price = $request['local_cur_price'];
                $sku->length = $request['length6'];
                $sku->width = $request['width6'];
                $sku->save();
            }


            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();
            Log::error('HinbanController@store: ' . $e->getMessage());
            return back()->withErrors(['message' => '品番の登録に失敗しました。']);
        }

        // ユーザーにメールを送信
        // $users = User::Where('mailService', 1)
        // ->distinct()
        // // ->select('id')
        // ->get()
        // ->toArray();

        // $touroku = Hinban::findOrFail($request['hinban_id2'])
        // ->toArray();


        // foreach($users as $user){
        //     $user = $user['user'];
        //     SendStintMail::dispatch($touroku,$user);
        // }

        return to_route('hinban_show',['id'=>$hinban_id])->with(['message'=>'品番情報が更新されました','status'=>'info']);

    }


    public function destroy($id)
    {
        //
    }

    public function destroy_one($id)
    {
        $hinban = Hinban::findOrFail($id);
        $hinban->delete();


        return to_route('hinban.hinban_index2')->with(['message'=>'削除されました','status'=>'alert']);
    }


}
