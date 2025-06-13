<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Vendor;
use Throwable;
use Illuminate\Support\Facades\Log;


class VendorController extends Controller
{
    public function index()
    {
        $vendors = DB::table('vendors')
        ->orderBy('vendors.id', 'asc')
        ->paginate(50);


        // dd($request,$companies,$areas,$vendors);

        return view('vendor.index',compact('vendors'));
    }


    public function create()
    {
       return view('vendor.create');
    }


    public function vendor_store(Request $request)
    {


        $request->validate([
            'vendor_id'=> ['required'],
            'vendor_name' => ['required', 'string', 'max:255'],
            'vendor_info' => ['max:255'],
        ]);

        // dd($request->all());

        try{
            DB::transaction(function()use($request){
                Vendor::create([
                    'id' => $request->vendor_id,
                    'vendor_name' => $request->vendor_name,
                    'vendor_info' => $request->vendor_info,
                ]);

            },2);
        }catch(Throwable $e){
            Log::error($e);
            throw $e;
        }

        return to_route('vendor_index')->with(['message'=>'登録されました','status'=>'info']);
    }


    public function show($id)
    {

        $vendor = DB::table('vendors')
        ->where('vendors.id',$id)
        ->select(['vendors.id','vendors.vendor_name','vendors.vendor_info'])
        ->first();
        // dd($vendors,$reports);

        return view('vendor.show',compact('vendor'));
        // return view('User.shop.show',compact('vendors'));
    }


    public function edit($id)
    {
        $vendor = DB::table('vendors')
        ->where('vendors.id',$id)
        ->select(['vendors.id','vendors.vendor_name','vendors.vendor_info'])
        ->first();
        // dd($vendors,$reports);

        return view('vendor.edit',compact('vendor'));
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'vendor_id'=> ['required'],
            'vendor_name' => ['required', 'string', 'max:255'],
            'vendor_info' => ['max:255'],
        ]);

        // dd($request->all());

        $vendor=Vendor::findOrFail($id);

        try{
            $vendor->id = $request->vendor_id;
            $vendor->vendor_name = $request->vendor_name;
            $vendor->vendor_info = $request->vendor_info;
            $vendor->save();
        }catch(Throwable $e){
            Log::error($e);
            throw $e;
        }

        return to_route('vendor_index')->with(['message'=>'更新されました','status'=>'info']);
    }



    public function destroy($id)
    {

        Vendor::findOrFail($id)->delete();

        return to_route('vendor_index')->with(['message'=>'仕入先が削除されました','status'=>'alert']);
    }


}
