<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Stint;
use App\Models\User;
use App\Models\Area;
use App\Models\Status;
use Carbon\Carbon;
use InterventionImage;
use App\Http\Requests\StoreStintRequest;
use App\Http\Requests\UpdateStintRequest;
use App\Jobs\SendStintMail;
use App\Jobs\SendTestMail;
use App\Models\Circuit;
use App\Models\Favorite;
use Illuminate\Mail\SendQueuedMailable;

class StintController extends Controller
{
    public function my_stint_list(Request $request)
    {
        $stints0 = DB::table('stints')
        ->join('my_engines','my_engines.id','=','stints.my_engine_id')
        ->join('engines','engines.id','=','my_engines.engine_id')
        ->join('my_karts','my_karts.id','=','stints.my_kart_id')
        ->join('makers','makers.id','=','my_karts.maker_id')
        ->join('my_tires','my_tires.id','=','stints.my_tire_id')
        ->join('tires','tires.id','=','my_tires.tire_id')
        ->join('circuits','circuits.id','=','stints.cir_id')
        ->join('areas','areas.id','=','circuits.area_id')
        ->where('stints.user_id',Auth::id())
        ->where('circuits.id','LIKE','%'.($request->cir_id).'%')
        ->where('makers.id','LIKE','%'.($request->kart_id).'%')
        ->where('tires.id','LIKE','%'.($request->tire_id).'%')
        ->where('engines.id','LIKE','%'.($request->engine_id).'%')
        ->where('stints.temp','LIKE','%'.($request->temp_id).'%')
        ->where('stints.road_temp','LIKE','%'.($request->road_temp_id).'%')
        ->where('stints.humidity','LIKE','%'.($request->humi_id).'%')
        ->where('stints.dry_wet','LIKE','%'.($request->dry_wet).'%')
        ->whereDate('stints.start_date', 'LIKE','%'.$request['from_date'].'%')
        ->select('stints.id as stint_id','stints.user_id','stints.start_date','circuits.cir_name','areas.area_name','stints.best_time',
        'stints.laps','stints.max_rev','stints.min_rev','makers.maker_name','engines.engine_name','tires.tire_name');
        // ->orderBy('stints.start_date','desc')
        // ->paginate(50);

        if(($request->sort == 0) || ($request->sort == 1))
        {
            $stints=$stints0->orderBy('stints.start_date','desc')
            ->paginate(50);
        }
        if(($request->sort == 2) )
        {
            $stints=$stints0->orderBy('stints.best_time')
            ->paginate(50);
        }

        $circuits = DB::table('circuits')->get();

        $karts = DB::table('makers')
        ->orderBy('makers.sort_order')->get();

        $tires = DB::table('tires')
        ->orderBy('tires.sort_order')->get();

        $engines = DB::table('engines')
        ->orderBy('engines.sort_order')
        ->get();

        $temps = DB::table('temps')
        ->where('temps.id','>',0)
        ->where('temps.id','<',7)
        ->get();

        $road_temps = DB::table('roadtemps')
        ->where('roadtemps.id','>',0)
        ->where('roadtemps.id','<',13)
        ->get();

        $humis = DB::table('humidities')
        ->get();


        $num_of_laps = DB::table('stints')
        ->leftjoin('my_karts','my_karts.id','=','stints.my_kart_id')
        ->leftjoin('makers','makers.id','=','my_karts.maker_id')
        ->leftjoin('my_tires','my_tires.id','=','stints.my_tire_id')
        ->leftjoin('tires','tires.id','=','my_tires.tire_id')
        ->leftjoin('my_engines','my_engines.id','=','stints.my_engine_id')
        ->leftjoin('engines','engines.id','=','my_engines.engine_id')
        ->where('stints.user_id',Auth::id())
        ->where('stints.cir_id','LIKE','%'.($request->cir_id).'%')
        ->where('makers.id','LIKE','%'.($request->kart_id).'%')
        ->where('tires.id','LIKE','%'.($request->tire_id).'%')
        ->where('engines.id','LIKE','%'.($request->engine_id).'%')
        ->where('stints.temp','LIKE','%'.($request->temp_id).'%')
        ->where('stints.road_temp','LIKE','%'.($request->road_temp_id).'%')
        ->where('stints.humidity','LIKE','%'.($request->humi_id).'%')
        ->where('stints.dry_wet','LIKE','%'.($request->dry_wet).'%')
        ->selectRaw('SUM(stints.laps) as laps')
        ->selectRaw('COUNT(stints.id) as number_of_laps')
        ->get();

        // dd($road_temps);
        // dd($request->dry_wet,$stints,$circuits,$engines,$karts,$tires,$temps,$road_temps,$humis);
        return view('stints.my_stint_list',compact('stints','circuits','karts','tires','engines','num_of_laps','temps','road_temps','humis'));
    }

    public function my_stint_show($id)
    {
        $stint_id = $id;
        $stint = DB::table('stints')
        ->join('users','users.id','=','stints.user_id')
        ->join('my_engines','my_engines.id','=','stints.my_engine_id')
        ->join('engines','engines.id','=','my_engines.engine_id')
        ->join('my_karts','my_karts.id','=','stints.my_kart_id')
        ->join('makers','makers.id','=','my_karts.maker_id')
        ->join('my_tires','my_tires.id','=','stints.my_tire_id')
        ->join('tires','tires.id','=','my_tires.tire_id')
        ->join('circuits','circuits.id','=','stints.cir_id')
        ->join('areas','areas.id','=','circuits.area_id')
        ->leftjoin('temps','temps.id','=','stints.temp')
        ->leftjoin('roadtemps','roadtemps.id','=','stints.road_temp')
        ->leftjoin('tiretemps','tiretemps.id','=','stints.tire_temp')
        ->leftjoin('humidities','humidities.id','=','stints.humidity')
        ->where('stints.id' ,$id)
        ->where('stints.user_id',Auth::id())
        ->select('stints.id','users.name','circuits.cir_name','stints.start_date','stints.dry_wet','stints.road_temp',
                'roadtemps.roadtemp_range','temps.temp_range','stints.temp','stints.tire_temp','tiretemps.tiretemp_range',
                'stints.atm_pressure','stints.humidity','humidities.humi_range','stints.my_kart_id','makers.id','my_karts.maker_id',
                'makers.maker_name','stints.my_engine_id','my_engines.engine_id','engines.engine_name','my_karts.model_year',
                'stints.my_tire_id','my_tires.tire_id','tires.tire_name','stints.laps','stints.best_time',
                'stints.max_rev','stints.min_rev','stints.fr_tread','stints.re_tread','stints.fr_sprocket',
                'stints.re_sprocket','stints.stabilizer','stints.tire_pres','stints.tire_age','stints.cab_hi',
                'stints.cab_lo','stints.stint_info','stints.photo1','stints.photo2','stints.photo3','stints.filename')
        // 'stints.laps','stints.max_rev','stints.min_rev')

        // ->select('reservations.id as resv_id','reservations.event_id','events.start_date','events.end_date','events.event_name',
        // 'events.place','reservations.secondary_category_id','secondary_categories.secondary_name','reservations.sub_category_id',
        // 'sub_categories.sub_name','reservations.number','reservations.real_name','reservations.real_name_kana','reservations.resv_info',
        // 'reservations.number_of_sub','reservations.number_of_main','events.main_fee','events.sub_fee')
        ->first();


        // dd($stint);
        return view('stints.my_stint_show',compact('stint','stint_id'));
    }

    public function stint_create()
    {
        $latest = Stint::where('user_id',Auth::id())->latest()->first();

        $user = User::findOrFail(Auth::id());

        $cirs = DB::table('circuits')->get();

        $karts = DB::table('my_karts')
        ->join('makers','makers.id','=','my_karts.maker_id')
        ->where('my_karts.user_id',Auth::id())
        ->select('my_karts.id as mykart_id','makers.maker_name','my_karts.purchase_date','my_karts.model_year')
        ->orderBy('my_karts.id','desc')
        ->get();

        $tires = DB::table('my_tires')
        ->join('tires','tires.id','=','my_tires.tire_id')
        ->where('my_tires.user_id',Auth::id())
        ->select('my_tires.id as mytire_id','tires.tire_name','my_tires.purchase_date')
        ->orderBy('mytire_id','desc')
        ->get();

        $engines = DB::table('my_engines')
        ->join('engines','engines.id','=','my_engines.engine_id')
        ->where('my_engines.user_id',Auth::id())
        ->select('my_engines.id as myengine_id','engines.engine_name','my_engines.purchase_date')
        ->orderBy('my_engines.id','desc')
        ->get();

        $temps = DB::table('temps')
        ->where('temps.id','>',0)
        ->where('temps.id','<',8)
        ->get();

        $road_temps = DB::table('roadtemps')
        ->get();

        $tire_temps = DB::table('tiretemps')
        ->get();

        $humis = DB::table('humidities')
        ->get();

        // dd($tires);
        // dd($latest_stint,$engines,$karts,$tires,$cirs,$user);
        return view('stints.stint_create',compact('latest','tire_temps','temps','road_temps','humis','cirs','karts','tires','engines','user'));
    }

    public function stint_create_2()
    {
        // $latest = Stint::where('user_id',Auth::id())->latest()->first();

        $latest = DB::table('stints')
        ->join('users','users.id','=','stints.user_id')
        ->join('my_engines','my_engines.id','=','stints.my_engine_id')
        ->join('engines','engines.id','=','my_engines.engine_id')
        ->join('my_karts','my_karts.id','=','stints.my_kart_id')
        ->join('makers','makers.id','=','my_karts.maker_id')
        ->join('my_tires','my_tires.id','=','stints.my_tire_id')
        ->join('tires','tires.id','=','my_tires.tire_id')
        ->join('circuits','circuits.id','=','stints.cir_id')
        ->join('areas','areas.id','=','circuits.area_id')
        ->leftjoin('temps','temps.id','=','stints.temp')
        ->leftjoin('roadtemps','roadtemps.id','=','stints.road_temp')
        ->leftjoin('tiretemps','tiretemps.id','=','stints.tire_temp')
        ->leftjoin('humidities','humidities.id','=','stints.humidity')
        ->where('stints.user_id',Auth::id())
        ->select('stints.id','users.name','stints.cir_id','circuits.cir_name','stints.start_date','stints.dry_wet','stints.road_temp',
                'roadtemps.roadtemp_range','temps.temp_range','stints.temp','stints.tire_temp','tiretemps.tiretemp_range',
                'stints.atm_pressure','stints.humidity','humidities.humi_range','stints.my_kart_id','makers.id','my_karts.maker_id',
                'makers.maker_name','stints.my_engine_id','my_engines.purchase_date as my_tire_date','my_engines.purchase_date as my_engine_date','engines.engine_name','my_karts.model_year',
                'stints.my_tire_id','my_tires.tire_id','my_tires.tire_id','tires.tire_name','stints.laps','stints.best_time',
                'stints.max_rev','stints.min_rev','stints.fr_tread','stints.re_tread','stints.fr_sprocket',
                'stints.re_sprocket','stints.stabilizer','stints.tire_pres','stints.tire_age','stints.cab_hi',
                'stints.cab_lo','stints.photo1','stints.photo2','stints.photo3','stints.created_at')
        ->latest()->orderBy('stints.created_at', 'DESC')->first();

        $user = User::findOrFail(Auth::id());

        $cirs = DB::table('circuits')->get();

        $karts = DB::table('my_karts')
        ->join('makers','makers.id','=','my_karts.maker_id')
        ->where('my_karts.user_id',Auth::id())
        ->select('my_karts.id as mykart_id','my_karts.maker_id','makers.maker_name','my_karts.model_year',)
        ->orderBy('mykart_id','desc')
        ->get();

        $tires = DB::table('my_tires')
        ->join('tires','tires.id','=','my_tires.tire_id')
        ->where('my_tires.user_id',Auth::id())
        ->select('my_tires.id as mytire_id','my_tires.tire_id','tires.tire_name','my_tires.purchase_date',)
        ->orderBy('mytire_id','desc')
        ->get();

        $engines = DB::table('my_engines')
        ->join('engines','engines.id','=','my_engines.engine_id')
        ->where('my_engines.user_id',Auth::id())
        ->select('my_engines.id as myengine_id','my_engines.engine_id','engines.engine_maker_name','my_engines.purchase_date','engines.engine_name')
        ->orderBy('myengine_id','desc')
        ->get();

        $temps = DB::table('temps')
        ->where('temps.id','>',0)
        ->where('temps.id','<',7)
        ->get();

        $road_temps = DB::table('roadtemps')
        ->get();

        $tire_temps = DB::table('tiretemps')
        ->get();

        $humis = DB::table('humidities')
        ->get();

        // dd($road_temps);
        // dd($latest,$engines,$karts,$tires,$cirs,$user);
        return view('stints.stint_create_2',compact('latest','tire_temps','temps','road_temps','humis','cirs','karts','tires','engines','user'));
    }

    public function stint_store(StoreStintRequest $request)
    {

        $start = $request['start_date'] . " " . $request['start_time'];
        $startDate = Carbon::createFromFormat(
        'Y-m-d H:i', $start );

        $circuit = DB::table('circuits')
        ->where('circuits.id',$request['cir_id'])
        ->first();

       // $pastlaps= DB::table('stints')
        // ->where('stints.user_id',Auth::id())
        // ->where('stints.my_tire_id',$request['tire_id'])
        // ->selectRaw('SUM(stints.laps) as laps')
        // ->first();

        $pastlaps = DB::table('stints')
        ->where('stints.user_id',Auth::id())
        ->where('stints.my_tire_id',$request['tire_id'])
        ->select('stints.tire_age','stints.created_at')
        ->latest()->orderBy('stints.created_at', 'DESC')->first();

        // 過去ラップ数がないとき対応
        if(!is_null($pastlaps)){
            $pluslaps=$pastlaps->tire_age;
        }else{
            $pluslaps=0;
        };

        $folderName='stint';
        if(!is_null($request->file('image1'))){
            $fileName1 = uniqid(rand().'_');
            $extension1 = $request->file('image1')->extension();
            $fileNameToStore1 = $fileName1. '.' . $extension1;
            $resizedImage1 = InterventionImage::make($request->file('image1'))->resize(400, 400,function($constraint){$constraint->aspectRatio();})->encode();
            Storage::put('public/stint/' . $fileNameToStore1, $resizedImage1 );
        }else{
            $fileNameToStore1 = '';
        };

        if(!is_null($request->file('image2'))){
            $fileName2 = uniqid(rand().'_');
            $extension2 = $request->file('image2')->extension();
            $fileNameToStore2 = $fileName2. '.' . $extension2;
            $resizedImage2 = InterventionImage::make($request->file('image2'))->resize(400, 400,function($constraint){$constraint->aspectRatio();})->encode();
            Storage::put('public/stint/' . $fileNameToStore2, $resizedImage2 );
        }else{
            $fileNameToStore2 = '';
        };
        if(!is_null($request->file('image3'))){
            $fileName3 = uniqid(rand().'_');
            $extension3 = $request->file('image3')->extension();
            $fileNameToStore3 = $fileName3. '.' . $extension3;
            $resizedImage3 = InterventionImage::make($request->file('image3'))->resize(400, 400,function($constraint){$constraint->aspectRatio();})->encode();
            Storage::put('public/stint/' . $fileNameToStore3, $resizedImage3 );
        }else{
            $fileNameToStore3 = '';
        };
    //    dd($startDate,$pastlaps,$request,$fileNameToStore1,$fileNameToStore2,$fileNameToStore3);

    // mail test
        // $users = Favorite::with('user:id,name,email')
        // ->where('cir_id',$request['cir_id'])
        // ->distinct()
        // ->select('user_id')
        // ->get()
        // ->toArray();

        // $circuit2 = Circuit::findOrFail($request['cir_id'])
        // ->toArray();


        // foreach($users as $user){
        //     $user = $user['user'];
        //     SendStintMail::dispatch($circuit2,$user);
        // }

        // dd($user,$circuit);

        Stint::create([
            'user_id' => Auth::id(),
            'my_kart_id' => $request['kart_id'],
            'my_tire_id' => $request['tire_id'],
            'my_engine_id' => $request['engine_id'],
            'cir_id' => $request['cir_id'],
            'start_date' => $startDate,
            'laps' => $request['laps'],
            'best_time' => $request['upper_of_time']+$request['bottom_of_time']/100,
            'max_rev' => $request['max_rev'],
            'min_rev' => $request['min_rev'],
            'fr_tread' => $request['fr_tread'],
            're_tread' => $request['re_tread'],
            'fr_sprocket' => $request['fr_sprocket'],
            're_sprocket' => $request['re_sprocket'],
            'stabilizer' => $request['stabilizer'],
            'tire_pres' => $request['tire_pres']/100,
            'tire_temp' => $request['tire_temp_id'],
            // 'tire_age' => $request['laps']+$pastlaps->tire_age,　
            'tire_age' => $request['laps']+$pluslaps,
            'distance' => $request['laps']*$circuit->length,
            'cab_hi' => $request['cab_hi'],
            'cab_lo' => $request['cab_lo'],
            'dry_wet' => $request['dry/wet'],
            'temp' => $request['temp_id'],
            'humidity' => $request['humi_id'],
            'atm_pressure' => $request['atm_pressure'],
            'road_temp' => $request['road_temp_id'],
            'stint_info' => $request['stint_info'],
            'photo1' => $fileNameToStore1,
            'photo2' => $fileNameToStore2,
            'photo3' => $fileNameToStore3,
        ]);

        $users = Favorite::with('user:id,name,email')
        ->where('cir_id',$request['cir_id'])
        ->distinct()
        ->select('user_id')
        ->get()
        ->toArray();

        $circuit2 = Circuit::findOrFail($request['cir_id'])
        ->toArray();


        foreach($users as $user){
            $user = $user['user'];
            SendStintMail::dispatch($circuit2,$user);
        }

        return to_route('my_stint')->with(['message'=>'Stintが登録されました','status'=>'info']);

    }
    public function stint_list(Request $request)
    {
        $stints0 = DB::table('stints')
        ->join('users','users.id','=','stints.user_id')
        ->join('my_engines','my_engines.id','=','stints.my_engine_id')
        ->join('engines','engines.id','=','my_engines.engine_id')
        ->join('my_karts','my_karts.id','=','stints.my_kart_id')
        ->join('makers','makers.id','=','my_karts.maker_id')
        ->join('my_tires','my_tires.id','=','stints.my_tire_id')
        ->join('tires','tires.id','=','my_tires.tire_id')
        ->join('circuits','circuits.id','=','stints.cir_id')
        ->join('areas','areas.id','=','circuits.area_id')
        ->where('circuits.id','LIKE','%'.($request->cir_id).'%')
        ->where('makers.id','LIKE','%'.($request->kart_id).'%')
        ->where('tires.id','LIKE','%'.($request->tire_id).'%')
        ->where('engines.id','LIKE','%'.($request->engine_id).'%')
        ->where('stints.temp','LIKE','%'.($request->temp_id).'%')
        ->where('stints.road_temp','LIKE','%'.($request->road_temp_id).'%')
        ->where('stints.humidity','LIKE','%'.($request->humi_id).'%')
        ->where('stints.dry_wet','LIKE','%'.($request->dry_wet).'%')
        // ->whereDate('stints.start_date', 'LIKE','%'.$request['from_date'].'%')
        ->select('stints.id as stint_id','stints.user_id','users.name','stints.start_date','circuits.cir_name','areas.area_name','stints.best_time',
        'stints.laps','stints.max_rev','stints.min_rev','stints.cir_id');
         // ->orderBy('stints.best_time')
        // ->paginate(50);

        if(($request->sort == 0) || ($request->sort == 1))
        {
            $stints=$stints0->orderBy('stints.start_date','desc')
            ->paginate(50);
        }
        if(($request->sort == 2) )
        {
            $stints=$stints0->orderBy('stints.best_time')
            ->paginate(50);
        }


        $circuits = DB::table('circuits')->get();

        $karts = DB::table('makers')
        ->orderBy('makers.sort_order')->get();

        $tires = DB::table('tires')
        ->orderBy('tires.sort_order')->get();

        $engines = DB::table('engines')
        ->orderBy('engines.sort_order')
        ->get();

        $temps = DB::table('temps')
        ->where('temps.id','>',0)
        ->where('temps.id','<',7)
        ->get();

        $road_temps = DB::table('roadtemps')
        ->where('roadtemps.id','>',0)
        ->where('roadtemps.id','<',13)
        ->get();

        $humis = DB::table('humidities')
        ->get();


        // dd($stints,$circuits,$engines,$karts,$tires,$temps,$road_temps,$humis);
        return view('stints.stint_list',compact('stints','circuits','karts','tires','engines','temps','road_temps','humis'));
    }

    public function stint_show($id)
    {
        $stint_id = $id;
        $login_user = Auth::id();
        $stint = DB::table('stints')
        ->join('users','users.id','=','stints.user_id')
        ->join('my_engines','my_engines.id','=','stints.my_engine_id')
        ->join('engines','engines.id','=','my_engines.engine_id')
        ->join('my_karts','my_karts.id','=','stints.my_kart_id')
        ->join('makers','makers.id','=','my_karts.maker_id')
        ->join('my_tires','my_tires.id','=','stints.my_tire_id')
        ->join('tires','tires.id','=','my_tires.tire_id')
        ->join('circuits','circuits.id','=','stints.cir_id')
        ->join('areas','areas.id','=','circuits.area_id')
        ->leftjoin('temps','temps.id','=','stints.temp')
        ->leftjoin('roadtemps','roadtemps.id','=','stints.road_temp')
        ->leftjoin('tiretemps','tiretemps.id','=','stints.tire_temp')
        ->leftjoin('humidities','humidities.id','=','stints.humidity')
        ->where('stints.id' ,$id)
        ->select('stints.id','users.name','stints.cir_id','circuits.cir_name','stints.start_date','stints.dry_wet','stints.road_temp',
                'roadtemps.roadtemp_range','temps.temp_range','stints.temp','stints.tire_temp','tiretemps.tiretemp_range',
                'stints.atm_pressure','stints.humidity','humidities.humi_range','stints.my_kart_id','makers.id','my_karts.maker_id',
                'makers.maker_name','stints.my_engine_id','my_engines.engine_id','engines.engine_name','my_karts.model_year',
                'stints.my_tire_id','my_tires.tire_id','tires.tire_name','stints.laps','stints.best_time',
                'stints.max_rev','stints.min_rev','stints.fr_tread','stints.re_tread','stints.fr_sprocket',
                'stints.re_sprocket','stints.stabilizer','stints.tire_pres','stints.tire_age','stints.cab_hi',
                'stints.cab_lo','stints.stint_info','stints.photo1','stints.photo2','stints.photo3','stints.filename')
        ->first();



        // dd($stint);
        return view('stints.stint_show',compact('stint','stint_id','login_user'));
    }

    public function stint_edit($id)
    {
        // $latest = Stint::where('user_id',Auth::id())->latest()->first();

        $stint = DB::table('stints')
        ->join('users','users.id','=','stints.user_id')
        ->join('my_engines','my_engines.id','=','stints.my_engine_id')
        ->join('engines','engines.id','=','my_engines.engine_id')
        ->join('my_karts','my_karts.id','=','stints.my_kart_id')
        ->join('makers','makers.id','=','my_karts.maker_id')
        ->join('my_tires','my_tires.id','=','stints.my_tire_id')
        ->join('tires','tires.id','=','my_tires.tire_id')
        ->join('circuits','circuits.id','=','stints.cir_id')
        ->join('areas','areas.id','=','circuits.area_id')
        ->leftjoin('temps','temps.id','=','stints.temp')
        ->leftjoin('roadtemps','roadtemps.id','=','stints.road_temp')
        ->leftjoin('tiretemps','tiretemps.id','=','stints.tire_temp')
        ->leftjoin('humidities','humidities.id','=','stints.humidity')
        ->where('stints.id' ,$id)
        ->select('stints.id as stint_id','users.name','stints.cir_id','circuits.cir_name','stints.start_date','stints.dry_wet','stints.road_temp',
                'roadtemps.roadtemp_range','temps.temp_range','stints.temp','stints.tire_temp','tiretemps.tiretemp_range',
                'stints.atm_pressure','stints.humidity','humidities.humi_range','stints.my_kart_id','makers.id','my_karts.maker_id',
                'makers.maker_name','stints.my_engine_id','my_engines.purchase_date as my_tire_date','my_engines.purchase_date as my_engine_date','engines.engine_name','my_karts.model_year',
                'stints.my_tire_id','my_tires.tire_id','my_tires.tire_id','tires.tire_name','stints.laps','stints.best_time',
                'stints.max_rev','stints.min_rev','stints.fr_tread','stints.re_tread','stints.fr_sprocket',
                'stints.re_sprocket','stints.stabilizer','stints.tire_pres','stints.tire_age','stints.cab_hi',
                'stints.cab_lo','stints.stint_info','stints.photo1','stints.photo2','stints.photo3','stints.created_at')
        ->first();

        $user = User::findOrFail(Auth::id());

        $cirs = DB::table('circuits')->get();

        $karts = DB::table('my_karts')
        ->join('makers','makers.id','=','my_karts.maker_id')
        ->where('my_karts.user_id',Auth::id())
        ->orderBy('my_karts.id','desc')
        ->get();

        $tires = DB::table('my_tires')
        ->join('tires','tires.id','=','my_tires.tire_id')
        ->where('my_tires.user_id',Auth::id())
        ->orderBy('my_tires.id','desc')
        ->get();

        $engines = DB::table('my_engines')
        ->join('engines','engines.id','=','my_engines.engine_id')
        ->where('my_engines.user_id',Auth::id())
        ->orderBy('my_engines.id','desc')
        ->get();

        $temps = DB::table('temps')
        ->where('temps.id','>',0)
        ->where('temps.id','<',7)
        ->get();

        $road_temps = DB::table('roadtemps')
        ->get();

        $tire_temps = DB::table('tiretemps')
        ->get();

        $humis = DB::table('humidities')
        ->get();

        // dd($road_temps);
        // dd($latest,$engines,$karts,$tires,$cirs,$user);
        return view('stints.stint_edit',compact('stint','tire_temps','temps','road_temps','humis','cirs','karts','tires','engines','user'));
    }

    public function stint_update(UpdateStintRequest $request, $id)
    {
        $stint=Stint::findOrFail($id);
        $stint2=Stint::findOrFail($id);
        $start = $request['start_date'] . " " . $request['start_time'];
        $startDate = Carbon::createFromFormat(
        'Y-m-d H:i', $start );
        // dd($start,$startDate);

        $circuit = DB::table('circuits')
        ->where('circuits.id',$request['cir_id'])
        ->first();

        $pastlaps= DB::table('stints')
        ->where('stints.user_id',Auth::id())
        ->where('stints.my_tire_id',$request['tire_id'])
        // ->select('stints.my_tire_id','laps')
        ->selectRaw('SUM(stints.laps) as laps')
        ->first();


        $filePath1 = 'public/stint/' . $stint->photo1;
        if(!empty($request->image1) && (Storage::exists($filePath1))){
            Storage::delete($filePath1);
            // dd($filePath1,$request->image1);
        }
        $filePath2 = 'public/stint/' . $stint->photo2;
        if(!empty($request->image2) && (Storage::exists($filePath2))){
            Storage::delete($filePath2);
            // dd($filePath2,$request->image2);
        }

        $filePath3 = 'public/stint/' . $stint->photo3;
        if(!empty($request->image3) && (Storage::exists($filePath3))){
            Storage::delete($filePath3);
            // dd($filePath3,$request->image3);
        }

        if(!is_null($request->file('image1'))){
            $fileName1 = uniqid(rand().'_');
            $extension1 = $request->file('image1')->extension();
            $fileNameToStore1 = $fileName1. '.' . $extension1;
            $resizedImage1 = InterventionImage::make($request->file('image1'))->resize(400, 400,function($constraint){$constraint->aspectRatio();})->encode();
            Storage::put('public/stint/' . $fileNameToStore1, $resizedImage1 );
        }else{
            $fileNameToStore1 = $stint->photo1;
        };

        if(!is_null($request->file('image2'))){
            $fileName2 = uniqid(rand().'_');
            $extension2 = $request->file('image2')->extension();
            $fileNameToStore2 = $fileName2. '.' . $extension2;
            $resizedImage2 = InterventionImage::make($request->file('image2'))->resize(400, 400,function($constraint){$constraint->aspectRatio();})->encode();
            Storage::put('public/stint/' . $fileNameToStore2, $resizedImage2 );
        }else{
            $fileNameToStore2 = $stint->photo2;
        };
        if(!is_null($request->file('image3'))){
            $fileName3 = uniqid(rand().'_');
            $extension3 = $request->file('image3')->extension();
            $fileNameToStore3 = $fileName3. '.' . $extension3;
            $resizedImage3 = InterventionImage::make($request->file('image3'))->resize(400, 400,function($constraint){$constraint->aspectRatio();})->encode();
            Storage::put('public/stint/' . $fileNameToStore3, $resizedImage3 );
        }else{
            $fileNameToStore3 = $stint->photo3;
        };

        $stint->user_id = Auth::id();
        $stint->my_kart_id = $request['kart_id'];
        $stint->my_tire_id = $request['tire_id'];
        $stint->my_engine_id = $request['engine_id'];
        $stint->cir_id = $request['cir_id'];
        $stint->start_date = $startDate;
        $stint->laps = $request['laps'];
        $stint->best_time = $request['upper_of_time']+$request['bottom_of_time']/100;
        $stint->max_rev = $request['max_rev'];
        $stint->min_rev = $request['min_rev'];
        $stint->fr_tread = $request['fr_tread'];
        $stint->re_tread = $request['re_tread'];
        $stint->fr_sprocket = $request['fr_sprocket'];
        $stint->re_sprocket = $request['re_sprocket'];
        $stint->stabilizer = $request['stabilizer'];
        $stint->tire_pres = $request['tire_pres']/100;
        $stint->tire_temp = $request['tire_temp_id'];
        // $stint->tire_age = $request['laps']+$pastlaps->laps-$stint2->laps;
        $stint->tire_age = $request['laps']+$stint2->tire_age-$stint2->laps;
        $stint->distance = $request['laps']*$circuit->length;
        $stint->cab_hi = $request['cab_hi'];
        $stint->cab_lo = $request['cab_lo'];
        $stint->dry_wet = $request['dry/wet'];
        $stint->temp = $request['temp_id'];
        $stint->humidity = $request['humi_id'];
        $stint->atm_pressure = $request['atm_pressure'];
        $stint->road_temp = $request['road_temp_id'];
        $stint->stint_info = $request['stint_info'];
        $stint->photo1 = $fileNameToStore1;
        $stint->photo2 = $fileNameToStore2;
        $stint->photo3 = $fileNameToStore3;
        // dd($request->stint_info);

        $stint->save();

        return to_route('my_stint')->with(['message'=>'Stintが更新されました','status'=>'info']);
    }


    public function stint_destroy(Request $request,$id)
    {
        $stint=Stint::findOrFail($id);
        $filePath1 = 'public/stint/' . $stint->photo1;
        // if(!empty($request->photo1) && (Storage::exists($filePath1))){
        if((Storage::exists($filePath1))){
            Storage::delete($filePath1);
            // dd($filePath1,$request->photo1);
        }
        $filePath2 = 'public/stint/' . $stint->photo2;
        // if(!empty($request->photo2) && (Storage::exists($filePath2))){
        if((Storage::exists($filePath2))){
            Storage::delete($filePath2);
            // dd($filePath2,$request->photo2);
        }

        $filePath3 = 'public/stint/' . $stint->photo3;
        // if(!empty($request->photo3) && (Storage::exists($filePath3))){
        if((Storage::exists($filePath3))){
            Storage::delete($filePath3);
            // dd($filePath3,$request->photo3);
        }
        Stint::findOrFail($id)->delete();

        return to_route('my_stint')->with(['message'=>'Stintが削除されました','status'=>'alert']);
    }


}
