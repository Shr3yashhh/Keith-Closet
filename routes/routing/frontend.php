<?php

use App\Http\Controllers\frontend\FrontendController;
use Illuminate\Support\Facades\Route;

Route::get('/', [FrontendController::class, 'index'])->name('home');
Route::any('/find-providers', [FrontendController::class, 'homeSearch'])->name('home.search');
Route::post('/request-service', [FrontendController::class, 'requestService'])->name('request.service');
