<?php

use App\Http\Controllers\admin\AdminController;
use App\Http\Controllers\admin\AdminDashboardController;
use App\Http\Controllers\admin\ProfessionController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\CkeditorController;
use App\Http\Middleware\AdminLoginAuth;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'admin-panel'],function(){
    Route::get("register", function () {
        return view("provider.register");
    });
    Route::get('login',[AdminDashboardController::class,'login'])
        ->name('admin.login');
    Route::resource('admins',AdminController::class);
    Route::post('login',[AdminController::class,'verify'])
        ->name('admin.verify');
    Route::any('forgot-password',[AdminController::class,'forgotPassword'])
        ->name('admin.forgotPassword');
    Route::any('reset-password',[AdminController::class,'resetPassword'])
        ->name('admin.resetPassword');

    Route::get('reset-login-attempt', [AdminController::class, "resetLoginAttempt"])->name('admin.reset.login.attempt');

    // Route::get("reset-password", [AdminController::class, "resetPassword"])->name("admin.reset.password");

    Route::middleware(['adminLoginAuth'])
        ->group(function(){
        Route::get('/logout',[AdminDashboardController::class,'logout'])
            ->name('admin.logout');
        Route::get('/',[AdminDashboardController::class,'index'])
            ->name('admin.dashboard');
        Route::post('ckeditor/image_upload', [CkeditorController::class,'upload'])
            ->name('upload');
        Route::any('change-password/{id}',[AdminController::class,'changePassword'])
            ->name('admin.changePassword');

        //Provider Routes
        Route::get('/user-admin',[AdminDashboardController::class,'listProviders'])
            ->name('admin.providers');
        Route::get('/user-admin/show',[AdminDashboardController::class,'showProvider'])
            ->name('admin.provider.show');
        Route::post('/user-admin',[AdminDashboardController::class,'storeProvider'])
            ->name('admin.provider.store');
        Route::get('/user-admin/{provider}',[AdminDashboardController::class,'editProvider'])
            ->name('admin.provider.edit');
        Route::put('/user-admin/{provider}',[AdminDashboardController::class,'updateProvider'])
            ->name('admin.provider.update');
        Route::get('/user-admin/soft_delete/{id}',[AdminDashboardController::class,'softDeleteProvider'])
            ->name('admin.providers.soft_delete');
        Route::get('/user-admin/restore/{id}',[AdminDashboardController::class,'restoreProvider'])
            ->name('admin.providers.restore');
        Route::get('/user-admin/delete/{id}',[AdminDashboardController::class,'deleteProvider'])
            ->name('admin.providers.delete');
        Route::patch('/user-admin/manage/{id}',[AdminDashboardController::class,'manageProvider'])
            ->name('admin.providers.manage');

        // appointment
        Route::get("/products", [AdminDashboardController::class, "listAppointment"])
            ->name("admin.appointments");
        Route::patch('/products/manage/{id}',[AdminDashboardController::class,'manageAppointment'])
            ->name('admin.appointments.manage');
        Route::get("/products/show", [AdminDashboardController::class, "showAppointment"])
            ->name("admin.appointment.show");
        Route::post("/product", [AdminDashboardController::class, "storeAppointment"])
            ->name("admin.appointment.store");
        Route::get('/product/delete/{id}',[AdminDashboardController::class,'deleteAppointment'])
            ->name('admin.appointments.delete');
        Route::get('/product/{appointment}',[AdminDashboardController::class,'editAppointment'])
            ->name('admin.appointment.edit');
        Route::post('/product/{appointment}',[AdminDashboardController::class,'updateAppointment'])
            ->name('admin.appointment.update');

        //Users Routes for Admin Panel (Admin Dashboard)
        Route::get('/users',[AdminDashboardController::class,'listUsers'])
            ->name('admin.users');
        Route::get('/users/show',[AdminDashboardController::class,'showUser'])
            ->name('admin.user.show');
        Route::post('/users',[AdminDashboardController::class,'storeUser'])
            ->name('admin.user.store');
        Route::get('/users/{user}',[AdminDashboardController::class,'editUser'])
            ->name('admin.user.edit');
        Route::put('/users/{user}',[AdminDashboardController::class,'updateUser'])
            ->name('admin.user.update');
        Route::get('/users/soft_delete/{id}',[AdminDashboardController::class,'softDeleteUser'])
            ->name('admin.users.soft_delete');
        Route::get('/users/restore/{id}',[AdminDashboardController::class,'restoreUser'])
            ->name('admin.users.restore');
        Route::get('/users/delete/{id}',[AdminDashboardController::class,'deleteUser'])
            ->name('admin.users.delete');
        Route::patch('/users/manage/{id}',[AdminDashboardController::class,'manageUser'])
            ->name('admin.users.manage');


        // Test Route
        Route::get('/stocks',[AdminDashboardController::class,'listTests'])
            ->name('admin.tests');
        Route::get('/stocks/show',[AdminDashboardController::class,'showTest'])
            ->name('admin.tests.show');
        Route::post('/tests',[AdminDashboardController::class,'storeTest'])
            ->name('admin.stock.store');
        Route::get('/stocks/{tests}',[AdminDashboardController::class,'editTest'])
            ->name('admin.tests.edit');
        Route::put('/stocks/{tests}',[AdminDashboardController::class,'updateTest'])
            ->name('admin.tests.update');
        Route::get('/stock/soft_delete/{id}',[AdminDashboardController::class,'softDeleteTest'])
            ->name('admin.tests.soft_delete');
        Route::get('/stocks/restore/{id}',[AdminDashboardController::class,'restoreTest'])
            ->name('admin.tests.restore');
        Route::get('/stocks/delete/{id}',[AdminDashboardController::class,'deleteTest'])
            ->name('admin.tests.delete');
        Route::patch('/stocks/manage/{id}',[AdminDashboardController::class,'manageTest'])
            ->name('admin.tests.manage');
        Route::get("/stocks/{test_id}/report", [AdminDashboardController::class, "generateTestPdf"])
            ->name("admin.test.report");


        // Bed Route
        Route::get('/warehouses',[AdminDashboardController::class,'listBeds'])
            ->name('admin.beds');
        Route::get('/warehouses/show',[AdminDashboardController::class,'showBed'])
            ->name('admin.beds.show');
        Route::post('/warehouses',[AdminDashboardController::class,'storeBed'])
            ->name('admin.beds.store');
        Route::get('/warehouses/{beds}',[AdminDashboardController::class,'editBed'])
            ->name('admin.beds.edit');
        Route::post('/warehouses/{beds}',[AdminDashboardController::class,'updateBed'])
            ->name('admin.beds.update');
        Route::get('/warehouses/soft_delete/{id}',[AdminDashboardController::class,'softDeleteBed'])
            ->name('admin.beds.soft_delete');
        Route::get('/warehouses/restore/{id}',[AdminDashboardController::class,'restoreBed'])
            ->name('admin.beds.restore');
        Route::get('/warehouses/delete/{id}',[AdminDashboardController::class,'deleteBed'])
            ->name('admin.beds.delete');
        Route::patch('/warehouses/manage/{id}',[AdminDashboardController::class,'manageBed'])
            ->name('admin.beds.manage');


        // orders
        // Bed Route
        Route::get('/orders',[AdminDashboardController::class,'listOrders'])
            ->name('admin.orders');
        Route::get('/orders/show',[AdminDashboardController::class,'showOrder'])
            ->name('admin.orders.show');
        Route::post('/orders',[AdminDashboardController::class,'storeOrder'])
            ->name('admin.orders.store');
        Route::get('/orders/{orders}',[AdminDashboardController::class,'editOrder'])
            ->name('admin.orders.edit');
        Route::post('/orders/{orders}',[AdminDashboardController::class,'updateOrder'])
            ->name('admin.orders.update');
        Route::get('/orders/soft_delete/{id}',[AdminDashboardController::class,'softDeleteOrder'])
            ->name('admin.orders.soft_delete');
        Route::get('/orders/restore/{id}',[AdminDashboardController::class,'restoreOrder'])
            ->name('admin.orders.restore');
        Route::get('/orders/delete/{id}',[AdminDashboardController::class,'deleteOrder'])
            ->name('admin.orders.delete');
        Route::patch('/orders/manage/{id}',[AdminDashboardController::class,'manageOrder'])
            ->name('admin.orders.manage');


        // donations
        Route::get('/donations',[AdminDashboardController::class,'listDonations'])
            ->name('admin.donations');
        Route::get('/donations/show',[AdminDashboardController::class,'showDonation'])
            ->name('admin.donations.show');
        Route::post('/donations',[AdminDashboardController::class,'storeDonation'])
            ->name('admin.donations.store');
        Route::get('/donations/{donations}',[AdminDashboardController::class,'editDonation'])
            ->name('admin.donations.edit');
        Route::post('/donations/{donations}',[AdminDashboardController::class,'updateOrder'])
            ->name('admin.donations.update');
        Route::get('/donations/soft_delete/{id}',[AdminDashboardController::class,'softDeleteOrder'])
            ->name('admin.donations.soft_delete');
        Route::get('/donations/restore/{id}',[AdminDashboardController::class,'restoreOrder'])
            ->name('admin.donations.restore');
        Route::get('/donations/delete/{id}',[AdminDashboardController::class,'deleteDonation'])
            ->name('admin.donations.delete');
        Route::patch('/donations/manage/{id}',[AdminDashboardController::class,'manageOrder'])
            ->name('admin.donations.manage');


        Route::get('/import',[AdminDashboardController::class,'importList'])
            ->name('admin.import');
        Route::get('/import/show',[AdminDashboardController::class,'showImport'])
            ->name('admin.import.show');
        Route::post('/import',[AdminDashboardController::class,'importDataStore'])
            ->name('admin.import.store');
        Route::get('/import/{import}',[AdminDashboardController::class,'editDonation'])
            ->name('admin.import.edit');
        Route::post('/import/{import}',[AdminDashboardController::class,'updateOrder'])
            ->name('admin.import.update');
        Route::get('/import/soft_delete/{id}',[AdminDashboardController::class,'softDeleteOrder'])
            ->name('admin.import.soft_delete');
        Route::get('/import/restore/{id}',[AdminDashboardController::class,'restoreOrder'])
            ->name('admin.import.restore');
        Route::get('/import/delete/{id}',[AdminDashboardController::class,'deleteDonation'])
            ->name('admin.import.delete');
        Route::patch('/import/manage/{id}',[AdminDashboardController::class,'manageOrder'])
            ->name('admin.import.manage');

        Route::get("generate-report", [AdminDashboardController::class, "generateReport"])
            ->name("admin.report.generate");


        // Profession Route Start
        Route::resource('professions',ProfessionController::class);
        Route::get('/professions/manage/{id}',[ProfessionController::class,'manageProfession'])
            ->name('admin.professions.manage');

        // Request Route Start
        Route::get('/requests',[AdminController::class,'requestList'])
            ->name('admin.requests');

    });


});

Route::post("/import/{import_type}", [AdminDashboardController::class, "importDataStore"])
    ->name("import.data");
    Route::get("report/download/{type}/type/{date}", [AdminDashboardController::class, "downloadReport"])
            ->name("admin.report.download");
