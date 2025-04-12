<?php

namespace App\Http\Middleware;

use App\Models\RequestedService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class UserLoginAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if(!Session::has('session_user')){
            return redirect()->route('user.login')->with('error','Please login first');
        }
        // $user_notification_msg = [];
        // $user_notification_count = 0;
        // $request_notifications = RequestedService::where('user_id','=',Session::get('session_user')->id)->where('is_seen','=',0)->get();
        // $user_notification_count = RequestedService::where('user_id','=',Session::get('session_user')->id)->where('is_seen','=',0)->count();
        // foreach($request_notifications as $key => $request_notification){
        //    if($request_notification->status == 'confirmed'){
        //         $user_notification_msg['message'][$key] = 'Your request has been confirmed by '.$request_notification->provider->name;
        //         $user_notification_msg['time_ago'][$key] = strtotime($request_notification->created_at);
        //     }elseif($request_notification->status == 'rejected'){
        //         $user_notification_msg['message'][$key] = 'Your request has been rejected by '.$request_notification->provider->name;
        //         $user_notification_msg['time_ago'][$key] = strtotime($request_notification->created_at);
        //     }
        // }

       
        return $next($request);
    }
}
