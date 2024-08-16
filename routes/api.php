<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthUserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;

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

Route::post('/auth/register', [AuthUserController::class, 'register']);
Route::post('/auth/login', [AuthUserController::class, 'login']);

Route::middleware('auth:api')->group(function () {

    Route::delete('/destroy', [AuthUserController::class, 'destroy']);

    Route::prefix('admin')->group(function () {
        Route::post('/products', [ProductController::class, 'store']);
        Route::put('/products/{id}', [ProductController::class, 'update']);
        Route::get('/products', [ProductController::class, 'index']);
        Route::get('/products/{id}', [ProductController::class, 'show']);
        Route::delete('/products/{id}', [ProductController::class, 'destroy']);
        Route::post('/products/{id}/hide', [ProductController::class, 'hide']);
        Route::post('/products/{id}/unhide', [ProductController::class, 'unhide']);
        Route::get('/stock-notifications', [ProductController::class, 'stockNotifications']);
    });

    Route::prefix('customer')->group(function () {
        Route::get('/products', [ProductController::class, 'index']);
        Route::get('/products/{id}', [ProductController::class, 'show']);
        Route::post('/mock-orders', [OrderController::class, 'mockOrder']);
    });
    
});
