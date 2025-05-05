<?php

namespace App\Http\Controllers\provider;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\ProviderTracker;
use App\Models\RequestedService;
use App\Models\Test;
use App\Models\User;
use Barryvdh\DomPDF\PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class ProviderDashboardController extends Controller
{
    public PDF $pdf;
    public function __construct(
        PDF $pdf
    )
    {
        $this->pdf = $pdf;
    }
    public function index(){
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
        //counter logic
        $request_count = RequestedService::where('provider_id','=',Session::get('session_provider')->id)->count();
        $request_count_today = RequestedService::whereDate('created_at', date('Y-m-d'))->where('provider_id','=',Session::get('session_provider')->id)->count();
        $request_count_month = RequestedService::whereMonth('created_at', date('m'))->where('provider_id','=',Session::get('session_provider')->id)->count();
        $request_count_year = RequestedService::whereYear('created_at', date('Y'))->where('provider_id','=',Session::get('session_provider')->id)->count();
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

    	return view('provider.index')
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
        // if(Session::has('session_provider')){
        //     return redirect()->route('provider.dashboard');
        // }
        return view('provider.login');
    }

    public function logout()
    {
        $provider_data = ProviderTracker::where('provider_id',Session::get('session_provider')->id)->get();
        if(count($provider_data)>0){
            $provider = $provider_data[0]->id;
            $provider_current_data = ProviderTracker::findOrfail($provider);
            $provider_current_data->is_active = 0;
            $provider_current_data->last_latitude = $provider_current_data->current_latitude;
            $provider_current_data->last_longitude = $provider_current_data->current_longitude;
            $provider_current_data->current_latitude = null;
            $provider_current_data->current_longitude = null;
            $provider_current_data->save();

        }

        Session::forget('session_provider');
        return redirect()->route('provider.login');
    }

    //    Appointment
    public function listAppointment()
    {
        $appointments = Appointment::with([
            "patient",
            "doctor",
        ])->where("doctor_id", Session::get("session_provider")->id)
            ->get();

        return view('provider.pages.appointments.index',[
            "appointments" => $appointments,
        ]);
    }

    public function manageAppointment(Request $request,$id)
    {
        $appointments = Appointment::findOrfail($id);
        if($request->has('status')){
            $appointments->status = $request->status;
            $appointments->save();
            return redirect()->route('provider.appointments')->with('success','User status updated successfully');
        }
        return redirect()->route('provider.appointments')->with('error','Something went wrong');
    }

    public function storeAppointment(Request $request)
    {
        $requestAppointment = $request->all();
        $requestAppointment["status"] = "pending";
        $createData = [
            "doctor_id" => Session::get("session_provider")->id,
            "patient_id" => $requestAppointment["user"],
            "status" => "pending",
            "description" => $requestAppointment["description"],
            "date" => $requestAppointment["date"],
        ];
        $user = Appointment::insert($createData);

        return redirect()->route('provider.appointments')->with('success','User created successfully');
    }

    public function showAppointment()
    {
        $doctors = User::whereRoleAndStatus("doctor", "active")->get();
        $users = User::whereRoleAndStatus("user", "active")->get();
        return view('provider.pages.appointments.create', [
            "doctors" => $doctors,
            "users" => $users,
        ]);
    }

    public function editAppointment($id)
    {
        $appointment = Appointment::with("patient", "doctor")->findOrFail($id);
        $doctors = User::whereRoleAndStatus("doctor", "active")->get();
        $users = User::whereRoleAndStatus("user", "active")->get();
        return view('provider.pages.appointments.edit',[
            'appointment' => $appointment,
            "doctors" => $doctors,
            "users" => $users,
        ]);
    }

    public function updateAppointment(Request $request, $id)
    {
//        $requestUser = $request->validate([
//            "name" => "required|string",
//            "email" => "required|email",
//            "phone" => "required|string",
//            "address" => "required|string",
//            "password" => "required|string",
//        ]);
        $requestAppointment = $request->all();
        $appointment = Appointment::findOrFail($id);
        $updateData = [
            "patient_id" => $requestAppointment["user"],
            "description" => $requestAppointment["description"],
            "date" => $requestAppointment["date"],
        ];

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
        return redirect()->route('provider.appointments')->with('success','Appointment updated successfully');
    }

    public function deleteAppointment($id)
    {
        $user = Appointment::withTrashed()->find($id);
        $user->forceDelete();
        return redirect()->route('provider.appointments')->with('success', 'Appointment deleted successfully');
    }



    // User Part
    public function listUsers()
    {
        $active_users = User::where('role', 'user')->get();
        $new_users = User::where('role', 'user')->where('status','new')->get();
        $trashed_users = User::onlyTrashed()->where('role', 'user')->get();
        $suspended_users = User::where('role', 'user')->where('status','suspended')->get();
        return view('provider.pages.users.index',[
            'active_users' => $active_users,
            'new_users' => $new_users,
            'trashed_users' => $trashed_users,
            'suspended_users' => $suspended_users
        ]);
    }
    public function softDeleteUser($id)
    {
        $user = User::find($id);
        $user->delete();
        return redirect()->route('provider.users')->with('success', 'User deleted successfully');
    }
    public function restoreUser($id)
    {
        $user = User::withTrashed()->find($id);
        $user->restore();
        return redirect()->route('provider.users')->with('success', 'User restored successfully');
    }
    public function deleteUser($id)
    {
        $user = User::withTrashed()->find($id);
        $user->forceDelete();
        return redirect()->route('provider.users')->with('success', 'User deleted successfully');
    }
    public function manageUser(Request $request,$id)
    {
        $user = User::findOrfail($id);
        if($request->has('status')){
            $user->status = $request->status;
            $user->save();
            return redirect()->route('provider.users')->with('success','User status updated successfully');
        }
        return redirect()->route('provider.users')->with('error','Something went wrong');
    }

    public function storeUser(Request $request)
    {
        $requestUser = $request->validate([
            "name" => "required|string",
            "email" => "required|email",
            "phone" => "required|string",
            "address" => "required|string",
            "password" => "required|string",
        ]);
        $requestUser["password"] = Hash::make($requestUser["password"]);
        $requestUser["status"] = "active";
        $requestUser["role"] = "user";
        $use = User::insert($requestUser);

        return redirect()->route('provider.users')->with('success','User created successfully');
    }

    public function showUser()
    {
        return view('provider.pages.users.create');
    }

    public function editUser($id)
    {
        $user = User::findOrFail($id);
        return view('provider.pages.users.edit',['user' => $user]);
    }

    public function updateUser(Request $request, $id)
    {
//        $requestUser = $request->validate([
//            "name" => "required|string",
//            "email" => "required|email",
//            "phone" => "required|string",
//            "address" => "required|string",
//            "password" => "required|string",
//        ]);
        $requestUser = $request->all();
        unset($requestUser["_token"]);

        $user = User::findOrFail($id);


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

        $user->name = $requestUser["name"];
        $user->email = $requestUser["email"];

        $user->phone = $requestUser["phone"];
        $user->address = $requestUser["address"];
//        if($request->hasFile('avatar')){
//            $user->avatar = $fileName;
//        }
        $user->save();
        return redirect()->route('provider.users')->with('success','User updated successfully');
    }

    //    Test

    public function listTests()
    {
        $tests = Test::with([
            "patient",
        ])->get();

        return view('provider.pages.tests.index',[
            "tests" => $tests,
        ]);
    }

    public function manageTest(Request $request,$id)
    {
        $appointments = Test::findOrfail($id);
        if($request->has('status')){
            $appointments->status = $request->status;
            $appointments->save();
            return redirect()->route('provider.appointments')->with('success','User status updated successfully');
        }
        return redirect()->route('provider.appointments')->with('error','Something went wrong');
    }

    public function storeTest(Request $request)
    {
        $requestAppointment = $request->all();
        $requestAppointment["status"] = "pending";
        $createData = [
            "patient_id" => $requestAppointment["user"],
            "name" => $requestAppointment["name"],
            "comment" => $requestAppointment["comment"],
        ];
        $user = Test::insert($createData);

        return redirect()->route('provider.tests')->with('success','Test created successfully');
    }

    public function showTest()
    {
        $doctors = User::whereRoleAndStatus("doctor", "active")->get();
        $users = User::whereRoleAndStatus("user", "active")->get();
        return view('provider.pages.tests.create', [
            "doctors" => $doctors,
            "users" => $users,
        ]);
    }

    public function editTest($id)
    {
        $test = Test::with("patient")->findOrFail($id);
        $users = User::whereRoleAndStatus("user", "active")->get();
        return view('provider.pages.tests.edit',[
            'test' => $test,
            "users" => $users,
        ]);
    }

    public function updateTest(Request $request, $id)
    {
//        $requestUser = $request->validate([
//            "name" => "required|string",
//            "email" => "required|email",
//            "phone" => "required|string",
//            "address" => "required|string",
//            "password" => "required|string",
//        ]);
        $requestAppointment = $request->all();
        $appointment = Test::findOrFail($id);
        $updateData = [
            "name" => $requestAppointment["name"],
            "patient_id" => $requestAppointment["user"],
            "comment" => $requestAppointment["comment"],
        ];

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
        return redirect()->route('provider.tests')->with('success','Appointment updated successfully');
    }

    public function deleteTest($id)
    {
        $user = Test::withTrashed()->find($id);
        $user->forceDelete();
        return redirect()->route('provider.tests')->with('success', 'Appointment deleted successfully');
    }

    public function generateTestPdf($id)
    {
        $test = Test::with("patient")->findOrFail($id);

        $pdf =$this->pdf->loadView("provider.pages.tests.report", ["data" => $test]);

        return $pdf->download("report");
    }
}
