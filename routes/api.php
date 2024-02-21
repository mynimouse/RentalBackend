<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\RentController;
use App\Http\Controllers\TransaksiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::prefix('v1')->middleware(["auth:sanctum", 'isAdmin'])->group(function () {
    Route::resource('rent', RentController::class);
    Route::get('user', [AuthController::class, 'index']);
    Route::resource('transaksi', TransaksiController::class);
    Route::post('logout', [AuthController::class, 'logout']);
});


Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::get('forbidden', [AuthController::class, 'forbidden'])->name('forbidden');
