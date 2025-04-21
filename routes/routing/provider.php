<?php

use App\Http\Controllers\CkeditorController;
use App\Http\Controllers\provider\ProviderController;
use App\Http\Controllers\provider\ProviderDashboardController;
use App\Http\Controllers\provider\RequestedServiceController;
use Illuminate\Support\Facades\Route;


Route::group(['prefix' => 'provider-panel'],function(){
    Route::get('login',[ProviderDashboardController::class,'login'])->name('provider.login');
    Route::resource('providers',ProviderController::class);
    Route::post('login',[ProviderController::class,'verify'])->name('provider.verify');

    Route::group([],function(){
        Route::any('change-password/{id}',[ProviderController::class,'changePassword'])->name('provider.changePassword');

        Route::get('/logout',[ProviderDashboardController::class,'logout'])->name('provider.logout');
        Route::get('/',[ProviderDashboardController::class,'index'])->name('provider.dashboard');
        Route::post('ckeditor/image_upload', [CkeditorController::class,'upload'])->name('upload');
        Route::get('/request-list/{id}/{hid?}',[RequestedServiceController::class,'requestList'])->name('provider.requestList');
        Route::get('/request-list/soft_delete/{id}',[RequestedServiceController::class,'softDeleteRequest'])->name('provider.request.soft_delete');
        Route::get('/request-list/restore/{id}',[RequestedServiceController::class,'restoreRequest'])->name('provider.request.restore');
        Route::get('/request-list/delete/{id}',[RequestedServiceController::class,'deleteRequest'])->name('provider.request.delete');
        Route::patch('/request/manage/{id}',[RequestedServiceController::class,'manageRequest'])->name('provider.request.manage');


        // appointment

        Route::get("/appointments", [ProviderDashboardController::class, "listAppointment"])
            ->name("provider.appointments");
        Route::patch('/appointments/manage/{id}',[ProviderDashboardController::class,'manageAppointment'])
            ->name('provider.appointments.manage');
        Route::get("/appointments/show", [ProviderDashboardController::class, "showAppointment"])
            ->name("appointment.show");
        Route::post("/appointment", [ProviderDashboardController::class, "storeAppointment"])
            ->name("appointment.store");
        Route::get('/appointment/delete/{id}',[ProviderDashboardController::class,'deleteAppointment'])
            ->name('provider.appointments.delete');
        Route::get('/appointment/{appointment}',[ProviderDashboardController::class,'editAppointment'])
            ->name('provider.appointment.edit');
        Route::post('/appointment/{appointment}',[ProviderDashboardController::class,'updateAppointment'])
            ->name('provider.appointment.update');


        //Users Routes for Admin Panel (Admin Dashboard)
        Route::get('/users',[ProviderDashboardController::class,'listUsers'])
            ->name('provider.users');
        Route::get('/users/show',[ProviderDashboardController::class,'showUser'])
            ->name('provider.user.show');
        Route::post('/users',[ProviderDashboardController::class,'storeUser'])
            ->name('provider.user.store');
        Route::get('/users/{user}',[ProviderDashboardController::class,'editUser'])
            ->name('provider.user.edit');
        Route::post('/users/{user}',[ProviderDashboardController::class,'updateUser'])
            ->name('provider.user.update');
        Route::get('/users/soft_delete/{id}',[ProviderDashboardController::class,'softDeleteUser'])
            ->name('provider.users.soft_delete');
        Route::get('/users/restore/{id}',[ProviderDashboardController::class,'restoreUser'])
            ->name('provider.users.restore');
        Route::get('/users/delete/{id}',[ProviderDashboardController::class,'deleteUser'])
            ->name('provider.users.delete');
        Route::patch('/users/manage/{id}',[ProviderDashboardController::class,'manageUser'])
            ->name('provider.users.manage');


        // Test Route
        Route::get('/tests',[ProviderDashboardController::class,'listTests'])
            ->name('provider.tests');
        Route::get('/tests/show',[ProviderDashboardController::class,'showTest'])
            ->name('provider.tests.show');
        Route::post('/tests',[ProviderDashboardController::class,'storeTest'])
            ->name('provider.tests.store');
        Route::get('/tests/{tests}',[ProviderDashboardController::class,'editTest'])
            ->name('provider.tests.edit');
        Route::put('/tests/{tests}',[ProviderDashboardController::class,'updateTest'])
            ->name('provider.tests.update');
        Route::get('/tests/soft_delete/{id}',[ProviderDashboardController::class,'softDeleteTest'])
            ->name('provider.tests.soft_delete');
        Route::get('/tests/restore/{id}',[ProviderDashboardController::class,'restoreTest'])
            ->name('provider.tests.restore');
        Route::get('/tests/delete/{id}',[ProviderDashboardController::class,'deleteTest'])
            ->name('provider.tests.delete');
        Route::patch('/tests/manage/{id}',[ProviderDashboardController::class,'manageTest'])
            ->name('provider.tests.manage');
        Route::get("/test/{test_id}/report", [ProviderDashboardController::class, "generateTestPdf"])
            ->name("provider.test.report");

    });


});
