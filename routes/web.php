<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SiteController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\TimesheetController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function (): void {
    Route::get('/', fn () => redirect('/login'));
    Route::resource('login', LoginController::class);
});

Route::middleware('auth')->group(function (): void {
    Route::resource('admin', AdminController::class);
    Route::resource('staff', StaffController::class);
    Route::resource('site', SiteController::class);
    Route::resource('user', UserController::class);
    Route::resource('timesheet', TimesheetController::class);
    Route::resource('company', CompanyController::class);
    Route::resource('report', ReportController::class);

    Route::get('changelocation', [UserController::class, 'changelocation'])->name('changelocation');
    Route::get('show_login/{site_id}/{id}', [UserController::class, 'show_login'])->name('show_login');
    Route::post('loginupdate', [UserController::class, 'loginupdate'])->name('loginupdate');
    Route::get('show_logout/{site_id}/{id}', [UserController::class, 'show_logout'])->name('show_logout');
    Route::post('logoutupdate', [UserController::class, 'logoutupdate'])->name('logoutupdate');
    Route::get('showlogoff/{userid}/{site_id}', [UserController::class, 'showlogoff'])->name('showlogoff');
    Route::get('force_logout/{id}/{site_id}', [UserController::class, 'force_logout'])->name('force_logout');
    Route::post('force_logout/{id}', [UserController::class, 'post_forcelogout'])->name('post_forcelogout');
    Route::get('post_edit/{site_id}/{userid}', [UserController::class, 'post_edit'])->name('post_edit');
    Route::post('post_site', [UserController::class, 'post_site'])->name('post_site');
    Route::post('assign_user', [SiteController::class, 'assign_user'])->name('assign_users');
    Route::get('logout', [LoginController::class, 'logout'])->name('logout');
    Route::post('timesheet_store', [TimesheetController::class, 'storeentry'])->name('storeentry');
    Route::post('timesheet_store_ajax', [TimesheetController::class, 'ajax_storeentry'])->name('ajax_storeentry');
});
