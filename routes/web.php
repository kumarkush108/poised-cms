<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\MediaController;
use App\Http\Controllers\Admin\PageController as AdminPageController;
use App\Http\Controllers\Admin\PageSectionController;
use App\Http\Controllers\Admin\SectionItemController;
use App\Http\Controllers\Admin\ContactMessageController as AdminContactMessageController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\ContactMessageController;
use App\Http\Controllers\PageController;

Route::get('/', fn () => app(PageController::class)->show('home', 'pages.home'))
    ->name('home');

Route::get('/about', fn () => app(PageController::class)->show('about', 'pages.about'))
    ->name('about');

Route::get('/solution', fn () => app(PageController::class)->show('solutions', 'pages.solutions'))
    ->name('solutions');

Route::get('/service', fn () => app(PageController::class)->show('services', 'pages.services'))
    ->name('services');

Route::get('/contact', fn () => app(PageController::class)->show('contact', 'pages.contact'))
    ->name('contact');

Route::post('/contact', fn (Request $request) => app(ContactMessageController::class)->store($request, 'contact'))
    ->middleware('throttle:10,1')
    ->name('contact.submit');

Route::post('/appointment', fn (Request $request) => app(ContactMessageController::class)->store($request, 'home'))
    ->middleware('throttle:10,1')
    ->name('appointment.submit');



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

        Route::get('/dashboard', [DashboardController::class, 'index'])
            ->name('admin.dashboard');

        Route::post('/logout', [AuthController::class, 'logout'])
            ->name('admin.logout');

        Route::get('/media', [MediaController::class, 'index'])
            ->name('admin.media.index');

        Route::post('/media', [MediaController::class, 'store'])
            ->name('admin.media.store');

        Route::patch('/media/{media}', [MediaController::class, 'update'])
            ->name('admin.media.update');

        Route::delete('/media/{media}', [MediaController::class, 'destroy'])
            ->name('admin.media.destroy');

        Route::get('/settings', [SettingController::class, 'index'])
            ->name('admin.settings.index');

        Route::patch('/settings', [SettingController::class, 'update'])
            ->name('admin.settings.update');

        Route::get('/pages', [AdminPageController::class, 'index'])
            ->name('admin.pages.index');

        Route::get('/pages/{page}', [AdminPageController::class, 'edit'])
            ->name('admin.pages.edit');

        Route::patch('/pages/{page}', [AdminPageController::class, 'update'])
            ->name('admin.pages.update');

        Route::patch('/page-sections/{section}', [PageSectionController::class, 'update'])
            ->name('admin.page-sections.update');

        Route::post('/page-sections/{section}/items', [SectionItemController::class, 'store'])
            ->name('admin.section-items.store');

        Route::patch('/section-items/{item}', [SectionItemController::class, 'update'])
            ->name('admin.section-items.update');

        Route::delete('/section-items/{item}', [SectionItemController::class, 'destroy'])
            ->name('admin.section-items.destroy');

        Route::post('/section-items/{item}/move', [SectionItemController::class, 'move'])
            ->name('admin.section-items.move');

        Route::get('/contact-messages', [AdminContactMessageController::class, 'index'])
            ->name('admin.contact-messages.index');

        Route::get('/contact-messages/{contactMessage}', [AdminContactMessageController::class, 'show'])
            ->name('admin.contact-messages.show');

        Route::patch('/contact-messages/{contactMessage}/archive', [AdminContactMessageController::class, 'archive'])
            ->name('admin.contact-messages.archive');

    });

});