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
use App\Models\Shop;
use App\Models\User;
use Throwable;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    public function memberlist(Request $request)
    {

        $roles = DB::table('roles')
        ->select(['roles.id','roles.role_name'])
        ->get();
        $users = DB::table('users')
        ->join('roles','roles.id','=','users.role_id')
        ->select('users.id','users.name','users.role_id','roles.role_name','users.user_info','users.mailService')
        ->where(function ($query) use ($request) {
            $query->orwhere('users.user_info','LIKE','%'.($request->search).'%')
                ->orWhere('users.name','LIKE','%'.($request->search).'%') ;
        })
        ->where('users.role_id','<',10)

        ->paginate(50);
        $login_user = User::findOrFail(Auth::id());

                // dd($companies,$areas,$shops);

        return view('user.index',compact('roles','users','login_user'));
        // dd($roles,$areas,$users);
    }

    public function create()
    {
        // $shops = Shop::All();
        return view('user.create');
    }


    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'user_info' => ['string', 'max:255'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        try{
            DB::transaction(function()use($request){
                User::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'user_info' => $request->user_info,
                    'password' => Hash::make($request->password),
                ]);

            },2);
        }catch(Throwable $e){
            Log::error($e);
            throw $e;
        }

        return to_route('memberlist')->with(['message'=>'登録されました','status'=>'info']);
    }

    public function show($id)
    {
        $login_user = User::findOrFail(Auth::id());
        $user = DB::table('users')
        ->join('roles','roles.id','=','users.role_id')
        ->where('users.id',$id)
        ->select('users.id','users.name','users.email','users.role_id','roles.role_name','users.user_info')
        ->first();


        // dd($login_user,$user,$user->id);
        return view('user.member_detail',compact('login_user','user',));

    }

    public function edit($id)
    {
        $login_user=Auth::id();
        $user = DB::table('users')
        ->join('roles','roles.id','=','users.role_id')
        ->where('users.id',$id)
        ->select('users.id','users.name','users.email','users.role_id','roles.role_name','users.user_info','users.mailService')
        ->first();


        // dd($companies,$areas,$shops);
        // dd($shops,$user);
        return view('user.member_edit',compact('login_user','user'));
        // dd($login_user,$user);
    }

    public function member_update_rs1(Request $request, $id)
    {
        $user=User::findOrFail($id);

        $login_user = User::findOrFail(Auth::id());

        $user->name = $request->name;
        $user->email = $request->email;
        $user->mailService = $request->mailService;
        $user->user_info = $request->user_info;
        $user->save();

        return to_route('memberlist')->with(['message'=>'情報が更新されました','status'=>'info']);
    }


    public function user_destroy($id)
    {
        $user = User::findorfail($id);
        $filrPath1 = 'public/user/' . $user->photo1;
        if(Storage::exists($filrPath1)){
            Storage::delete($filrPath1);
        }
        $filrPath2 = 'public/user/' . $user->photo2;
        if(Storage::exists($filrPath2)){
            Storage::delete($filrPath2);
        }

        User::findOrFail($id)->delete();

        return to_route('memberlist')->with(['message'=>'メンバーが削除されました','status'=>'alert']);
    }

    public function role_list(Request $request)
    {

        $roles = DB::table('roles')
        ->select(['roles.id','roles.role_name'])
        ->get();

        $users = DB::table('users')
        ->join('roles','roles.id','=','users.role_id')
        ->select('users.id','users.name','users.role_id','roles.role_name','users.user_info')
        ->where('users.role_id','LIKE','%'.($request->role_id).'%')
        ->where('users.name','LIKE','%'.($request->user_name).'%')
        ->paginate(50);

        $login_user=User::findOrFail(Auth::id());

        $changeable_roles = DB::table('roles')
        ->where('roles.id','>=',$login_user->role_id)
        ->select('roles.id','roles.role_name')
        ->get();

        $changeable_users = DB::table('users')
        ->join('roles','roles.id','=','users.role_id')
        ->where('users.role_id','>=',$login_user->role_id)
        ->where('users.role_id','LIKE','%'.($request->role_id).'%')
        ->where('users.name','LIKE','%'.($request->user_name).'%')
        ->select('users.id','users.name','users.role_id','roles.role_name','users.user_info')
        ->get();
        // dd($login_user,$changeable_users,$changeable_roles);

        return view('role.role_list',compact('roles','users','changeable_users','changeable_roles'));
        // dd($roles,$areas,$users);
    }

    public function role_edit($id)
    {
        $login_user=Auth::id();
        $user = DB::table('users')
        ->join('roles','roles.id','=','users.role_id')
        ->where('users.id',$id)
        ->select('users.id','users.name','users.email','users.role_id','roles.role_name','users.user_info')
        ->first();

        $roles = DB::table('roles')
        ->select(['roles.id','roles.role_name'])
        ->get();

        $login_user2=User::findOrFail(Auth::id());

        $changeable_roles = DB::table('roles')
        ->where('roles.id','>=',$login_user2->role_id)
        ->groupBy('roles.id','role_name')
        ->select('roles.id','roles.role_name')
        ->get();

        $changeable_users = DB::table('users')
        ->join('roles','roles.id','=','users.role_id')
        ->where('users.role_id','>=',$login_user2->role_id)
        ->select('users.id','users.name','users.role_id','roles.role_name','users.user_info')
        ->get();
        // dd($login_user,$changeable_users,$changeable_roles);

        return view('role.role_edit',compact('login_user','user','roles','changeable_users','changeable_roles'));
        // dd($login_user,$user);
    }

    public function role_update(Request $request, $id)
    {
        $user=User::findOrFail($id);

        $login_user = User::findOrFail(Auth::id());

        $user->role_id = $request->role_id;

        // dd($request->name,$request->name_kana,$request->realname,$request->realname_kana,$request->user_info,);

        $user->save();

        return to_route('role_list')->with(['message'=>'情報が更新されました','status'=>'info']);
    }


    public function pw_change($id)
    {
        $login_user=User::findOrFail(Auth::id());

        $user = DB::table('users')
        ->join('areas','areas.id','=','users.area_id')
        ->join('roles','roles.id','=','users.role_id')
        ->where('users.id',$id)
        ->select('users.id','users.name','users.name_kana','users.email','users.role_id','roles.role_name','users.area_id','areas.area_name','users.user_info','users.photo1','users.photo2',)
        ->first();


        // dd($companies,$areas,$shops);

        return view('user.pw_change',compact('login_user','user'));
    }

    public function pw_update(Request $request)
    {

        $user=User::findOrFail(Auth::id());

        $request->validate([
            'current-password' => 'required',
            // 'new-password' => ['required', 'confirmed', Rules\Password::defaults()],
            'new-password' => ['required', Rules\Password::defaults()],
            'password-confirmation' => ['required', Rules\Password::defaults()],
        ]);

        if(!(Hash::check($request->get('current-password'), $user->password))) {
            return redirect()->route('pw_change',['user'=>$user->id])->withInput()->withErrors(array('current-password' => '現在のパスワードが間違っています'));
        }
        if(strcmp($request->get('current-password'), $request->get('new-password')) == 0) {
            return redirect()->route('pw_change',['user'=>$user->id])->withInput()->withErrors(array('new-password' => '新しいパスワードが現在のパスワードと同じです'));
        }

        if(!strcmp($request->get('password-confirmation'), $request->get('new-password')) == 0) {
            return to_route('pw_change',['user'=>$user->id])->withInput()->withErrors(array('password-confirmation' => '新しいパスワードと確認フィールドが一致しません'));
        }




        $user->password=Hash::make($request->get('new-password'));
        $user->save();

        return to_route('ac_info')->with(['message'=>'パスワードが更新されました','status'=>'info']);
    }

    public function pw_change_admin($id)
    {
        $target_user=User::findOrFail($id);

        $user = DB::table('users')
        ->join('roles','roles.id','=','users.role_id')
        ->where('users.id',$id)
        ->select('users.id','users.name','users.email','users.role_id','roles.role_name','users.user_info')
        ->first();


        // dd($companies,$areas,$shops);

        return view('user.pw_change_admin',compact('target_user','user'));
    }

    public function pw_update_admin(Request $request,$id)
    {

        $user=User::findOrFail($id);

        $request->validate([

            'new-password' => ['required', Rules\Password::defaults()],
            'password-confirmation' => ['required', Rules\Password::defaults()],
        ]);

        if(!strcmp($request->get('password-confirmation'), $request->get('new-password')) == 0) {
            return to_route('pw_change_admin',['user'=>$user->id])->withInput()->withErrors(array('password-confirmation' => '新しいパスワードと確認フィールドが一致しません'));
        }


        $user->password=Hash::make($request->get('new-password'));
        $user->save();

        return to_route('memberlist')->with(['message'=>'パスワードが更新されました','status'=>'info']);
    }




}
