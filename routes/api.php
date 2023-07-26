<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\StaffController;
use App\Http\Controllers\API\MenuController;
use App\Http\Controllers\API\TableController;
use App\Http\Controllers\API\ReservationController;
use App\Http\Controllers\API\OrderController;
use App\Http\Controllers\API\PaymentController;

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
Route::get('users/verify/{id}', [UserController::class, 'verifyEmail'])->name('user.verify');

Route::middleware('auth:sanctum')->group(function () {
    //     Route::get('users', [UserController::class, 'index']);
    Route::get('users/{id}', [UserController::class, 'show']);
    Route::put('users/{id}', [UserController::class, 'update']);
    //     Route::delete('users/{id}', [UserController::class, 'destroy']);

    Route::post('menus', [MenuController::class, 'store'])->middleware('restrictRole:admin');
    Route::put('menus/{id}', [MenuController::class, 'update'])->middleware('restrictRole:admin');
    Route::delete('menus/{id}', [MenuController::class, 'destroy'])->middleware('restrictRole:admin');

    Route::post('staff', [StaffController::class, 'store'])->middleware('restrictRole:admin');
    Route::put('staff/{id}', [StaffController::class, 'update'])->middleware('restrictRole:admin');
    Route::delete('staff/{id}', [StaffController::class, 'destroy'])->middleware('restrictRole:admin');

    Route::post('tables', [TableController::class, 'store'])->middleware('restrictRole:admin');
    Route::put('tables/{id}', [TableController::class, 'update'])->middleware('restrictRole:admin');
    Route::delete('tables/{id}', [TableController::class, 'destroy'])->middleware('restrictRole:admin');

    Route::get('reservations', [ReservationController::class, 'index']);
    Route::get('reservations/{id}', [ReservationController::class, 'show']);
    Route::post('reservations', [ReservationController::class, 'store']);
    Route::put('reservations/{id}', [ReservationController::class, 'update']);
    Route::delete('reservations/{id}', [ReservationController::class, 'destroy']);

    Route::get('orders', [OrderController::class, 'index']);
    Route::get('orders/{id}', [OrderController::class, 'show']);
    Route::post('orders', [OrderController::class, 'store']);
    Route::put('orders/{id}', [OrderController::class, 'update']);
    Route::delete('orders/{id}', [OrderController::class, 'destroy']);

    Route::get('payments', [PaymentController::class, 'index']);
    Route::get('payments/{id}', [PaymentController::class, 'show']);
    Route::post('payments', [PaymentController::class, 'store']);
    Route::put('payments/{id}', [PaymentController::class, 'update']);
    Route::delete('payments/{id}', [PaymentController::class, 'destroy']);

});

Route::get('menus', [MenuController::class, 'index']);
Route::get('menus/{id}', [MenuController::class, 'show']);

Route::get('staff', [StaffController::class, 'index']);
Route::get('staff/{id}', [StaffController::class, 'show']);

Route::get('tables', [TableController::class, 'index']);
Route::get('tables/{id}', [TableController::class, 'show']);
