<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\DiscountCodeController;
use App\Http\Controllers\PaymentMethodController;
use App\Http\Controllers\DeliveryMethodController;





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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/products', [ProductController::class, 'index']); 
Route::get('/payment-methods', [PaymentMethodController::class, 'index']); 
Route::get('/delivery-methods', [DeliveryMethodController::class, 'index']); 
Route::post('/place-order', [OrderController::class, 'submitData']);
Route::post('/discount-code', [DiscountCodeController::class, 'checkDiscountStatus']);




