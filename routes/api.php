<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
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


/////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////// Auth Requests ////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////

// In Auth Controller
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('logout', [AuthController::class, 'logout'])->middleware('api');
Route::post('refresh', [AuthController::class, 'refresh'])->middleware('api');
Route::post('changePassword', [AuthController::class, 'changePassword'])->middleware('api');
/////////////////////////////////////////////////////////////////////////////////////

// In Auth Controller
Route::put('update_user/{user}', [UserController::class, 'update'])->middleware('api');
/////////////////////////////////////////////////////////////////////////////////////

/////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////// End Auth Requests //////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////




/////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////// Owner Requests ///////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////

// In User Countroller
Route::middleware(['api', 'owner'])->group(function () {
    Route::get('users', [UserController::class, 'index']);
    Route::get('user/{user}', [UserController::class, 'show']);
    Route::post('create_user', [UserController::class, 'store']);
    Route::delete('delete_user/{user}', [UserController::class, 'destroy']);
});
/////////////////////////////////////////////////////////////////////////////////////

/////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////// End Owner Requests /////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////






/////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////// Admin Requests ///////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////

Route::middleware(['api', 'owner', 'admin'])->group(function () {

    // In Brand Controller
    Route::post('create_brand', [BrandController::class, 'store']);
    Route::put('update_brand/{brand}', [BrandController::class, 'update']);
    Route::delete('delete_brand/{brand}', [BrandController::class, 'destroy']);
    /////////////////////////////////////////////////////////////////////////////////

    // In Product Controller
    Route::post('create_product', [ProductController::class, 'store']);
    Route::put('update_product/{product}', [ProductController::class, 'update']);
    Route::delete('delete_product/{product}', [ProductController::class, 'destroy']);
    /////////////////////////////////////////////////////////////////////////////////

    // In Order Countroller
    Route::get('orders', [OrderController::class, 'index']);
    Route::get('order/{order}', [OrderController::class, 'show']);
    /////////////////////////////////////////////////////////////////////////////////

});

/////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////// End Admin Requests ////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////







/////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////// Customer Requests //////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////

// In Brand Controller
Route::get('brands', [BrandController::class, 'index']);
Route::get('brand/{brand}', [BrandController::class, 'show']);
/////////////////////////////////////////////////////////////////////////////////////

// In Product Controller
Route::get('products', [ProductController::class, 'index']);
Route::get('product/{product}', [ProductController::class, 'show']);
/////////////////////////////////////////////////////////////////////////////////////

// In Order Countroller
Route::middleware('api')->group(function () {
    Route::get('my_orders', [OrderController::class, 'ordersByUser']);
    Route::get('my_order/{order}', [OrderController::class, 'orderByUser']);
    Route::post('create_order', [OrderController::class, 'store']);
});
/////////////////////////////////////////////////////////////////////////////////////

/////////////////////////////////////////////////////////////////////////////////////
////////////////////////////// End Customer Requests ////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////

