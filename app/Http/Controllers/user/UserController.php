<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use App\Models\RequestedService;
use App\Models\User;
use App\Models\UserTracker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function verify(Request $request)
    {
        $this->validate($request, [
            'email' => 'required',
            'password' => 'required',
        ]);
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){
            if(Auth::user()->status == 'new'){
                return redirect()->route('user.login')->with('error','Your account is not active yet. Please contact admin.');
            }
            if(Auth::user()->role == 'user'){
                $request->session()->put('session_user', Auth::user());

                UserTracker::updateOrCreate(
                    ['user_id' => Auth::user()->id],
                    [
                        'user_id' => Auth::user()->id,
                        'current_latitude' => $request->current_latitude,
                        'current_longitude' => $request->current_longitude,
                        'is_active' => 1
                        ]
                );
                return redirect()->route('user.dashboard');
            }else{
                return redirect()->route('user.login')->with('error', 'You are not a user');
            }

        }else{
            return redirect()->route('user.login')->with('error','Invalid Credentials');
        }
    }
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('user.register');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users,email',
            'role' => 'required',
            'password' => 'required|min:6|max:16|confirmed'
        ]);
//        dd($request->all());
        $data['name'] = $request->name;
        $data['email'] = $request->email;
        $data['password'] = Hash::make($request->password);
        $data['role'] = $request->role;
        $data['status'] = 'active';
        $user = User::create($data);

        return redirect()->route('user.login')->with('success','User created successfully');


    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
         //notification logic
         $user_notification_msg = [];
         $user_notification_count = 0;
         $request_notifications = RequestedService::where('user_id','=',Session::get('session_user')->id)->where('is_seen','=',1)->limit(5)->get();
         $user_notification_count = RequestedService::where('user_id','=',Session::get('session_user')->id)->where('is_seen','=',1)->count();
         foreach($request_notifications as $key => $request_notification){
            if($request_notification->status == 'confirmed'){
                 $user_notification_msg[$key]['status'] = 'confirmed';
                 $user_notification_msg[$key]['notification_id'] = $request_notification->id;
                 $user_notification_msg[$key]['message'][$key] = 'Your request has been confirmed by '.$request_notification->provider->name;
                 $user_notification_msg[$key]['time_ago'][$key] = strtotime($request_notification->created_at);
             }elseif($request_notification->status == 'rejected'){
                 $user_notification_msg[$key]['status'] = 'rejected';
                 $user_notification_msg[$key]['notification_id'] = $request_notification->id;
                 $user_notification_msg[$key]['message'][$key] = 'Your request has been rejected by '.$request_notification->provider->name;
                 $user_notification_msg[$key]['time_ago'][$key] = strtotime($request_notification->created_at);
             }
         }
            //end notification logic
        $user = User::findOrfail($id);
        return view('user.pages.user.edit',compact('user','user_notification_msg','user_notification_count'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrfail($id);
        $request->has('avatar') ? $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]) : '';
        if($request->has('avatar')){
            $file = $request->file('avatar');
            $fileName = md5(microtime()).'_'.$file->getClientOriginalName();
            $file->move(public_path('user_avatar'),$fileName);
            if($user->avatar != null){
                $old_avatar = $user->avatar;
                if(file_exists(public_path('user_avatar/'.$old_avatar))){
                    unlink(public_path('user_avatar/'.$old_avatar));
                }
             }
            $user->avatar = $fileName;
            $user->save();
        }else{
            $user['name'] = $request->name;
            $user['email'] = $request->email;
            $user['phone'] = $request->phone;
            $user['address'] = $request->address;
            $user->save();
        }
        return redirect()->route('users.edit',$user->id)->with('success','User updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    public function changePassword(Request $request,$id){

        if($request->isMethod('get')){
            $user_id = $id;
            return view('user.change-password',compact('user_id'));
        }
        if($request->isMethod('post')){
            $request->validate([
                'old_password' => 'required',
                'password' => 'required|min:6|max:16|confirmed',
            ]);
            $user = User::findOrfail($id);
            if(Hash::check($request->old_password,$user->password)){
                $user->password = Hash::make($request->password);
                $user->save();
                return redirect('user-panel/users/'.$id.'/edit')->with('success','Password changed successfully');
            }else{
                return redirect()->back()->with('error','Old password is incorrect');
            }
        }


    }
}
