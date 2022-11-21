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
Route::post("/update/{id}",[UserController::class , 'update']);
Route::post("/delete/{id}",[UserController::class , 'delete']);


// Route::middleware('auth:sanctum')->post("/create-payment",[RazorpayController::class, 'createPayment']);

Route::group(['middleware' => 'checkRole:super_admin','auth:api', 'prefix' => 'admin' ], function() {
    Route::get("/list",[RazorpayController::class, 'listPayment']);
    Route::get("/user-list",[UserController::class, 'userDetails']);

});

