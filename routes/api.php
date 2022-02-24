<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\OwnerController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\MailController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\GiftController;
use App\Http\Controllers\NewsController;
//
use App\Http\Controllers\AuthJWTController;

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

Route::middleware('auth:user, owner, admin')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('login', [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout']);
// // Route::post('refresh', [AuthController::class, 'refresh']);
// // Route::post('user', [UserController::class, 'user']);
Route::get('me', [AuthController::class, 'me']);

// //User
Route::get('all_user', [UserController::class, 'index']);
Route::post('register', [UserController::class, 'store']);

// //Menu
Route::get('menu', [MenuController::class, 'index']);
Route::post('menu', [MenuController::class, 'store']);
Route::post('menu/upload', [MenuController::class, 'upload']);
Route::get('menu/{id}', [MenuController::class, 'show']);
Route::put('menu/{id}', [MenuController::class, 'update']);
Route::delete('menu/{id}', [MenuController::class, 'destroy']);
//menu stock update
Route::put('menu/{owner_id}/update_stock', [MenuController::class, 'recievedStock']);

// //Genre
Route::get('genre', [MenuController::class, 'genre']);
// //product_code test
// Route::post('code', [MenuController::class, 'code']);

// //owner
Route::get('owner', [OwnerController::class, 'index']);
Route::get('owner/menu/{owner_id}', [MenuController::class, 'myMenu']);

// //Stripe
Route::get('checkout/{user_id}/{menu_id}/{quantity}', [CheckoutController::class, 'checkout']);


// //mail(create gift)
Route::post('send', [MailController::class, 'send']);
Route::post('admin/send', [MailController::class, 'admin_send']);
Route::get('send/test', [MailController::class, 'send_test']);


Route::post('purchase', [PurchaseController::class, 'store']);
// //後から消すかも（Gift次第）
// Route::get('purchase/{id}', [PurchaseController::class, 'show']);
// Route::put('purchase/{id}', [PurchaseController::class, 'update']);


// Route::get('purchase/make/url', [PurchaseController::class, 'searchPurchase'])->name('makeUrl');


// //gift
Route::post('gift', [GiftController::class, 'store']);
Route::get('gift/{url}', [GiftController::class, 'show']);

// //news
Route::get('news', [NewsController::class, 'index']);
Route::post('create/news', [NewsController::class, 'store']);



//test
// Route::post('register', [AuthJWTController::class, 'register']);
// Route::post('login',    [AuthJWTController::class, 'login']);
// Route::post('refresh',  [AuthJWTController::class, 'refresh']);
// Route::get('logout',    [AuthJWTController::class, 'logout']);
// Route::get('me',        [AuthJWTController::class, 'me']);
// Route::post('update',   [AuthJWTController::class, 'update']);
// Route::post('reminder', [AuthJWTController::class, 'reminder']);


//change password
// Route::post('change_password', [UserController::class, 'password_change']);
Route::post('change_password', [UserController::class, 'changePassword']);
Route::post('reminder', [UserController::class, 'reminder']);