<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PurchaseController;

use App\Http\Controllers\AuthJWTController;
use App\Http\Controllers\UserController;

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

Route::get('/', function () {
    return view('welcome');
});

// Route::get('/verify/{token}', [AuthJWTController::class, 'verify']);
Route::get('/verify/{token}', [UserController::class, 'verify']);
Route::get('/resend/{token}', [UserController::class, 'resend']);
Route::get('/reminder/{token}', [UserController::class, 'reminder']);
// Route::post('/password_change', [AuthJWTController::class, 'password_change']);
