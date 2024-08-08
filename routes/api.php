<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthUserController;

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
});
