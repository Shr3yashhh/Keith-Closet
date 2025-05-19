<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\RequestedService;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;

class AdminController extends Controller
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
            'g-recaptcha-response' => 'required|captcha'
        ]);
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){
            if(Auth::user()->role == 'admin'){
                $request->session()->put('session_admin', Auth::user());
                return redirect()->route('admin.dashboard');
            }else{
                return redirect()->route('admin.login')->with('error', 'You are not an admin');
            }
        }else{
            $user = User::where('email', $request->email)->first();
            if($user){
                if ($user->login_attempt >= 5) {
                    Mail::to($user->email)->send(new \App\Mail\ResetLoginAttemptMail($user));
                    return redirect()->route('admin.login')->with('error','Your account is locked. Please check your email to reset your login attempts.');
                }
                $user->login_attempt = $user->login_attempt + 1;
                $user->save();
            }
            return redirect()->route('admin.login')->with('error','Invalid Credentials');
        }
    }
    public function index()
    {
        //
    }

    public function resetLoginAttempt(Request $request)
    {
        $email = $request->query('email');
        $user = User::where('email', $email)->first();
        if ($user) {
            $user->login_attempt = 0;
            $user->save();
            return redirect()->route('admin.login')->with('success','You can login again');
        } else {
            return redirect()->route('admin.login')->with('error','User not found');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.register');
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
            'password' => [
                'required',
                'string',
                'min:8',
                'max:16',
                'confirmed',
                'regex:/^(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*#?&])[A-Za-z\d@$!%*#?&]{6,}$/'
            ],
        ]);
        $data['name'] = $request->name;
        $data['email'] = $request->email;
        $data['password'] = Hash::make($request->password);
        $data['role'] = $request->role;
        $data['status'] = 'active';
        User::create($data);
        return redirect()->route('admin.login')->with('success','User created successfully');


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
        $admin = User::findOrfail($id);
        return view('admin.pages.admin.edit',compact('admin'));
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
        $admin = User::findOrfail($id);
        $request->has('avatar') ? $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]) : '';
        if($request->has('avatar')){
            $file = $request->file('avatar');
            $fileName = md5(microtime()).'_'.$file->getClientOriginalName();
            $file->move(public_path('admin_avatar'),$fileName);
            if($admin->avatar != null){
                $old_avatar = $admin->avatar;
                if(file_exists(public_path('admin_avatar/'.$old_avatar))){
                    unlink(public_path('admin_avatar/'.$old_avatar));
                }
             }
            $admin->avatar = $fileName;
            $admin->save();
        }else{
            $admin['name'] = $request->name;
            $admin['email'] = $request->email;
            $admin['phone'] = $request->phone;
            $admin['address'] = $request->address;
            $admin->save();
        }
        return redirect()->route('admins.edit',$admin->id)->with('success','Admin updated successfully');
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
    public function forgotPassword(Request $request){
        if($request->isMethod('get')){
            return view('admin.forgot-password');
        }
        if($request->isMethod('post')){
            $request->validate([
                'email' => 'required|email',
            ]);


            $user = User::where('email',$request->email)->first();
            if(!$user){
                return redirect()->back()->with('error','Email not found');
            }else{
                $token = md5(microtime());
                // $url = route('admin.resetPassword').'?token='.$token;
                $details = [
                    'title' => 'Mail from HMS',
                    'body' => 'Please click the link below to reset your password',
                    'link' => route('admin.resetPassword').'?token='.$token,
                ];

                Mail::to('test@gmail.com')->send(new \App\Mail\AdminForgotPassword($details));
                if(Mail::failures()){
                    return redirect()->back()->with('error','Something went wrong');
                }else{
                    $user->token = $token;
                    $user->save();
                    return redirect()->back()->with('success','Please check your email');
                }
            }
        }

    }

    public function resetPassword(Request $request){
        if($request->isMethod('get')){
            $token = $request->query()["token"] ?? null;
            $user = User::where('token',$token)->first();
            if(!$user){
                return redirect()->route('admin.login')->with('error','Token not found');
            }else{
                return view('admin.forgot-password-form', compact('token'));
            }
        }
        if($request->isMethod('post')){
            $request->validate([
                'password' => [
                    'required',
                    'string',
                    'min:8',
                    'max:16',
                    'regex:/^(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*#?&])[A-Za-z\d@$!%*#?&]{6,}$/'
                ],
            ]);
            $token = $request->query()["token"] ?? null;

            $user = User::where('token',$token)->first();
            if(!$user){
                return redirect()->route('admin.login')->with('error','Token not found');
            }else{
                $user->password = Hash::make($request->password);
                $user->token = null;
                $user->save();
                return redirect()->route('admin.login')->with('success','Password changed successfully');
            }
        }
    }

    public function changePassword(Request $request,$id){

            if($request->isMethod('get')){
                $user_id = $id;
                return view('admin.change-password',compact('user_id'));
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
                    return redirect('admin-panel/admins/'.$id.'/edit')->with('success','Password changed successfully');
                }else{
                    return redirect()->back()->with('error','Old password is incorrect');
                }
            }
        }

        //request list of all users
        public function requestList($id, $hid = null){
            // hilight row logic
            $hilight_id = $hid;
            if($hid != null){
                $requested_service = RequestedService::find($hid);
                $requested_service->is_seen_admin = 1;
                $requested_service->save();
                $hilight_id = $hid;
            }
            //notification logic
            $user_notification_msg = [];
            $user_notification_count = 0;
              $request_notifications = RequestedService::where('provider_id','=',Session::get('session_provider')?->id)->where('is_seen_admin','=',0)->limit(5)->get();
              $user_notification_count = RequestedService::where('provider_id','=',Session::get('session_provider')?->id)->where('is_seen_admin','=',0)->count();
              foreach($request_notifications as $key => $request_notification){
                      $user_notification_msg[$key]['notification_id'] = $request_notification?->id;
                      $user_notification_msg[$key]['message'][$key] = $request_notification?->user?->name.' has sent you a request';
                      $user_notification_msg[$key]['time_ago'][$key] = strtotime($request_notification?->created_at);

              }

              //other logic
            $requested_services = RequestedService::where('is_canceled',0)->get();
            $pending_services = RequestedService::where('status', 'pending')->get();
            $confirmed_services = RequestedService::where('status', 'confirmed')->get();
            $rejected_services = RequestedService::where('status', 'rejected')->get();
            $cancelled_services = RequestedService::where('status', 'cancelled')->get();
            return view('admin.pages.requestList', compact('requested_services', 'pending_services', 'confirmed_services', 'rejected_services', 'cancelled_services', 'user_notification_msg', 'user_notification_count', 'hilight_id'));
        }
}
