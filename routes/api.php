<?php

use App\Http\Controllers\Mobile\BookingController;
use App\Http\Controllers\Mobile\ServiceController;
use App\Http\Controllers\Mobile\ProfileController;
use App\Http\Controllers\Mobile\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::post('register', [UserController::class, 'register']);
Route::post('login', [UserController::class, 'login']);
Route::post('logout', [UserController::class, 'logout']);
Route::post('verified', [UserController::class, 'verifiedUser']);
Route::post('forgetpassword', [UserController::class, 'forgetPassword']);
Route::post('checkuseravailable', [UserController::class, 'checkUserExist']);
Route::post('validate-session', [UserController::class, 'validateSession']);


Route::post('change_password', [UserController::class, 'changePassword']);
Route::post('update_profile', [UserController::class, 'updateProfile']);

Route::get('allservice', [ServiceController::class, 'getServiceList']);
Route::get('service/{id}', [ServiceController::class, 'getservicelistbyid']);

Route::post('booking', [BookingController::class, 'getdateavailabe']);
Route::post('loket', [BookingController::class, 'checkAvailableLoket']);
Route::post('insertbooking', [BookingController::class, 'insertbooking']);
Route::post('gethistorybooking', [BookingController::class, 'getBookingHistory']);
Route::post('gethistorytoday', [BookingController::class, 'getBookingToday']);
Route::post('getlasthistorybooking', [BookingController::class, 'getLatestBookingHistory']);
Route::post('getTicketUser', [BookingController::class, 'getTicketUsers']);
Route::get('getbookuser', [BookingController::class, 'getUserBookChart']);
Route::post('getbookuserdate', [BookingController::class, 'getUserBookChartByDate']);
Route::post('updateBooking', [BookingController::class, 'updateBookingStatus']);

Route::get('getprofile', [ProfileController::class, 'getProfile']);
Route::get('getbanners', [ProfileController::class, 'getListBanners']);


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
