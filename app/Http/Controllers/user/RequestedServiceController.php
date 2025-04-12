<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\RequestedService;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;


class RequestedServiceController extends Controller
{
    public function requestHistory($id,$hid = null){

        // hilight row logic
        $hilight_id = $hid;
        if($hid != null){
            $requested_service = RequestedService::find($hid);
            $requested_service->is_seen = 1;
            $requested_service->save();
            $hilight_id = $hid;
        }
        //notification logic
        $user_notification_msg = [];
        $user_notification_count = 0;
        $request_notifications = RequestedService::where('user_id','=',Session::get('session_user')->id)->where('is_seen','=',0)->limit(5)->get();
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
        //dashboard data
        $requested_services = RequestedService::where('user_id', $id)->where('is_canceled',0)->get();
        $pending_services = RequestedService::where('user_id', $id)->where('status', 'pending')->get();
        $confirmed_services = RequestedService::where('user_id', $id)->where('status', 'confirmed')->get();
        $rejected_services = RequestedService::where('user_id', $id)->where('status', 'rejected')->get();
        $canceled_services = RequestedService::where('user_id', $id)->where('is_canceled', 1)->get();
        $trashed_services = RequestedService::where('user_id', $id)->onlyTrashed()->get();
        return view('user.pages.user.requestHistory', compact('requested_services', 'pending_services', 'confirmed_services', 'rejected_services', 'trashed_services', 'canceled_services', 'user_notification_msg', 'user_notification_count', 'hilight_id'));
    }
    public function softDeleteRequest($id){
        $requested_services = RequestedService::where('id', $id)->first();
        $requested_services->delete();
        return redirect()->back()->with('success', 'Request deleted successfully');
    }
    public function restoreRequest($id){
        $requested_services = RequestedService::withTrashed()->find($id);
        $requested_services->restore();
        return redirect()->back()->with('success', 'Request restored successfully');
    }
    public function deleteRequest($id){
        $requested_services = RequestedService::withTrashed()->find($id);
        $requested_services->forceDelete();
        return redirect()->back()->with('success', 'Request deleted successfully');
    }
    public function manageRequest($id){
        $requested_services = RequestedService::findOrfail($id);
        if($requested_services->is_canceled == 1){
            $requested_services->is_canceled = 0;
        }else{
            $requested_services->is_canceled = 1;
        }
            $requested_services->save();
            return redirect()->back()->with('success', 'Request accepted successfully');
    }
    public function providerRating(Request $request,$id){
        $requested_services = RequestedService::findOrfail($id);
        $requested_services->rating = request()->rating;
        $requested_services->save();
        return redirect()->back()->with('success', 'Rating submitted successfully');
    }


    public function getAllAppointment()
    {
        $userId = Session::get('session_user')->id;
        $appointments = Appointment::with(["doctor", "patient"])
            ->where("patient_id", $userId)->get();
        $pendingAppointments = Appointment::with(["doctor", "patient"])
            ->where("patient_id", $userId)
            ->where("status", "pending")
            ->get();
        $approveAppointments = Appointment::with(["doctor", "patient"])
            ->where("patient_id", $userId)
            ->where("status", "approved")
            ->get();
        $rejectedAppointments = Appointment::with(["doctor", "patient"])
            ->where("patient_id", $userId)
            ->where("status", "rejected")
            ->get();

        return view("user.pages.appointments.index", [
            "appointments" => $appointments,
            "pendingAppointment" => $pendingAppointments,
            "approvedAppointment" => $approveAppointments,
            "rejectedAppointment" => $rejectedAppointments,
        ]);
    }

    public function showAppointment()
    {
        $doctors = User::whereRoleAndStatus("doctor", "active")->get();
//        dd($doctors);
        return view("user.pages.appointments.create", [
            "doctors" => $doctors
        ]);
    }

    public function storeAppointment(Request $request)
    {
        $requestAppointment = $request->all();
        $requestAppointment["status"] = "pending";
        $createData = [
            "doctor_id" => $requestAppointment["doctor"],
            "patient_id" => Session::get("session_user")->id,
            "status" => "pending",
            "description" => $requestAppointment["description"],
            "date" => $requestAppointment["date"],
        ];
        $user = Appointment::insert($createData);

        return redirect()->route('user.appointments.index')->with('success','User created successfully');
    }

    public function editAppointment($id)
    {
        $appointment = Appointment::with("patient", "doctor")->findOrFail($id);
        $doctors = User::whereRoleAndStatus("doctor", "active")->get();
        $users = User::whereRoleAndStatus("user", "active")->get();
        return view('user.pages.appointments.edit',[
            'appointment' => $appointment,
            "doctors" => $doctors,
            "users" => $users,
        ]);
    }

    public function updateAppointment(Request $request, $id)
    {
        $requestAppointment = $request->all();
        $appointment = Appointment::findOrFail($id);
        $updateData = [
            "doctor_id" => $requestAppointment["doctor"],
            "patient_id" => $requestAppointment["user"],
            "status" => "pending",
            "description" => $requestAppointment["description"],
            "date" => $requestAppointment["date"],
        ];
//        dd($updateData);

//        if($request->hasFile('avatar')){
//            $file = $request->file('avatar');
//            $fileName = md5(microtime()).'_'.$file->getClientOriginalName();
//            $file->move(public_path('profession_avatar'),$fileName);
//            if($user->avatar != null){
//                $old_avatar = $user->avatar;
//                if(file_exists(public_path('profession_avatar/'.$old_avatar))){
//                    unlink(public_path('profession_avatar/'.$old_avatar));
//                }
//            }
//
//        }
        $appointment->update($updateData);
//        $user->email = $requestUser->email;
//
//        $user->meta_description = $requestUser->phone_number;
//        $user->description = $requestUser->address;
////        if($request->hasFile('avatar')){
////            $user->avatar = $fileName;
////        }
//        $user->save();
        return redirect()->route('user.appointments.index')->with('success','Appointment updated successfully');
    }

    public function deleteAppointment($id)
    {
        $user = Appointment::withTrashed()->find($id);
        $user->forceDelete();
        return redirect()->route('user.appointments.index')->with('success', 'Appointment deleted successfully');
    }

}
