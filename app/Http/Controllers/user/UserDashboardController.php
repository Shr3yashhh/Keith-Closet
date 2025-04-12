<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use App\Models\RequestedService;
use App\Models\UserTracker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class UserDashboardController extends Controller
{
    
    public function index(){
        //notification logic
        $user_notification_msg = [];
        $user_notification_count = 0;
        $request_notifications = RequestedService::where('user_id','=',Session::get('session_user')->id)->where('is_seen','=',1)->limit(5)->get();
        $user_notification_count = RequestedService::where('user_id','=',Session::get('session_user')->id)->where('is_seen','=',0)->count();
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
        // dd($user_notification_msg);
        $request_count = RequestedService::where('user_id','=',Session::get('session_user')->id)->count();
        $request_count_today = RequestedService::whereDate('created_at', date('Y-m-d'))->where('user_id','=',Session::get('session_user')->id)->count();
        $request_count_month = RequestedService::whereMonth('created_at', date('m'))->where('user_id','=',Session::get('session_user')->id)->count();
        $request_count_year = RequestedService::whereYear('created_at', date('Y'))->where('user_id','=',Session::get('session_user')->id)->count();
        $years = RequestedService::select(DB::raw("DATE_FORMAT(created_at, '%Y-%m') as year"))->groupBy('year')->orderBy('year', 'asc')->get();
        
        if($years->count() > 0){
            foreach($years as $year){
                $year_arr[] = $year->year;
            }
            $year_arr = array_unique($year_arr);
           
        }else{
            $year_arr = array();
        }
        
      
        $request = [];
        foreach ($year_arr as $key => $value) {
            $request[] = RequestedService::where(DB::raw("DATE_FORMAT(created_at, '%Y-%m')"), '=', $value)->count();
        }

    	return view('user.index')
        ->with('year',json_encode($year_arr,JSON_NUMERIC_CHECK))
        ->with('request_count',$request_count)
        ->with('request_count_today',$request_count_today)
        ->with('request_count_month',$request_count_month)
        ->with('request_count_year',$request_count_year)
        ->with('request',json_encode($request,JSON_NUMERIC_CHECK))
        ->with('user_notification_msg',$user_notification_msg)
        ->with('user_notification_count',$user_notification_count);
    }
    public function login()
    {
        return view('user.login');
    }

    public function logout()
    {
        $user_data = UserTracker::where('user_id',Session::get('session_user')->id)->get();
        if(count($user_data)>0){
            $user = $user_data[0]->id;
            $user_current_data = UserTracker::findOrfail($user);
            $user_current_data->is_active = 0;
            $user_current_data->last_latitude = $user_current_data->current_latitude;
            $user_current_data->last_longitude = $user_current_data->current_longitude;
            $user_current_data->current_latitude = null;
            $user_current_data->current_longitude = null;
            $user_current_data->save();

        }
        Session::forget('session_user');
        return redirect()->route('user.login');
    }
   

}
