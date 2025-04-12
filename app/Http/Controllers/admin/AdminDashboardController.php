<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Bed;
use App\Models\Profession;
use App\Models\RequestedService;
use App\Models\Test;
use App\Models\User;
use Barryvdh\DomPDF\PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;


class AdminDashboardController extends Controller
{
    public PDF $pdf;
    public function __construct(
        PDF $pdf
    )
    {
        $this->pdf = $pdf;
    }
    public function index(){
        $user_count = User::where('role', '=', 'user')->count();
        $provider_count = User::where('role', '=', 'provider')->count();
        $request_count = RequestedService::count();
        $request_count_today = RequestedService::whereDate('created_at', date('Y-m-d'))->count();
        $request_count_month = RequestedService::whereMonth('created_at', date('m'))->count();
        $request_count_year = RequestedService::whereYear('created_at', date('Y'))->count();
        $years = User::select(DB::raw("DATE_FORMAT(created_at, '%Y-%m') as year"))->groupBy('year')->orderBy('year', 'asc')->get();
        if($years->count() > 0){
            foreach($years as $year){
                $year_arr[] = $year->year;
            }
            $year_arr = array_unique($year_arr);

        }else{
            $year_arr = array();
        }

        $user = [];
        $provider = [];
        $request = [];
        foreach ($year_arr as $key => $value) {
            $user[] = User::where(DB::raw("DATE_FORMAT(created_at, '%Y-%m')"), '=', $value)->where('role','=','user')->count();
            $provider[] = User::where(DB::raw("DATE_FORMAT(created_at, '%Y-%m')"), '=', $value)->where('role','=','provider')->count();
            $request[] = RequestedService::where(DB::raw("DATE_FORMAT(created_at, '%Y-%m')"), '=', $value)->count();
        }

    	return view('admin.index')
        ->with('year',json_encode($year_arr,JSON_NUMERIC_CHECK))
        ->with('user',json_encode($user,JSON_NUMERIC_CHECK))
        ->with('user_count',$user_count)
        ->with('provider_count',$provider_count)
        ->with('request_count',$request_count)
        ->with('request_count_today',$request_count_today)
        ->with('request_count_month',$request_count_month)
        ->with('request_count_year',$request_count_year)
        ->with('provider',json_encode($provider,JSON_NUMERIC_CHECK))
        ->with('request',json_encode($request,JSON_NUMERIC_CHECK));
    }
    public function login()
    {
        return view('admin.login');
    }

    public function logout()
    {
        Session::forget('session_admin');
        return redirect()->route('admin.login')->with('success','Logout Successfully');
    }
    public function listProviders()
    {
        $active_providers = User::where('role', 'doctor')->where('status','active')->get();
        $new_providers = User::where('role', 'doctor')->where('status','new')->get();
        $trashed_providers = User::onlyTrashed()->where('role', 'doctor')->get();
        $suspended_providers = User::where('role', 'doctor')->where('status','suspended')->get();
        return view('admin.pages.providers.index',[
            'active_providers' => $active_providers,
            'new_providers' => $new_providers,
            'trashed_providers' => $trashed_providers,
            'suspended_providers' => $suspended_providers
        ]);
    }
    public function softDeleteProvider($id)
    {
        $provider = User::find($id);
        $provider->delete();
        return redirect()->route('admin.providers')->with('success','Provider deleted successfully');
    }
    public function restoreProvider($id)
    {
        $provider = User::withTrashed()->find($id);
        $provider->restore();
        return redirect()->route('admin.providers')->with('success','Provider restored successfully');
    }
    public function deleteProvider($id)
    {
        $provider = User::withTrashed()->find($id);
        $provider->forceDelete();
        return redirect()->route('admin.providers')->with('success','Provider deleted successfully');
    }
    public function manageProvider(Request $request,$id)
    {
        $provider = User::findOrfail($id);
        if($request->has('status')){
            $provider->status = $request->status;
            $provider->save();
            return redirect()->route('admin.providers')->with('success','Provider status updated successfully');
        }
        return redirect()->route('admin.providers')->with('error','Something went wrong');
    }

    public function storeProvider(Request $request)
    {
        $requestUser = $request->validate([
            "name" => "required|string",
            "email" => "required|email",
            "phone" => "required|string",
            "address" => "required|string",
            "password" => "required|string",
        ]);
        $requestUser["password"] = Hash::make($requestUser["password"]);
        $requestUser["role"] = "doctor";
        $requestUser["status"] = "active";
        $use = User::insert($requestUser);

        return redirect()->route('admin.providers')->with('success','Doctor created successfully');
    }

    public function showProvider()
    {
        return view('admin.pages.providers.create');
    }

    public function editProvider($id)
    {
        $provider = User::findOrFail($id);
        return view('admin.pages.providers.edit',['provider' => $provider]);
    }

    public function updateProvider(Request $request, $id)
    {
        $requestProvider = $request->validate([
            "name" => "required|string",
            "email" => "required|email",
            "phone" => "required|string",
            "address" => "required|string",
            "password" => "required|string",
        ]);
        dd($requestProvider);
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

        $user->name = $requestProvider->name;
        $user->email = $requestProvider->email;

        $user->meta_description = $requestProvider->phone_number;
        $user->description = $requestProvider->address;
//        if($request->hasFile('avatar')){
//            $user->avatar = $fileName;
//        }
        $user->save();
        return redirect()->route('admin.providers')->with('success','User updated successfully');
    }



    // User Part
    public function listUsers()
    {
        $active_users = User::where('role', 'user')->where('status','active')->get();
        $new_users = User::where('role', 'user')->where('status','new')->get();
        $trashed_users = User::onlyTrashed()->where('role', 'user')->get();
        $suspended_users = User::where('role', 'user')->where('status','suspended')->get();
        return view('admin.pages.users.index',[
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
        return redirect()->route('admin.users')->with('success', 'User deleted successfully');
    }
    public function restoreUser($id)
    {
        $user = User::withTrashed()->find($id);
        $user->restore();
        return redirect()->route('admin.users')->with('success', 'User restored successfully');
    }
    public function deleteUser($id)
    {
        $user = User::withTrashed()->find($id);
        $user->forceDelete();
        return redirect()->route('admin.users')->with('success', 'User deleted successfully');
    }
    public function manageUser(Request $request,$id)
    {
        $user = User::findOrfail($id);
        if($request->has('status')){
            $user->status = $request->status;
            $user->save();
            return redirect()->route('admin.users')->with('success','User status updated successfully');
        }
        return redirect()->route('admin.users')->with('error','Something went wrong');
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

        $use = User::insert($requestUser);

        return redirect()->route('admin.users')->with('success','User created successfully');
    }

    public function showUser()
    {
        return view('admin.pages.users.create');
    }

    public function editUser($id)
    {
        $user = User::findOrFail($id);
        return view('admin.pages.users.edit',['user' => $user]);
    }

    public function updateUser(Request $request, $id)
    {
        $requestUser = $request->validate([
            "name" => "required|string",
            "email" => "required|email",
            "phone" => "required|string",
            "address" => "required|string",
            "password" => "required|string",
        ]);
        dd($requestUser);
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

        $user->name = $requestUser->name;
        $user->email = $requestUser->email;

        $user->meta_description = $requestUser->phone_number;
        $user->description = $requestUser->address;
//        if($request->hasFile('avatar')){
//            $user->avatar = $fileName;
//        }
        $user->save();
        return redirect()->route('admin.users')->with('success','User updated successfully');
    }

//    Appointment

    public function listAppointment()
    {
        $appointments = Appointment::with([
            "patient",
            "doctor",
        ])->get();

        return view('admin.pages.appointments.index',[
            "appointments" => $appointments,
        ]);
    }

    public function manageAppointment(Request $request,$id)
    {
        $appointments = Appointment::findOrfail($id);
        if($request->has('status')){
            $appointments->status = $request->status;
            $appointments->save();
            return redirect()->route('admin.appointments')->with('success','User status updated successfully');
        }
        return redirect()->route('admin.appointments')->with('error','Something went wrong');
    }

    public function storeAppointment(Request $request)
    {
        $requestAppointment = $request->all();
        $requestAppointment["status"] = "pending";
        $createData = [
            "doctor_id" => $requestAppointment["doctor"],
            "patient_id" => $requestAppointment["user"],
            "status" => "pending",
            "description" => $requestAppointment["description"],
            "date" => $requestAppointment["date"],
        ];
        $user = Appointment::insert($createData);

        return redirect()->route('admin.appointments')->with('success','User created successfully');
    }

    public function showAppointment()
    {
        $doctors = User::whereRoleAndStatus("doctor", "active")->get();
        $users = User::whereRoleAndStatus("user", "active")->get();
        return view('admin.pages.appointments.create', [
            "doctors" => $doctors,
            "users" => $users,
        ]);
    }

    public function editAppointment($id)
    {
        $appointment = Appointment::with("patient", "doctor")->findOrFail($id);
        $doctors = User::whereRoleAndStatus("doctor", "active")->get();
        $users = User::whereRoleAndStatus("user", "active")->get();
        return view('admin.pages.appointments.edit',[
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
            "doctor_id" => $requestAppointment["doctor"],
            "patient_id" => $requestAppointment["user"],
            "status" => "pending",
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
        return redirect()->route('admin.appointments')->with('success','Appointment updated successfully');
    }

    public function deleteAppointment($id)
    {
        $user = Appointment::withTrashed()->find($id);
        $user->forceDelete();
        return redirect()->route('admin.appointments')->with('success', 'Appointment deleted successfully');
    }



//    Test

    public function listTests()
    {
        $tests = Test::with([
            "patient",
        ])->get();

        return view('admin.pages.tests.index',[
            "tests" => $tests,
        ]);
    }

    public function manageTest(Request $request,$id)
    {
        $appointments = Test::findOrfail($id);
        if($request->has('status')){
            $appointments->status = $request->status;
            $appointments->save();
            return redirect()->route('admin.appointments')->with('success','User status updated successfully');
        }
        return redirect()->route('admin.appointments')->with('error','Something went wrong');
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

        return redirect()->route('admin.tests')->with('success','Test created successfully');
    }

    public function showTest()
    {
        $doctors = User::whereRoleAndStatus("doctor", "active")->get();
        $users = User::whereRoleAndStatus("user", "active")->get();
        return view('admin.pages.tests.create', [
            "doctors" => $doctors,
            "users" => $users,
        ]);
    }

    public function editTest($id)
    {
        $test = Test::with("patient")->findOrFail($id);
        $users = User::whereRoleAndStatus("user", "active")->get();
        return view('admin.pages.tests.edit',[
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
        return redirect()->route('admin.tests')->with('success','Appointment updated successfully');
    }

    public function deleteTest($id)
    {
        $user = Test::withTrashed()->find($id);
        $user->forceDelete();
        return redirect()->route('admin.appointments')->with('success', 'Appointment deleted successfully');
    }

    public function generateTestPdf($id)
    {
        $test = Test::with("patient")->findOrFail($id);

        $pdf =$this->pdf->loadView("provider.pages.tests.report", ["data" => $test]);

        return $pdf->download("report");
    }



    //    Beds

    public function listBeds()
    {
        $tests = Bed::with([
            "patient",
            "doctor"
        ])->get();

        return view('admin.pages.beds.index',[
            "beds" => $tests,
        ]);
    }

    public function manageBed(Request $request,$id)
    {
        $appointments = Bed::findOrfail($id);
        if($request->has('status')){
            $appointments->status = $request->status;
            $appointments->save();
            return redirect()->route('admin.appointments')->with('success','User status updated successfully');
        }
        return redirect()->route('admin.beds')->with('error','Something went wrong');
    }

    public function storeBed(Request $request)
    {
        $requestAppointment = $request->all();
        $requestAppointment["status"] = "pending";
        $createData = [
            "bed_number" => $requestAppointment["bed_number"],
            "doctor_id" => $requestAppointment["doctor"],
            "patient_id" => $requestAppointment["user"],
            "comment" => $requestAppointment["comment"],
        ];
        $user = Bed::insert($createData);

        return redirect()->route('admin.beds')->with('success','User created successfully');
    }

    public function showBed()
    {
        $doctors = User::whereRoleAndStatus("doctor", "active")->get();
        $users = User::whereRoleAndStatus("user", "active")->get();
        return view('admin.pages.beds.create', [
            "doctors" => $doctors,
            "users" => $users,
        ]);
    }

    public function editBed($id)
    {
        $appointment = Bed::with("patient", "doctor")->findOrFail($id);
        $doctors = User::whereRoleAndStatus("doctor", "active")->get();
        $users = User::whereRoleAndStatus("user", "active")->get();
        return view('admin.pages.beds.edit',[
            'bed' => $appointment,
            "doctors" => $doctors,
            "users" => $users,
        ]);
    }

    public function updateBed(Request $request, $id)
    {
//        $requestUser = $request->validate([
//            "name" => "required|string",
//            "email" => "required|email",
//            "phone" => "required|string",
//            "address" => "required|string",
//            "password" => "required|string",
//        ]);
        $requestAppointment = $request->all();
        $appointment = Bed::findOrFail($id);
        $updateData = [
            "bed_number" => $requestAppointment["bed_number"],
            "doctor_id" => $requestAppointment["doctor"],
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
        return redirect()->route('admin.beds')->with('success','Appointment updated successfully');
    }

    public function deleteBed($id)
    {
        $user = Bed::withTrashed()->find($id);
        $user->forceDelete();
        return redirect()->route('admin.beds')->with('success', 'Appointment deleted successfully');
    }


}
