<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Jobs\SendCommentMail;
use App\Models\Hinban;
use App\Models\User;
use Illuminate\Http\Request;

use function Laravel\Prompts\select;

class CommentController extends Controller
{

    public function comment_create($id)
    {
        $login_user = User::findOrFail(Auth::id());

        $report = Report::findOrFail($id);

        // dd($login_user,$comment);

        return view('comment.comment_create',compact('login_user','report'));
    }


    public function comment_store(Request $request)
    {
        $login_user = User::findOrFail(Auth::id());
        $report_id = $request->report_id2;

        // dd($request->report_id2,$login_user->id,$request->report_id2,$request->comment);
        Comment::create([
            'user_id' => $login_user->id,
            'report_id' => $request->report_id2,
            'comment' => $request->comment,
        ]);

        // ここでメール送信
        $users = User::Where('mailService','=',1)
        ->where('shop_id','<>',106)
        ->where('shop_id','<',4000)
        ->orwhere('shop_id','>',5000)
        ->get()
        ->toArray();

        $comment_info = Report::Where('reports.id','=',$request->report_id2)
        ->join('shops','reports.shop_id','shops.id')
        ->distinct()
        ->select('reports.id','shop_id','shop_name')
        ->get()
        ->toArray();

        $comment_info = $comment_info[0];

        // dd($users,$comment_info);

        foreach($users as $user){
            // $comment_info = $comment_info[0];
            // dd($request->report_id2,$user,$comment_info);
            SendCommentMail::dispatch($comment_info,$user);
        }

        return to_route('report_detail',['report'=>$report_id])->with(['message'=>'コメントが登録されました','status'=>'info']);
    }


    public function comment_detail($id)
    {
        $comment=DB::table('comments')
        ->join('users','users.id','=','comments.user_id')
        ->join('reports','reports.id','=','comments.report_id')
        ->join('shops','shops.id','=','reports.shop_id')
        ->select(['comments.id','comments.report_id','shops.shop_name','comments.user_id','users.name','comments.comment','comments.created_at'])
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
        ->join('reports','reports.id','=','comments.report_id')
        ->join('shops','shops.id','=','reports.shop_id')
        ->select(['comments.id','comments.report_id','shops.shop_name','comments.user_id','users.name','comments.comment','comments.updated_at'])
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


            // $comment->user_id -> $login_user->id;
            // $comment->event_id -> $request->evt_id2;
            $comment->comment = $request->comment;
            // dd($comment->title,$comment->comment);
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


        return to_route('report_detail',['report'=>$comment->report_id])->with(['message'=>'コメントが更新されました','status'=>'info']);
    }


    public function comment_destroy($id)
    {

        $Comment = Comment::findorfail($id);
        $report_id2 = $Comment->report_id;
        // dd($evt_id2);

        Comment::findOrFail($id)->delete();
        return to_route('report_detail',['report'=>$report_id2])->with(['message'=>'コメントが削除されました','status'=>'alert']);
        // return to_route('eventlist')->with(['message'=>'コメントが削除されました','status'=>'alert']);
    }
}
