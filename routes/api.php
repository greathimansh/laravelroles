<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\RazorpayController;
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

Route::post("/register-user",[UserController::class , 'registerUser']);
Route::post("/login",[UserController::class , 'login']);
Route::post("/logout",[UserController::class , 'logout']);


// Route::middleware('auth:sanctum')->post("/create-payment",[RazorpayController::class, 'createPayment']);

Route::group(['middleware' => 'checkRole:super_admin', 'prefix' => 'admin' ], function() {
    Route::middleware('auth:sanctum')->get("/list",[RazorpayController::class, 'listPayment']);
    Route::middleware('auth:sanctum')->get("/user-list",[UserController::class, 'userDetails']);

});

