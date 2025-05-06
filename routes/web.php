<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return \Inertia\Inertia::render('Auth/Login');
})->middleware('guest');

Route::prefix('apps')->group(function() {

    Route::group(['middleware' => ['auth']], function () {

        Route::get('dashboard', App\Http\Controllers\Apps\DashboardController::class)->name('apps.dashboard');
    
    });
});