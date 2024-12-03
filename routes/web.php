<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Password;
use App\Http\Controllers\ResetController;
use App\Http\Controllers\SessionsController;
use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\PengaturanController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\BookingController;
use App\Http\Controllers\Admin\LayananController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\ChangePasswordController;
use App\Http\Controllers\Admin\PelayananController;
use App\Http\Controllers\Admin\LaporanBulananController;
use App\Http\Controllers\Admin\LaporanTahunanController;
use App\Http\Controllers\Admin\LaporanMingguanController;

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
		Route::resource('karyawan', App\Http\Controllers\Admin\AdminController::class);  
		Route::resource('pelayanan', App\Http\Controllers\Admin\PelayananController::class); 
		Route::get('/get-layanans', [App\Http\Controllers\Admin\PelayananController::class, 'getLayanans'])->name('getLayanans');

	});

	Route::get('/', [App\Http\Controllers\Admin\HomeController::class, 'home']);
	Route::get('dashboard', [App\Http\Controllers\Admin\HomeController::class, 'home'])->name('dashboard');
	Route::get('/check-loket', [HomeController::class, 'checkLoket']);

	
	Route::resource('layanan', App\Http\Controllers\Admin\LayananController::class); 
	
	Route::get('/booking', [App\Http\Controllers\Admin\BookingController::class, 'index'])->name('booking.index');
	Route::get('/booking/edit', [App\Http\Controllers\Admin\BookingController::class, 'edit'])->name('booking.edit');
	Route::put('/booking/update', [App\Http\Controllers\Admin\BookingController::class, 'update'])->name('booking.update');
	Route::get('/admin/new-bookings', [BookingController::class, 'getNewBookings'])->name('admin.getNewBookings');
	Route::get('/booking/search', [BookingController::class, 'search'])->name('booking.search');
	Route::get('/booking/fetch', [BookingController::class, 'fetchBookingData']);
	Route::delete('/booking/{id}', [App\Http\Controllers\Admin\BookingController::class, 'destroy'])->name('booking.destroy');

	// Profile editing
	Route::resource('profile', App\Http\Controllers\Admin\ProfileController::class); 
	Route::resource('pengaturan', App\Http\Controllers\Admin\PengaturanController::class); 

	
    Route::resource('laporan-mingguan', App\Http\Controllers\Admin\LaporanMingguanController::class); 
	Route::resource('laporan-bulanan', App\Http\Controllers\Admin\LaporanBulananController::class); 
	Route::resource('laporan-tahunan', App\Http\Controllers\Admin\LaporanTahunanController::class); 

    Route::get('/logout', [SessionsController::class, 'destroy']);
});

Route::group(['middleware' => 'guest'], function () {
    Route::get('/login', [SessionsController::class, 'create'])->name('login');
    Route::post('/session', [SessionsController::class, 'store']);
	Route::get('/login/forgot-password', [ResetController::class, 'create']);
	Route::post('/forgot-password', [ResetController::class, 'sendEmail']);
	Route::get('/reset-password/{token}', [ResetController::class, 'resetPass'])->name('password.reset');
	Route::post('/reset-password', [ChangePasswordController::class, 'changePassword'])->name('password.update');

});

Route::get('/', function () {
    return redirect()->route('login');
});
