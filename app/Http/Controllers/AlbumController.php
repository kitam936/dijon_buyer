<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Album;
use App\Models\User;
use App\Models\Event;
use App\Http\Requests\UploadImageRequest;
use App\Services\ImageService;
use Illuminate\Support\Facades\Storage;

class AlbumController extends Controller
{

    public function index()
    {
        //
    }

    public function album_index(Request $request, $id)
    {
        $login_user = Auth::id();
        $event = DB::table('events')
        ->leftjoin('albums','events.id','=','albums.event_id')
        ->where('events.id',($id))
        ->select('events.id as evt_id','events.event_name','albums.filename','albums.created_at')
        ->first();
        $albums = DB::table('albums')
        ->join('events','events.id','=','albums.event_id')
        ->join('users','users.id','=','albums.user_id')
        ->where('events.id',($id))
        ->select('events.id as evt_id','albums.id as album_id','events.event_name','albums.user_id','users.name','users.role_id','albums.filename','albums.created_at')
        ->orderBy('albums.updated_at', 'desc')
        ->paginate(20);
        // $resv_id = $request->resv_id;
        // dd($resv_id);
        return view('events.album.index',compact('albums','event','login_user'));
    }


    public function create()
    {
        return view('events.album.create');
    }

    public function album_create($id)
    {
        $event = DB::table('events')
        ->where('events.id',($id))
        ->select('events.id as evt_id','events.event_name')
        ->first();

        return view('events.album.create',compact('event'));
    }


    public function store(Request $request)
    {
        $event_id = $request->evt_id2;
        $imageFiles = $request->file('files');
        if(!is_null($imageFiles)){
            foreach($imageFiles as $imageFile){
                $fileNameToStore = ImageService::upload($imageFile,'album');
                Album::create([
                    'user_id'=>Auth::id(),
                    'event_id'=>$request->evt_id2,
                    'filename'=>$fileNameToStore,
                ]);
            }
        }

        return to_route('album_index',['event'=>$event_id])->with(['message'=>'画像情報を登録しました','status'=>'info']);
    }



    public function show(string $id)
    {
        //
    }

    public function album_show(string $id)
    {
        $login_user = User::findorfail(Auth::id());
        $album = DB::table('albums')
        ->where('albums.id',($id))
        ->select('albums.id as album_id','albums.filename','albums.event_id','albums.user_id')
        ->first();

        return view('events.album.show',compact('album','login_user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function album_edit($id)
    {
        $login_user = User::findorfail(Auth::id());
        $album = DB::table('albums')
        ->where('albums.id',($id))
        ->select('albums.id as album_id','albums.filename','albums.event_id','albums.user_id')
        ->first();

        return view('events.album.edit',compact('album','login_user'));
    }


    public function update(Request $request, $id)
    {
        //
    }


    public function destroy($id)
    {

        $album = Album::findOrFail($id);
        $event_id = $album->event_id;
        $filePath = 'public/album/' . $album->filename;

        if(Storage::exists($filePath)){
            Storage::delete($filePath);
        }

        Album::findOrFail($id)->delete();

        return to_route('album_index',['event'=>$event_id])->with(['message'=>'画像を削除しました',
        'status'=>'alert']);
    }
}
