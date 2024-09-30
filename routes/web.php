<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Password;
use App\Http\Controllers\ResetController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\InfoUserController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\SessionsController;
use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\LayananController;
use App\Http\Controllers\ChangePasswordController;
use App\Http\Controllers\Admin\PelayananController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::group(['middleware' => ['auth:admin']], function () {
	Route::middleware(['admin'])->group(function () {
		Route::resource('karyawan', App\Http\Controllers\admin\AdminController::class);  
		Route::resource('pelayanan', App\Http\Controllers\admin\PelayananController::class); 
	});

    Route::get('/', [HomeController::class, 'home']);
	Route::get('dashboard', [HomeController::class, 'home'])->name('dashboard');
	
	Route::resource('layanan', App\Http\Controllers\admin\LayananController::class); 
	
	Route::get('/booking', [App\Http\Controllers\admin\BookingController::class, 'index'])->name('booking.index');
	Route::get('/booking/edit', [App\Http\Controllers\admin\BookingController::class, 'edit'])->name('booking.edit');
	Route::put('/booking/update', [App\Http\Controllers\admin\BookingController::class, 'update'])->name('booking.update');
	Route::delete('/booking/{id}', [App\Http\Controllers\admin\BookingController::class, 'destroy'])->name('booking.destroy');

	// Profile editing
	Route::post('/profile', [App\Http\Controllers\admin\ProfileController::class, 'index'])->name('profile.index');
	Route::put('/profile/edit', [App\Http\Controllers\admin\ProfileController::class, 'edit'])->name('profile.edit');
	Route::put('/profile/changePassword', [App\Http\Controllers\admin\ProfileController::class, 'changePassword'])->name('profile.changePassword');
	Route::put('/profile/settings', [App\Http\Controllers\admin\ProfileController::class, 'settings'])->name('profile.settings');

	Route::resource('laporan-mingguan', App\Http\Controllers\admin\LaporanMingguanController::class); 








    Route::get('/logout', [SessionsController::class, 'destroy']);
	Route::get('/user-profile', [InfoUserController::class, 'create']);
	Route::post('/user-profile', [InfoUserController::class, 'store']);
    Route::get('/login', function () {
		return view('dashboard');
	})->name('sign-up');
});



Route::group(['middleware' => 'guest'], function () {
    Route::get('/login', [SessionsController::class, 'create']);
    Route::post('/session', [SessionsController::class, 'store']);
	Route::get('/login/forgot-password', [ResetController::class, 'create']);
	Route::post('/forgot-password', [ResetController::class, 'sendEmail']);
	Route::get('/reset-password/{token}', [ResetController::class, 'resetPass'])->name('password.reset');
	Route::post('/reset-password', [ChangePasswordController::class, 'changePassword'])->name('password.update');

});

Route::get('/login', function () {
    return view('session/login-session');
})->name('login');