<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Http\Requests\UploadImageRequest;
use App\Services\ImageService;
use App\Jobs\SendCommentMail;
use App\Jobs\SendReportMail;
use InterventionImage;
use App\Models\Report;
use App\Models\Shop;
use App\Models\User;
use App\Models\Company;
use App\Models\Area;
use App\Models\Comment;
use App\Jobs\SendTestMail;

class ReportController extends Controller
{

    public function report_list(Request $request)
    {
        // $reports=DB::table('reports')
        // ->join('shops','shops.id','=','reports.shop_id')
        // ->join('companies','companies.id','=','shops.company_id')
        // ->join('areas','areas.id','=','shops.area_id')
        // ->select(['reports.id','shops.company_id','companies.co_name','reports.shop_id','shops.shop_name','areas.area_name','shops.shop_info','reports.report','reports.image1','reports.created_at','reports.updated_at'])
        // ->where('shops.company_id','>','1000')
        // ->where('shops.company_id','<','7000')
        // ->where('shops.is_selling','=',1)
        // ->where('shops.company_id','LIKE','%'.($request->co_id).'%')
        // ->where('shops.area_id','LIKE','%'.($request->ar_id).'%')
        // ->where('shops.shop_name','LIKE','%'.($request->sh_name).'%')
        // ->orderBy('updated_at','desc')
        // ->paginate(100);

        $reports=DB::table('reports')
        ->join('shops','shops.id','=','reports.shop_id')
        ->join('companies','companies.id','=','shops.company_id')
        ->join('areas','areas.id','=','shops.area_id')
        ->leftjoin('comments','comments.report_id','=','reports.id')
        // ->select(['reports.id','shops.company_id','companies.co_name','reports.shop_id','shops.shop_name','areas.area_name','shops.shop_info','reports.report','reports.image1','reports.created_at','reports.updated_at'])
        ->where('shops.company_id','>','1000')
        ->where('shops.company_id','<','7000')
        // ->where('shops.is_selling','=',1)
        ->where('shops.company_id','LIKE','%'.($request->co_id).'%')
        ->where('shops.area_id','LIKE','%'.($request->ar_id).'%')
        ->where('shops.shop_name','LIKE','%'.($request->sh_name).'%')
        ->groupBy('reports.id','shops.company_id','companies.co_name','reports.shop_id','shops.shop_name','areas.area_name','shops.shop_info','reports.report','reports.image1','reports.created_at','reports.updated_at')
        ->selectRaw('reports.id,shops.company_id,companies.co_name,reports.shop_id,shops.shop_name,areas.area_name,shops.shop_info,reports.report,reports.image1,reports.created_at,reports.updated_at,count(comments.id) as comment_count')
        ->orderBy('reports.updated_at','desc')
        ->paginate(100);



        $companies = Company::with('shop')
        ->whereHas('shop',function($q){$q->where('is_selling','=',1);})
        ->where('id','>',1000)
        ->where('id','<',7000)->get();

        $areas = DB::table('areas')
        ->select(['areas.id','areas.area_name'])
        ->get();

        $shops = DB::table('shops')
        ->join('areas','areas.id','=','shops.area_id')
        ->join('companies','companies.id','=','shops.company_id')
        ->select('shops.id','shops.shop_name','shops.company_id','shops.area_id','areas.area_name','companies.co_name')
        ->where('shops.company_id','>','1000')
        ->where('shops.company_id','<','7000')
        // ->where('shops.is_selling','=',1)
        ->where('shops.company_id','LIKE','%'.($request->co_id).'%')
        ->where('shops.area_id','LIKE','%'.($request->ar_id).'%')
        ->where('shops.shop_name','LIKE','%'.($request->sh_name).'%')
        ->paginate(100);
        // dd($reports);

        return view('shop.report',compact('reports','areas','shops','companies'));
    }

    public function report_detail($id)
    {
        $report=DB::table('reports')
        ->join('shops','shops.id','=','reports.shop_id')
        ->join('companies','companies.id','=','shops.company_id')
        ->join('areas','areas.id','=','shops.area_id')
        ->join('users','users.id','=','reports.user_id')
        ->select(['reports.id','reports.user_id','users.name','shops.company_id','companies.co_name','reports.shop_id','shops.shop_name','areas.area_name','shops.shop_info','reports.report','reports.image1','reports.image2','reports.image3','reports.image4','reports.created_at'])
        ->where('reports.id',$id)
        ->first();

        $comment_exist = DB::table('comments')
        ->where('comments.report_id',$id)
        ->exists();

        $images = DB::table('reports')
        ->where('reports.id',$id)
        ->orderBy('reports.updated_at', 'desc')
        ->get();

        $login_user=Auth::id();

        $comments=DB::table('comments')
        ->join('users','users.id','=','comments.user_id')
        ->where('comments.report_id',$id)
        ->select(['comments.id','comments.updated_at','users.name','comments.comment'])
        ->orderBy('updated_at','desc')
        ->get();

        // dd($report,$images,$comments,$comment_exist);

        return view('shop.report_detail',compact('comment_exist','report','images','login_user','comments'));
    }

    public function report_create($id)
    {
        $shops = DB::table('shops')
        ->join('companies','companies.id','=','shops.company_id')
        ->where('shops.id',$id)
        ->select(['shops.company_id','companies.co_name','shops.id','shops.shop_name'])
        ->get();

        return view('shop.report_create',compact('shops'));
    }

    public function report_create2(Request $request)
    {
        $shops = DB::table('shops')
        ->join('areas','areas.id','=','shops.area_id')
        ->join('companies','companies.id','=','shops.company_id')
        ->where('shops.company_id','LIKE','%'.($request->co_id).'%')
        ->where('shops.area_id','LIKE','%'.($request->area_id).'%')
        ->where('shops.company_id','>',1000)
        ->where('shops.company_id','<',7000)
        ->select(['shops.company_id','companies.co_name','shops.id','shops.shop_name'])
        ->get();

        $companies = Company::with('shop')
        ->whereHas('shop',function($q){$q->where('is_selling','=',1);})
        ->where('id','>',1000)
        ->where('id','<',7000)->get();

        $areas = DB::table('areas')
        ->select(['areas.id','areas.area_name'])
        ->get();

        return view('shop.report_create2',compact('shops','companies','areas'));
    }

    public function report_store_rs(UploadImageRequest $request)
    {
        // dd($request->sh_id,$request->image1->extension(),$request->comment,$request->image2,$request->image3,$request->image4);

        $login_user = User::findOrFail(Auth::id());
        $folderName='reports';
        if(!is_null($request->file('image1'))){
            $fileName1 = uniqid(rand().'_');
            $extension1 = $request->file('image1')->extension();
            $fileNameToStore1 = $fileName1. '.' . $extension1;
            $resizedImage1 = InterventionImage::make($request->file('image1'))->resize(400, 400,function($constraint){$constraint->aspectRatio();})->encode();
            Storage::put('public/reports/' . $fileNameToStore1, $resizedImage1 );
        }else{
            $fileNameToStore1 = '';
        };

        if(!is_null($request->file('image2'))){
            $fileName2 = uniqid(rand().'_');
            $extension2 = $request->file('image2')->extension();
            $fileNameToStore2 = $fileName2. '.' . $extension2;
            $resizedImage2 = InterventionImage::make($request->file('image2'))->resize(400, 400,function($constraint){$constraint->aspectRatio();})->encode();
            Storage::put('public/reports/' . $fileNameToStore2, $resizedImage2 );
        }else{
            $fileNameToStore2 = '';
        };
        if(!is_null($request->file('image3'))){
            $fileName3 = uniqid(rand().'_');
            $extension3 = $request->file('image3')->extension();
            $fileNameToStore3 = $fileName3. '.' . $extension3;
            $resizedImage3 = InterventionImage::make($request->file('image3'))->resize(400, 400,function($constraint){$constraint->aspectRatio();})->encode();
            Storage::put('public/reports/' . $fileNameToStore3, $resizedImage3 );
        }else{
            $fileNameToStore3 = '';
        };


        if(!is_null($request->file('image4'))){
            $fileName4 = uniqid(rand().'_');
            $extension4 = $request->file('image4')->extension();
            $fileNameToStore4 = $fileName4. '.' . $extension4;
            $resizedImage4 = InterventionImage::make($request->file('image4'))->resize(400, 400,function($constraint){$constraint->aspectRatio();})->encode();
            Storage::put('public/reports/' . $fileNameToStore4, $resizedImage4 );
        }else{
            $fileNameToStore4 = '';
        };

        // $users = User::Where('mailService','=',1)
        // ->where('shop_id','<>',106)
        // ->where('shop_id','<',4000)
        // ->orwhere('shop_id','>',5000)
        // ->get();

        // dd($users);

        Report::create([
            'user_id' => $login_user->id,
            'shop_id' => $request->sh_id2,
            'image1' => $fileNameToStore1,
            'image2' => $fileNameToStore2,
            'image3' => $fileNameToStore3,
            'image4' => $fileNameToStore4,
            'report' => $request->report,
        ]);

        // ここでメール送信

        $users = User::Where('mailService','=',1)
        ->where('shop_id','<>',106)
        ->where('shop_id','<',4000)
        ->orwhere('shop_id','>',5000)
        ->get()
        ->toArray();

        // $report_info = Shop::findOrFail($request->sh_id2)
        // ->toArray();

        $report_info = Report::Where('reports.shop_id',$request->sh_id2)
        ->join('shops','shops.id','reports.shop_id')
        ->join('users','users.id','reports.user_id')
        ->where('reports.user_id',Auth::id())
        ->select('reports.id as report_id','users.name','users.email','reports.shop_id','shops.shop_name')
        ->first()
        ->toArray();

        // dd($users,$report_info);

        foreach($users as $user){

            // dd($user,$report_info);
            SendReportMail::dispatch($report_info,$user);
        }

        return to_route('report_list')->with(['message'=>'Reportが登録されました','status'=>'info']);
    }

    public function report_store_rs2(UploadImageRequest $request )
    {

        // dd($request->sh_id,$request->image1->extension(),$request->comment,$request->image2,$request->image3,$request->image4);

        $login_user = User::findOrFail(Auth::id());
        $folderName='reports';
        if(!is_null($request->file('image1'))){
            $fileName1 = uniqid(rand().'_');
            $extension1 = $request->file('image1')->extension();
            $fileNameToStore1 = $fileName1. '.' . $extension1;
            $resizedImage1 = InterventionImage::make($request->file('image1'))->resize(400, 400,function($constraint){$constraint->aspectRatio();})->encode();
            Storage::put('public/reports/' . $fileNameToStore1, $resizedImage1 );
        }else{
            $fileNameToStore1 = '';
        };

        if(!is_null($request->file('image2'))){
            $fileName2 = uniqid(rand().'_');
            $extension2 = $request->file('image2')->extension();
            $fileNameToStore2 = $fileName2. '.' . $extension2;
            $resizedImage2 = InterventionImage::make($request->file('image2'))->resize(400, 400,function($constraint){$constraint->aspectRatio();})->encode();
            Storage::put('public/reports/' . $fileNameToStore2, $resizedImage2 );
        }else{
            $fileNameToStore2 = '';
        };
        if(!is_null($request->file('image3'))){
            $fileName3 = uniqid(rand().'_');
            $extension3 = $request->file('image3')->extension();
            $fileNameToStore3 = $fileName3. '.' . $extension3;
            $resizedImage3 = InterventionImage::make($request->file('image3'))->resize(400, 400,function($constraint){$constraint->aspectRatio();})->encode();
            Storage::put('public/reports/' . $fileNameToStore3, $resizedImage3 );
        }else{
            $fileNameToStore3 = '';
        };


        if(!is_null($request->file('image4'))){
            $fileName4 = uniqid(rand().'_');
            $extension4 = $request->file('image4')->extension();
            $fileNameToStore4 = $fileName4. '.' . $extension4;
            $resizedImage4 = InterventionImage::make($request->file('image4'))->resize(400, 400,function($constraint){$constraint->aspectRatio();})->encode();
            Storage::put('public/reports/' . $fileNameToStore4, $resizedImage4 );
        }else{
            $fileNameToStore4 = '';
        };

        // $users = User::Where('mailService','=',1)
        // ->where('shop_id','<>',106)
        // ->where('shop_id','<',4000)
        // ->orwhere('shop_id','>',5000)
        // ->get();

        // dd($users);

        // dd($request,$request->co_id,$request->sh_id,$request->report,$request->image1);
        Report::create([
            'user_id' => $login_user->id,
            'shop_id' => $request->sh_id,
            'image1' => $fileNameToStore1,
            'image2' => $fileNameToStore2,
            'image3' => $fileNameToStore3,
            'image4' => $fileNameToStore4,
            'report' => $request->report,
        ]);


        $users = User::Where('mailService','=',1)
        ->where('shop_id','<>',106)
        ->where('shop_id','<',4000)
        ->orwhere('shop_id','>',5000)
        ->get()->toArray();

        // $report_info = Shop::findOrFail($request->sh_id)
        // ->toArray();

        $report_info = Report::Where('reports.shop_id',$request->sh_id)
        ->join('shops','shops.id','reports.shop_id')
        ->join('users','users.id','reports.user_id')
        ->where('reports.user_id',Auth::id())
        ->select('reports.id as report_id','users.name','users.email','reports.shop_id','shops.shop_name')
        ->first()
        ->toArray();

        // dd($users,$report_info);

        foreach($users as $user){

            // dd($user,$report_info);
            SendReportMail::dispatch($report_info,$user);

        }


        return to_route('report_list')->with(['message'=>'Reportが登録されました','status'=>'info']);
    }


    public function report_edit($id)
    {
        $login_user = User::findOrFail(Auth::id());
        $report=DB::table('reports')
        ->join('shops','shops.id','=','reports.shop_id')
        ->join('companies','companies.id','=','shops.company_id')
        ->select(['reports.id','reports.user_id','shops.company_id','companies.co_name','reports.shop_id','shops.shop_name','reports.report','reports.image1','reports.image2','reports.image3','reports.image4','reports.created_at'])
        ->where('reports.id',$id)
        ->first();
        // dd($report);
        return view('shop.report_edit',compact('report','login_user'));
    }


    public function report_update_rs(Request $request, $id)
    {
        $report=Report::findOrFail($id);

        $user = User::findOrFail(Auth::id());

        $filePath1 = 'public/reports/' . $report->image1;
        if(!empty($request->image1) && (Storage::exists($filePath1))){
            Storage::delete($filePath1);
            // dd($filePath1,$request->image1);
        }

        $filePath2 = 'public/reports/' . $report->image2;
        if(!empty($request->image2) && (Storage::exists($filePath2))){
            Storage::delete($filePath2);
            // dd($filePath1,$request->image1);
        }

        $filePath3 = 'public/reports/' . $report->image3;
        if(!empty($request->image3) && (Storage::exists($filePath3))){
            Storage::delete($filePath3);
            // dd($filePath1,$request->image1);
        }

        $filePath4 = 'public/reports/' . $report->image4;
        if(!empty($request->image4) && (Storage::exists($filePath4))){
            Storage::delete($filePath4);
            // dd($filePath1,$request->image1);
        }

        if(!is_null($request->file('image1'))){
            $fileName1 = uniqid(rand().'_');
            $extension1 = $request->file('image1')->extension();
            $fileNameToStore1 = $fileName1. '.' . $extension1;
            $resizedImage1 = InterventionImage::make($request->file('image1'))->resize(400, 400,function($constraint){$constraint->aspectRatio();})->encode();
            Storage::put('public/reports/' . $fileNameToStore1, $resizedImage1 );
        }else{
            $fileNameToStore1 = $report->image1;
        };

        if(!is_null($request->file('image2'))){
            $fileName2 = uniqid(rand().'_');
            $extension2 = $request->file('image2')->extension();
            $fileNameToStore2 = $fileName2. '.' . $extension2;
            $resizedImage2 = InterventionImage::make($request->file('image2'))->resize(400, 400,function($constraint){$constraint->aspectRatio();})->encode();
            Storage::put('public/reports/' . $fileNameToStore2, $resizedImage2 );
        }else{
            $fileNameToStore2 = $report->image2;
        };
        if(!is_null($request->file('image3'))){
            $fileName3 = uniqid(rand().'_');
            $extension3 = $request->file('image3')->extension();
            $fileNameToStore3 = $fileName3. '.' . $extension3;
            $resizedImage3 = InterventionImage::make($request->file('image3'))->resize(400, 400,function($constraint){$constraint->aspectRatio();})->encode();
            Storage::put('public/reports/' . $fileNameToStore3, $resizedImage3 );
        }else{
            $fileNameToStore3 = $report->image3;
        };


        if(!is_null($request->file('image4'))){
            $fileName4 = uniqid(rand().'_');
            $extension4 = $request->file('image4')->extension();
            $fileNameToStore4 = $fileName4. '.' . $extension4;
            $resizedImage4 = InterventionImage::make($request->file('image4'))->resize(400, 400,function($constraint){$constraint->aspectRatio();})->encode();
            Storage::put('public/reports/' . $fileNameToStore4, $resizedImage4 );
        }else{
            $fileNameToStore4 = $report->image4;
        };

        $report->image1 = $fileNameToStore1;
        $report->image2 = $fileNameToStore2;
        $report->image3 = $fileNameToStore3;
        $report->image4 = $fileNameToStore4;
        $report->report = $request->report;

        // dd($request,$request->comment,$request->report,$fileNameToStore1,$fileNameToStore2,$fileNameToStore3,$fileNameToStore4);

        $report->save();

        return to_route('report_list')->with(['message'=>'Reportが更新されました','status'=>'info']);
    }


    public function report_destroy($id)
    {

        $report = Report::findOrFail($id);

        $filePath1 = 'public/reports/' . $report->image1;
        if((Storage::exists($filePath1))){
            Storage::delete($filePath1);
            // dd($filePath1,$request->photo1);
        }

        $filePath2 = 'public/reports/' . $report->image2;
        if((Storage::exists($filePath2))){
            Storage::delete($filePath2);
            // dd($filePath1,$request->photo1);
        }

        $filePath3 = 'public/reports/' . $report->image3;
        if((Storage::exists($filePath3))){
            Storage::delete($filePath3);
            // dd($filePath1,$request->photo1);
        }

        $filePath4 = 'public/reports/' . $report->image4;
        if((Storage::exists($filePath4))){
            Storage::delete($filePath4);
            // dd($filePath1,$request->photo1);
        }

        Report::findOrFail($id)->delete();

        return to_route('report_list')->with(['message'=>'削除されました','status'=>'alert']);

    }

    public function image1_show(string $id)
    {
       $report = DB::table('reports')
        ->where('reports.id',($id))
        ->first();

        return view('shop.report_image1_show',compact('report'));
    }

    public function image2_show(string $id)
    {
       $report = DB::table('reports')
        ->where('reports.id',($id))
        ->first();

        return view('shop.report_image2_show',compact('report'));
    }

    public function image3_show(string $id)
    {
       $report = DB::table('reports')
        ->where('reports.id',($id))
        ->first();

        return view('shop.report_image3_show',compact('report'));
    }

    public function image4_show(string $id)
    {
       $report = DB::table('reports')
        ->where('reports.id',($id))
        ->first();

        return view('shop.report_image4_show',compact('report'));
    }

}
