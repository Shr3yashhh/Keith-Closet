<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Bed;
use App\Models\Import;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductWarehouse;
use App\Models\Profession;
use App\Models\RequestedService;
use App\Models\Test;
use App\Models\User;
use App\Models\Warehouse;
use Barryvdh\DomPDF\PDF;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;

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
        Auth::logout(); // This logs out the user from Laravel's auth
        Session::flush(); // Optional: clears all session data
        return redirect()->route('admin.login')->with('success', 'Logout Successfully');
    }
    public function listProviders()
    {
        $active_providers = User::where('role', 'admin')->where('status','active')->get();
        $new_providers = User::where('role', 'admin')->where('status','new')->get();
        $trashed_providers = User::onlyTrashed()->where('role', 'admin')->get();
        $suspended_providers = User::where('role', 'admin')->where('status','suspended')->get();
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
        // $requestUser = $request->validate([
        //     "name" => "required|string",
        //     "email" => "required|email",
        //     "phone" => "required|string",
        //     "address" => "required|string",
        //     "password" => "required|string",
        // ]);
        // $requestUser = $request->validate([
        //     'name' => 'required|min:3',
        //     'email' => 'required|email|unique:users,email',
        //     "phone" => "required|string",
        //     "address" => "required|string",
        //     'password' => [
        //         'required',
        //         'string',
        //         'min:8',
        //         'max:16',
        //         'confirmed',
        //         'regex:/^(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*#?&])[A-Za-z\d@$!%*#?&]{8,}$/'
        //     ],
        // ]);


        $validator = Validator::make($request->all(), [
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string',
            'address' => 'required|string',
            'password' => [
                'required',
                'string',
                'min:8',
                'max:16',
                'regex:/^(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*#?&])[A-Za-z\d@$!%*#?&]{8,}$/'
            ],
        ], [
            'password.regex' => 'Password must include at least one uppercase letter, one number, and one special character.',
        ]);

        $requestUser = $validator->validated();


        $requestUser["password"] = Hash::make($requestUser["password"]);
        $requestUser["role"] = "admin";
        $requestUser["status"] = "active";
        $use = User::insert($requestUser);

        return redirect()->route('admin.providers')->with('success','admin created successfully');
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
        $requestUser = $request->validate([
            "name" => "required|string",
            "email" => "required|email",
            "phone" => "required|string",
            "address" => "required|string",
            "password" => "required|string",
        ]);
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
        $appointments = Product::get();

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
            "name" => $requestAppointment["name"],
            "sku" => $requestAppointment["sku"],
            "size" => $requestAppointment["size"],
            "sex" => $requestAppointment["sex"],
            "category" => $requestAppointment["category"],
        ];
        $product = Product::insert($createData);

        return redirect()->route('admin.appointments')->with('success','Product created successfully');
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
        $appointment = Product::findOrFail($id);
        return view('admin.pages.appointments.edit',[
            'appointment' => $appointment,
            "doctors" => [],
            "users" => [],
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
        $appointment = Product::findOrFail($id);
        $updateData = [
            "name" => $requestAppointment["name"],
            "sku" => $requestAppointment["sku"],
            "size" => $requestAppointment["size"],
            "sex" => $requestAppointment["sex"],
            "category" => $requestAppointment["category"],
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
        return redirect()->route('admin.appointments')->with('success','Product updated successfully');
    }

    public function deleteAppointment($id)
    {
        $user = Product::find($id);
        $user->forceDelete();
        return redirect()->route('admin.appointments')->with('success', 'Product deleted successfully');
    }



    //    Test

    public function listTests(Request $request)
    {
        $query = ProductWarehouse::with(['warehouse', 'product']);

        if ($request->filled('product_id')) {
            $query->where('product_id', $request->input('product_id'));
        }

        if ($request->filled('warehouse_id')) {
            $query->where('warehouse_id', $request->input('warehouse_id'));
        }

        $tests = $query->get();

        $products = Product::all(); // For filter dropdown
        $warehouses = Warehouse::all();

        return view('admin.pages.tests.index', [
            'tests' => $tests,
            'products' => $products,
            'warehouses' => $warehouses,
            'selectedProduct' => $request->product_id,
            'selectedWarehouse' => $request->warehouse_id,
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
        // $requestAppointment = $request->all();
        // $requestAppointment["status"] = "pending";
        // $validated = Validator::make($requestAppointment, [
        //     'product' => ['required', 'integer', 'exists:products,id'],
        //     'warehouse' => ['required', 'integer', 'exists:warehouses,id'],
        //     'stock' => ['required', 'integer', 'min:1'],
        // ])->validate();

        $requestAppointment = $request->validate([
            'product' => ['required', 'integer', 'exists:products,id'],
            'warehouse' => ['required', 'integer', 'exists:warehouses,id'],
            'quantity' => ['required', 'integer', 'min:1'],
            "bin_location" => ['nullable', 'string'],
        ]);
        // $requestUser = $validated->validated();
        $createData = [
            "product_id" => $requestAppointment["product"],
            "warehouse_id" => $requestAppointment["warehouse"],
            "quantity" => $requestAppointment["quantity"],
            "bin_location" => $requestAppointment["bin_location"] ?? "",
        ];

        $existingStock = ProductWarehouse::where('product_id', $requestAppointment["product"])
            ->where('warehouse_id', $requestAppointment["warehouse"])
            ->first();

        if ($existingStock) {
            return redirect()->route('admin.tests.show')->with('exist','This product stock already exists in this warehouse');
        }
        $stock = ProductWarehouse::insert($createData);

        return redirect()->route('admin.tests')->with('success','Stock created successfully');
    }

    public function showTest()
    {
        $products = Product::get();
        $warehouses = Warehouse::get();
        $binLocation = config('bin_location');
        $binLocations = Arr::first($binLocation);
        // dd($binLocations);
        return view('admin.pages.tests.create', [
            "products" => $products,
            "warehouses" => $warehouses,
            "binLocations" => $binLocation,
        ]);
    }

    public function editTest($id)
    {
        $test = ProductWarehouse::with(["warehouse","product"])->findOrFail($id);
        $products = Product::get();
        $warehouses = Warehouse::get();
        $binLocation = config('bin_location');
        // $users = User::whereRoleAndStatus("user", "active")->get();
        return view('admin.pages.tests.edit',[
            'test' => $test,
            "products" => $products,
            "warehouses" => $warehouses,
            "binLocations" => $binLocation,
            // "users" => $users,
        ]);
    }

    public function updateTest(Request $request, $id)
    {
        $requestAppointment = $request->validate([
            'product' => ['required', 'integer', 'exists:products,id'],
            'warehouse' => ['required', 'integer', 'exists:warehouses,id'],
            'quantity' => ['required', 'integer', 'min:1'],
            "bin_location" => ['nullable', 'string'],
        ]);
        // $requestUser = $validated->validated();
        $createData = [
            "product_id" => $requestAppointment["product"],
            "warehouse_id" => $requestAppointment["warehouse"] ?? "",
            "quantity" => $requestAppointment["quantity"] ?? "",
            "bin_location" => $requestAppointment["bin_location"] ?? "",
        ];

        $existingStock = ProductWarehouse::where('product_id', $requestAppointment["product"])
            ->where('warehouse_id', $requestAppointment["warehouse"])
            ->first();

        if ($existingStock && $existingStock->id != $id) {
            return redirect()->back()->with('exist','This product stock already exists in this warehouse');
        }
        $stock = ProductWarehouse::findOrFail($id);
        $stock = $stock->update($createData);

        return redirect()->route('admin.tests')->with('success','Stock update successfully');
    }

    public function deleteTest($id)
    {
        $user = ProductWarehouse::find($id);
        $user->forceDelete();
        return redirect()->route('admin.appointments')->with('success', 'Stock deleted successfully');
    }

    // public function generateTestPdf($id)
    // {
      
    //     $pdf = $this->pdf->loadView("provider.pages.tests.report", ["data" => $test]);

    //     return $pdf->download("report");
    // }



    //    Beds

    public function listBeds()
    {
        $tests = Warehouse::get();

        return view('admin.pages.beds.index',[
            "beds" => $tests,
        ]);
    }

    public function manageBed(Request $request,$id)
    {
        $appointments = Warehouse::findOrfail($id);
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
        // $requestAppointment["status"] = "pending";
        $createData = [
            "name" => $requestAppointment["name"],
            "code" => $requestAppointment["code"],
            "address" => $requestAppointment["address"],
            "contact_number" => $requestAppointment["contact_number"],
        ];
        $user = Warehouse::insert($createData);

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
        $appointment = Warehouse::findOrFail($id);
        // $doctors = User::whereRoleAndStatus("doctor", "active")->get();
        // $users = User::whereRoleAndStatus("user", "active")->get();
        return view('admin.pages.beds.edit',[
            'bed' => $appointment,
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
        $appointment = Warehouse::findOrFail($id);
        $updateData = [
            "name" => $requestAppointment["name"],
            "code" => $requestAppointment["code"],
            "address" => $requestAppointment["address"],
            "contact_number" => $requestAppointment["contact_number"],
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
        $user = Warehouse::find($id);
        $user->forceDelete();
        return redirect()->route('admin.beds')->with('success', 'Appointment deleted successfully');
    }





    // orders

    public function listOrders()
    {
        $tests = Order::with(["senderWarehouse", "receiverWarehouse"])
            ->where("type", null)
            ->orWhere("type", "order")->get();

        return view('admin.pages.orders.index',[
            "orders" => $tests,
        ]);
    }

    public function manageOrder(Request $request,$id)
    {
        $appointments = Warehouse::findOrfail($id);
        if($request->has('status')){
            $appointments->status = $request->status;
            $appointments->save();
            return redirect()->route('admin.appointments')->with('success','User status updated successfully');
        }
        return redirect()->route('admin.orders')->with('error','Something went wrong');
    }

    public function storeOrder(Request $request)
    {
        DB::beginTransaction();

        try {
            $requestAppointment = $request->all();

            $requestAppointment = $request->validate([
                'from_warehouse_id' => ['required', 'integer', 'exists:warehouses,id'],
                'to_warehouse_id' => ['required', 'integer', 'exists:warehouses,id'],
                'items' => ['required', 'array'],
                'items.*.product_id' => ['required', 'integer', 'exists:products,id'],
                'items.*.quantity' => ['required', 'integer', 'min:1'],
            ]);

            // Calculate total quantity
            $totalQuantity = array_reduce($requestAppointment["items"], function ($carry, $item) {
                return $carry + (int) $item['quantity'];
            }, 0);

            // Stock availability check (BEFORE creating the order)
            foreach ($requestAppointment["items"] as $item) {
                $productWarehouseFrom = ProductWarehouse::where('product_id', $item['product_id'])
                    ->where('warehouse_id', $requestAppointment["from_warehouse_id"])
                    ->first();

                if (!$productWarehouseFrom || $productWarehouseFrom->quantity < $item['quantity']) {
                    throw new \Exception("Insufficient stock for product ID {$item['product_id']} in sender warehouse.");
                }
            }

            // Create the order
            $createData = [
                "sender_warehouse_id" => $requestAppointment["from_warehouse_id"],
                "receiver_warehouse_id" => $requestAppointment["to_warehouse_id"],
                "user_id" => auth()->user()->id,
                "quantity" => $totalQuantity,
                "status" => "processing",
            ];

            $order = Order::create($createData);

            // Handle inventory transfers
            foreach ($requestAppointment["items"] as $item) {
                // Reduce stock from sender
                $productWarehouseFrom = ProductWarehouse::where('product_id', $item['product_id'])
                    ->where('warehouse_id', $requestAppointment["from_warehouse_id"])
                    ->first();
                $productWarehouseFrom->decrement('quantity', $item['quantity']);

                // Add stock to receiver
                $productWarehouseTo = ProductWarehouse::where('product_id', $item['product_id'])
                    ->where('warehouse_id', $requestAppointment["to_warehouse_id"])
                    ->first();

                if ($productWarehouseTo) {
                    $productWarehouseTo->increment('quantity', $item['quantity']);
                } else {
                    ProductWarehouse::create([
                        'product_id' => $item['product_id'],
                        'warehouse_id' => $requestAppointment["to_warehouse_id"],
                        'quantity' => $item['quantity'],
                    ]);
                }

                // Create order item
                OrderItem::create([
                    "order_id" => $order->id,
                    "product_id" => $item['product_id'],
                    "quantity" => $item['quantity'],
                ]);
            }

            DB::commit();
            return redirect()->route('admin.orders')->with('success', 'Order created successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'Error: ' . $e->getMessage());
        }
    }


    public function showOrder()
    {
        $products = Product::get();
        $warehouses = Warehouse::get();
        return view('admin.pages.orders.create', [
            "products" => $products,
            "warehouses" => $warehouses,
        ]);
    }

    public function editOrder($id)
    {
        $order = Order::with("senderWarehouse", "receiverWarehouse", "orderItems.product")->findOrFail($id);
        // $appointment = Bed::with("patient", "doctor")->findOrFail($id);
        // $doctors = User::whereRoleAndStatus("doctor", "active")->get();
        // $users = User::whereRoleAndStatus("user", "active")->get();
        return view('admin.pages.orders.edit',[
            "order" => $order,
        ]);
    }

    public function updateOrder(Request $request, $id)
    {
       $requestUser = $request->validate([
           "status" => "required|string",
       ]);
        $requestOrder = $request->all();
        $order = Order::with("orderItems")->findOrFail($id);

        if (!$this->canChangeOrderStatus($order->status, $requestOrder["status"])) {
            return redirect()->back()->with('error', 'You cannot change the order status back to previous status.');
        }

        // Only process stock reversal if changing to "cancelled" from a different status
        if ($requestOrder["status"] === 'cancelled' && $order->status !== 'cancelled') {
            foreach ($order->orderItems as $item) {
                // Reverse stock from W2 -> W1

                // 1. Increase back to sender warehouse
                $productWarehouseFrom = ProductWarehouse::where('product_id', $item->product_id)
                    ->where('warehouse_id', $order->sender_warehouse_id)
                    ->first();

                if ($productWarehouseFrom) {
                    $productWarehouseFrom->increment('quantity', $item->quantity);
                } else {
                    // If not found, create record
                    ProductWarehouse::create([
                        'product_id' => $item->product_id,
                        'warehouse_id' => $order->sender_warehouse_id,
                        'quantity' => $item->quantity,
                    ]);
                }

                // 2. Decrease from receiver warehouse
                $productWarehouseTo = ProductWarehouse::where('product_id', $item->product_id)
                    ->where('warehouse_id', $order->receiver_warehouse_id)
                    ->first();

                if ($productWarehouseTo && $productWarehouseTo->quantity >= $item->quantity) {
                    $productWarehouseTo->decrement('quantity', $item->quantity);
                } else {
                    throw new \Exception("Receiver warehouse has insufficient stock to reverse for product ID {$item->product_id}");
                }
            }
        }


        $updateData = [
            "status" => $requestOrder["status"],
        ];

        $order->update($updateData);
        return redirect()->route('admin.orders')->with('success','Order status updated successfully');
    }

    function canChangeOrderStatus(string $current, string $next): bool
    {
        $flow = config('order_status.flow');

        $currentIndex = array_search($current, $flow);
        $nextIndex = array_search($next, $flow);

        // If not found or attempting to go back in flow
        if ($currentIndex === false || $nextIndex === false || $nextIndex < $currentIndex) {
            return false;
        }

        // Special case: allow cancelled at any stage before completed
        if ($next === 'cancelled' && $current !== 'completed') {
            return true;
        }

        return true;
    }

    public function deleteOrder($id)    {
        $user = Order::find($id);
        $user->forceDelete();
        return redirect()->route('admin.orders')->with('success', 'Order deleted successfully');
    }









    // donations

    public function listDonations()
    {
        $orders = Order::with(["senderWarehouse"])
            ->where("type", "donation")->get();

        return view('admin.pages.donations.index',[
            "orders" => $orders,
        ]);
    }

    public function manageDonation(Request $request,$id)
    {
        $appointments = Warehouse::findOrfail($id);
        if($request->has('status')){
            $appointments->status = $request->status;
            $appointments->save();
            return redirect()->route('admin.appointments')->with('success','User status updated successfully');
        }
        return redirect()->route('admin.orders')->with('error','Something went wrong');
    }

    public function storeDonation(Request $request)
    {
        DB::beginTransaction();

        try {
            $requestAppointment = $request->all();
            // dd($requestAppointment);

            $requestAppointment = $request->validate([
                'name' => 'required|string|max:255',
                'from_warehouse_id' => 'required|exists:warehouses,id',
                'items' => 'required|array|min:1',
                'items.*.product_id' => 'required|exists:products,id',
                'items.*.quantity' => 'required|integer|min:1',
            ]);

            // Calculate total quantity
            $totalQuantity = array_reduce($requestAppointment["items"], function ($carry, $item) {
                return $carry + (int) $item['quantity'];
            }, 0);

            // Stock availability check (BEFORE creating the order)
            foreach ($requestAppointment["items"] as $item) {
                $productWarehouseFrom = ProductWarehouse::where('product_id', $item['product_id'])
                    ->where('warehouse_id', $requestAppointment["from_warehouse_id"])
                    ->first();

                if (!$productWarehouseFrom || $productWarehouseFrom->quantity < $item['quantity']) {
                    throw new \Exception("Insufficient stock for product ID {$item['product_id']} in sender warehouse.");
                }
            }

            // Create the order
            $createData = [
                "sender_warehouse_id" => $requestAppointment["from_warehouse_id"],
                "user_id" => auth()->user()->id,
                "username" => $requestAppointment["name"],
                "quantity" => $totalQuantity,
                "type" => "donation",
                "status" => "complete",
            ];

            $order = Order::create($createData);
            // dd($order);

            // Handle inventory transfers
            foreach ($requestAppointment["items"] as $item) {
                // Reduce stock from sender
                $productWarehouseFrom = ProductWarehouse::where('product_id', $item['product_id'])
                    ->where('warehouse_id', $requestAppointment["from_warehouse_id"])
                    ->first();
                $productWarehouseFrom->decrement('quantity', $item['quantity']);

                // Create order item
                OrderItem::create([
                    "order_id" => $order->id,
                    "product_id" => $item['product_id'],
                    "quantity" => $item['quantity'],
                ]);
            }

            DB::commit();
            return redirect()->route('admin.donations')->with('success', 'Donations created successfully');

        } catch (\Illuminate\Validation\ValidationException $e) {
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'Error: ' . $e->getMessage());
        }
    }


    public function showDonation()
    {
        $products = Product::get();
        $warehouses = Warehouse::get();
        return view('admin.pages.donations.create', [
            "products" => $products,
            "warehouses" => $warehouses,
        ]);
    }

    public function editDonation($id)
    {
        $order = Order::with("senderWarehouse", "receiverWarehouse", "orderItems.product")->findOrFail($id);
        // $appointment = Bed::with("patient", "doctor")->findOrFail($id);
        // $doctors = User::whereRoleAndStatus("doctor", "active")->get();
        // $users = User::whereRoleAndStatus("user", "active")->get();
        return view('admin.pages.donations.edit',[
            "order" => $order,
        ]);
    }

    // public function updateDonation(Request $request, $id)
    // {
    //    $requestUser = $request->validate([
    //        "status" => "required|string",
    //    ]);
    //     $requestOrder = $request->all();
    //     $order = Order::with("orderItems")->findOrFail($id);


    //     $updateData = [
    //         "status" => $requestOrder["status"],
    //     ];

    //     $order->update($updateData);
    //     return redirect()->route('admin.orders')->with('success','Order status updated successfully');
    // }

    public function deleteDonation($id) {
        $user = Order::find($id);
        $user->forceDelete();
        return redirect()->route('admin.donations')->with('success', 'Donation deleted successfully');
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
                $details = [
                    'title' => 'Mail from Keith Closet',
                    'body' => 'Please click the link below to reset your password',
                    'link' => route('admin.resetPassword',$token)
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


    //import
    public function importList()
    {
        $import = Import::get();

        return view('admin.pages.import.index',[
            "import" => $import,
        ]);
    }

    public function showImport()
    {
        return view("admin.pages.import.create");
    }

    public function importDataStore(Request $request)
    {
        $data = $request->all();
        DB::beginTransaction();
        try {
            $csvToArray = $this->csvToArray(
                importType: $data["type"],
                filename: $data["file"]
            );

            $storeData = array_merge($data, [
                "created_by" => auth()->user()->id,
                "data" => $csvToArray,
                "status" => "pending"
            ]);

            // dd($storeData);
            $import = Import::create($storeData);


            // factory design pattern
            $dataImport = $this->getDataImport($import->type);
            $dataImport->make($import);
        } catch (Exception $exception) {
            DB::rollBack();
            return redirect()->back()->with("error", $exception->getMessage());
        }

        DB::commit();
        return redirect()->route("admin.import")->with("success", "import create successfully");
    }

    public function create(object $import): void
    {
        $dataImport = $this->getDataImport($import->type);
        $dataImport->make($import);
    }

    private function getDataImport(string $dataImportType)
    {
        $dataImportFactories = config("data_import");
        $dataImport = $dataImportFactories[$dataImportType];

        return resolve($dataImport["class"]);
    }

    public function csvToArray(string $importType, object|string $filename = "", string $delimiter = ','): array|bool
    {
        if (!file_exists($filename) || !is_readable($filename))
            return false;

        $header = null;
        $data = [];
        if (($handle = fopen($filename, 'r')) !== false)
        {
            while (($row = fgetcsv($handle, 1000, $delimiter)) !== false)
            {
                if (!$header) {
                    $importDataFields = array_column($this->getDataImportConfig()[$importType]["fields"], "identifier");
                    $header = array_map(function($header) {
                        return Str::slug($header, "_");
                    }, $row);

                    $missingImportDataFields = array_diff($importDataFields, $header);
                    throw_if(
                        condition: !empty($missingImportDataFields),
                        exception: new exception("These header fields are required: " . implode(', ', $missingImportDataFields))
                    );
                }
                else {
                    $data[] = array_combine($header, $row);
                }
            }
            fclose($handle);
        }

        return $data;
    }

    private function getDataImportConfig(): array
    {
        return config("data_import");
    }


    // reporting

    public function report()
    {
        $products = Product::get();
        $warehouses = Warehouse::get();
        $binLocation = config('bin_location');
        $binLocations = Arr::first($binLocation);
        return view('admin.pages.reports.index', [
            "products" => $products,
            "warehouses" => $warehouses,
            "binLocations" => $binLocation,
        ]);
    }

    public function downloadReport($type, $date)
    {
        $fileName = "inventory_report_{$type}_{$date}.pdf";
        $filePath = storage_path("app/reports/{$fileName}");

        if (!file_exists($filePath)) {
            abort(404, 'Report not found.');
        }

        return response()->download($filePath, $fileName, [
            'Content-Type' => 'application/pdf',
        ]);
    }

    public function generateReport(Request $request)
    {
        // $request->validate([
        //     'type' => 'required|in:daily,weekly,monthly',
        // ]);


        $data = $request->all();
        // dd($request->all());
        Artisan::call('reports:generate', [
            '--type' => $data["type"] ?? "daily",
        ]);
        // $type = $request->input('type');
        // $date = Carbon::parse($request->input('date'));
        // $startDate = $date->copy()->startOfDay();
        // $endDate = $date->copy()->endOfDay();

        // Generate report logic here
        // ...

        return redirect()->route("admin.dashboard")->with('success', 'Report generated successfully. Please check your email.');
    }
}
