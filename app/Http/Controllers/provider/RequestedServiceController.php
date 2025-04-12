<?php

namespace App\Http\Controllers\provider;

use App\Http\Controllers\Controller;
use App\Models\RequestedService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class RequestedServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
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
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
        //
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
        //
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
    public function requestList($id,$hid = null){
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
          $request_notifications = RequestedService::where('provider_id','=',Session::get('session_provider')->id)->where('is_seen_admin','=',0)->limit(5)->get();
          $user_notification_count = RequestedService::where('provider_id','=',Session::get('session_provider')->id)->where('is_seen_admin','=',0)->count();
          foreach($request_notifications as $key => $request_notification){
                  $user_notification_msg[$key]['notification_id'] = $request_notification->id;
                  $user_notification_msg[$key]['message'][$key] = $request_notification->user->name.' has sent you a request';
                  $user_notification_msg[$key]['time_ago'][$key] = strtotime($request_notification->created_at);
              
          }

          //other logic
        $requested_services = RequestedService::where('provider_id', $id)->where('is_canceled',0)->get();
        $pending_services = RequestedService::where('provider_id', $id)->where('status', 'pending')->get();
        $confirmed_services = RequestedService::where('provider_id', $id)->where('status', 'confirmed')->get();
        $rejected_services = RequestedService::where('provider_id', $id)->where('status', 'rejected')->get();
        $cancelled_services = RequestedService::where('provider_id', $id)->where('status', 'cancelled')->get();
        return view('provider.pages.provider.requestList', compact('requested_services', 'pending_services', 'confirmed_services', 'rejected_services', 'cancelled_services', 'user_notification_msg', 'user_notification_count', 'hilight_id'));
    }
    public function softDeleteRequest($id){
        $requested_service = RequestedService::find($id);
        $requested_service->delete();
        return redirect()->back()->with('success', 'Service deleted successfully');
    }
    public function restoreRequest($id){
        $requested_service = RequestedService::withTrashed()->find($id);
        $requested_service->restore();
        return redirect()->back()->with('success', 'Service restored successfully');
    }
    public function deleteRequest($id){
        $requested_service = RequestedService::withTrashed()->find($id);
        $requested_service->forceDelete();
        return redirect()->back()->with('success', 'Service deleted successfully');
    }
    public function manageRequest(Request $request,$id){
        $user = RequestedService::findOrfail($id);
        if($request->has('status')){
            $user->status = $request->status;
            $user->is_seen = 0;
            $user->save();

            return redirect()->route('provider.requestList',$user->provider_id)->with('success','User status updated successfully');
        }
        return redirect()->route('provider.requestList',$user->provider_id)->with('error','Something went wrong');
    }
}
