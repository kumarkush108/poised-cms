<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;

Route::get('/', function () {
    return view('pages.home');
})->name('home');

Route::get('/about', function () {
    return view('pages.about');
})->name('about');

Route::get('/solution', function () {
    return view('pages.solutions');
})->name('solutions');

Route::get('/service', function () {
    return view('pages.services');
})->name('services');

Route::get('/contact', function () {
    return view('pages.contact');
})->name('contact');



Route::prefix('admin')->group(function () {

    Route::middleware('guest')->group(function () {

        Route::get('/login', [AuthController::class, 'login'])
            ->name('admin.login');

        Route::post('/login', [AuthController::class, 'loginSubmit'])
            ->middleware('throttle:5,1')
            ->name('admin.login.submit');

        Route::get('/forgot-password', function () {
            return view('admin.auth.forgot-password');
        })->name('admin.password.request');

        Route::post('/forgot-password', function (Request $request) {

            $request->validate([
                'email' => 'required|email'
            ]);

            return back()->with(
                'success',
                'Password reset link sent successfully.'
            );

        })->name('admin.password.email');

    });

    Route::middleware('auth')->group(function () {

        Route::get('/dashboard', function () {
            return view('admin.dashboard.index');
        })->name('admin.dashboard');

        Route::post('/logout', [AuthController::class, 'logout'])
            ->name('admin.logout');

    });

});