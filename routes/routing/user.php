<?php

use App\Http\Controllers\CkeditorController;
use App\Http\Controllers\user\RequestedServiceController;
use App\Http\Controllers\user\UserController;
use App\Http\Controllers\user\UserDashboardController;
use Illuminate\Support\Facades\Route;


Route::group(['prefix' => 'user-panel'],function(){
    Route::get('login',[UserDashboardController::class,'login'])
        ->name('user.login');
    Route::resource('users',UserController::class);
    Route::post('login',[UserController::class,'verify'])
        ->name('user.verify');

    Route::group(['middleware' => 'userLoginAuth'],function(){
        Route::any('change-password/{id}',[UserController::class,'changePassword'])
            ->name('user.changePassword');

        Route::get('/logout',[UserDashboardController::class,'logout'])
            ->name('user.logout');
        Route::get('/',[UserDashboardController::class,'index'])
            ->name('user.dashboard');
        Route::post('ckeditor/image_upload', [CkeditorController::class,'upload'])
            ->name('upload');
        Route::resource('requested-services',RequestedServiceController::class);
        Route::get('/request-history/{id}/{hid?}',[RequestedServiceController::class,'requestHistory'])
            ->name('user.request.history');
        Route::get('/request-history/soft_delete/{id}',[RequestedServiceController::class,'softDeleteRequest'])
            ->name('user.request.soft_delete');
        Route::get('/request-history/restore/{id}',[RequestedServiceController::class,'restoreRequest'])
            ->name('user.request.restore');
        Route::get('/request-history/delete/{id}',[RequestedServiceController::class,'deleteRequest'])
            ->name('user.request.delete');
        Route::get('/request-history/manage/{id}',[RequestedServiceController::class,'manageRequest'])
            ->name('user.request.manage');
        Route::post('/provider-rating/{id}',[RequestedServiceController::class,'providerRating'])
            ->name('user.request.provider_rating');

        Route::get("appointments", [RequestedServiceController::class, "getAllAppointment"])
            ->name("user.appointments.index");
        Route::get("appointments/show", [RequestedServiceController::class, "showAppointment"])
            ->name("user.appointment.show");
        Route::post("appointments", [RequestedServiceController::class, "storeAppointment"])
            ->name("user.appointment.store");
        Route::get('/appointment/delete/{id}',[RequestedServiceController::class,'deleteAppointment'])
            ->name('user.appointments.delete');
        Route::get('/appointment/{appointment}',[RequestedServiceController::class,'editAppointment'])
            ->name('user.appointment.edit');
        Route::post('/appointment/{appointment}',[RequestedServiceController::class,'updateAppointment'])
            ->name('user.appointment.update');
    });
});
