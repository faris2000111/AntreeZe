<?php

use App\Http\Controllers\Mobile\ServiceController;
use App\Http\Controllers\Mobile\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('register', [UserController::class, 'register']);
Route::post('login', [UserController::class, 'login']);
Route::post('change_password', [UserController::class, 'changePassword']);
Route::post('update_profile', [UserController::class, 'updateProfile']);

Route::get('allservice', [ServiceController::class, 'getServiceList']);
Route::get('service/{id}', [ServiceController::class, 'getservicelistbyid']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
