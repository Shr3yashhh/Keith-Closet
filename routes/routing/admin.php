<?php

use App\Http\Controllers\admin\AdminController;
use App\Http\Controllers\admin\AdminDashboardController;
use App\Http\Controllers\admin\ProfessionController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\CkeditorController;
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
    Route::any('reset-password/{token}',[AdminController::class,'resetPassword'])
        ->name('admin.resetPassword');

    Route::group(['middleware' => 'adminLoginAuth'],function(){
        Route::get('/logout',[AdminDashboardController::class,'logout'])
            ->name('admin.logout');
        Route::get('/',[AdminDashboardController::class,'index'])
            ->name('admin.dashboard');
        Route::post('ckeditor/image_upload', [CkeditorController::class,'upload'])
            ->name('upload');
        Route::any('change-password/{id}',[AdminController::class,'changePassword'])
            ->name('admin.changePassword');
        //Provider Routes
        Route::get('/providers',[AdminDashboardController::class,'listProviders'])
            ->name('admin.providers');
        Route::get('/providers/show',[AdminDashboardController::class,'showProvider'])
            ->name('admin.provider.show');
        Route::post('/providers',[AdminDashboardController::class,'storeProvider'])
            ->name('admin.provider.store');
        Route::get('/providers/{provider}',[AdminDashboardController::class,'editProvider'])
            ->name('admin.provider.edit');
        Route::put('/providers/{provider}',[AdminDashboardController::class,'updateProvider'])
            ->name('admin.provider.update');
        Route::get('/providers/soft_delete/{id}',[AdminDashboardController::class,'softDeleteProvider'])
            ->name('admin.providers.soft_delete');
        Route::get('/providers/restore/{id}',[AdminDashboardController::class,'restoreProvider'])
            ->name('admin.providers.restore');
        Route::get('/providers/delete/{id}',[AdminDashboardController::class,'deleteProvider'])
            ->name('admin.providers.delete');
        Route::patch('/providers/manage/{id}',[AdminDashboardController::class,'manageProvider'])
            ->name('admin.providers.manage');

        // appointment
        Route::get("/appointments", [AdminDashboardController::class, "listAppointment"])
            ->name("admin.appointments");
        Route::patch('/appointments/manage/{id}',[AdminDashboardController::class,'manageAppointment'])
            ->name('admin.appointments.manage');
        Route::get("/appointments/show", [AdminDashboardController::class, "showAppointment"])
            ->name("appointment.show");
        Route::post("/appointment", [AdminDashboardController::class, "storeAppointment"])
            ->name("appointment.store");
        Route::get('/appointment/delete/{id}',[AdminDashboardController::class,'deleteAppointment'])
            ->name('admin.appointments.delete');
        Route::get('/appointment/{appointment}',[AdminDashboardController::class,'editAppointment'])
            ->name('admin.appointment.edit');
        Route::post('/appointment/{appointment}',[AdminDashboardController::class,'updateAppointment'])
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
        Route::get('/tests',[AdminDashboardController::class,'listTests'])
            ->name('admin.tests');
        Route::get('/tests/show',[AdminDashboardController::class,'showTest'])
            ->name('admin.tests.show');
        Route::post('/tests',[AdminDashboardController::class,'storeTest'])
            ->name('admin.tests.store');
        Route::get('/tests/{tests}',[AdminDashboardController::class,'editTest'])
            ->name('admin.tests.edit');
        Route::put('/tests/{tests}',[AdminDashboardController::class,'updateTest'])
            ->name('admin.tests.update');
        Route::get('/tests/soft_delete/{id}',[AdminDashboardController::class,'softDeleteTest'])
            ->name('admin.tests.soft_delete');
        Route::get('/tests/restore/{id}',[AdminDashboardController::class,'restoreTest'])
            ->name('admin.tests.restore');
        Route::get('/tests/delete/{id}',[AdminDashboardController::class,'deleteTest'])
            ->name('admin.tests.delete');
        Route::patch('/tests/manage/{id}',[AdminDashboardController::class,'manageTest'])
            ->name('admin.tests.manage');
        Route::get("/test/{test_id}/report", [AdminDashboardController::class, "generateTestPdf"])
            ->name("admin.test.report");


        // Bed Route
        Route::get('/beds',[AdminDashboardController::class,'listBeds'])
            ->name('admin.beds');
        Route::get('/beds/show',[AdminDashboardController::class,'showBed'])
            ->name('admin.beds.show');
        Route::post('/beds',[AdminDashboardController::class,'storeBed'])
            ->name('admin.beds.store');
        Route::get('/beds/{beds}',[AdminDashboardController::class,'editBed'])
            ->name('admin.beds.edit');
        Route::post('/beds/{beds}',[AdminDashboardController::class,'updateBed'])
            ->name('admin.beds.update');
        Route::get('/beds/soft_delete/{id}',[AdminDashboardController::class,'softDeleteBed'])
            ->name('admin.beds.soft_delete');
        Route::get('/beds/restore/{id}',[AdminDashboardController::class,'restoreBed'])
            ->name('admin.beds.restore');
        Route::get('/beds/delete/{id}',[AdminDashboardController::class,'deleteBed'])
            ->name('admin.beds.delete');
        Route::patch('/beds/manage/{id}',[AdminDashboardController::class,'manageBed'])
            ->name('admin.beds.manage');



        // Profession Route Start
        Route::resource('professions',ProfessionController::class);
        Route::get('/professions/manage/{id}',[ProfessionController::class,'manageProfession'])
            ->name('admin.professions.manage');

        // Request Route Start
        Route::get('/requests',[AdminController::class,'requestList'])
            ->name('admin.requests');

    });


});
