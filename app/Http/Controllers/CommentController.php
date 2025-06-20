<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Hinban;
use App\Models\User;
use Illuminate\Http\Request;
use App\Jobs\SendResvOrderMail;
use App\Jobs\SendResvCommentMail;
use App\Jobs\SendCreateHinbanMail;

use function Laravel\Prompts\select;

class CommentController extends Controller
{

    public function comment_create($id)
    {
        $login_user = User::findOrFail(Auth::id());

        $hinban = Hinban::findOrFail($id);

        // dd($login_user,$comment);

        return view('comment.comment_create',compact('login_user','hinban'));
    }


    public function comment_store(Request $request)
    {
        $login_user = User::findOrFail(Auth::id());
        $hinban_id = $request->hinban_id2;

        // dd($request->report_id2,$login_user->id,$request->report_id2,$request->comment);
        Comment::create([
            'user_id' => $login_user->id,
            'hinban_id' => $request->hinban_id2,
            'comment' => $request->comment,
        ]);

        // ここでメール送信
        // $users = User::Where('mailService','=',1)
        // ->get()
        // ->toArray();

        // $comment_info = Hinban::Where('hinbans.id','=',$request->hinban_id2)
        // ->distinct()
        // ->select('hinbans.id','hinban_name')
        // ->get()
        // ->toArray();

        // $comment_info = $comment_info[0];

        // foreach($users as $user){
        //     SendCommentMail::dispatch($comment_info,$user);
        // }

        return to_route('hinban_show',['id'=>$hinban_id])->with(['message'=>'コメントが登録されました','status'=>'info']);
    }


    public function comment_detail($id)
    {
        $comment=DB::table('comments')
        ->join('users','users.id','=','comments.user_id')
        ->join('hinbans','hinbans.id','=','comments.hinban_id')
        ->select(['comments.id','comments.hinban_id','comments.user_id','users.name','comments.comment','comments.created_at'])
        ->where('comments.id',$id)
        ->first();

        $login_user = User::findOrFail(Auth::id());

        // dd($comment,$login_user);

        return view('comment.comment_detail',compact('comment','login_user'));
    }


    public function comment_edit($id)
    {
        $comment=DB::table('comments')
        ->join('users','users.id','=','comments.user_id')
        ->join('hinbans','hinbans.id','=','comments.hinban_id')
        ->select(['comments.id','comments.hinban_id','comments.user_id','users.name','comments.comment','comments.updated_at'])
        ->where('comments.id',$id)
        ->first();

        $login_user = User::findOrFail(Auth::id());

        // dd($comment,$login_user,$comment->user_id);

        return view('comment.comment_edit',compact('comment','login_user'));
    }


    public function comment_update(Request $request, $id)
    {
        // $login_user = User::findOrFail(Auth::id());
        $comment=Comment::findOrFail($id);
        $comment_id = $request->comment_id2;

        $comment->comment = $request->comment;
        $comment->save();

        // ここでメール送信
        // $user = User::findOrFail(Auth::id())
        // ->toArray();

        // $users = Reservation::with('user:id,name,email')
        // ->where('event_id',$request->evt_id2)
        // ->distinct()
        // ->select('user_id')
        // ->get()
        // ->toArray();

        // $event_info = Event::findOrFail($request->evt_id2)
        // ->toArray();

        // dd($users,$event_info);

        // foreach($users as $user){
        //     $user = $user['user'];
        //     // dd($user,$event_info);
        //     SendCommentMail::dispatch($event_info,$user);
        // }


        return to_route('hinban_show',['id'=>$comment->hinban_id])->with(['message'=>'コメントが更新されました','status'=>'info']);
    }


    public function comment_destroy($id)
    {

        $Comment = Comment::findorfail($id);
        $hinban_id2 = $Comment->hinban_id;
        // dd($evt_id2);

        Comment::findOrFail($id)->delete();
        return to_route('hinban_show',['id'=>$hinban_id2])->with(['message'=>'コメントが削除されました','status'=>'alert']);
        // return to_route('eventlist')->with(['message'=>'コメントが削除されました','status'=>'alert']);
    }
}
