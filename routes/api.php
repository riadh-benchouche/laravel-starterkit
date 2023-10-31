<?php

use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\UserController;
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

// Guest routes
Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);

Route::group(['middleware' => ['auth:sanctum']], static function () {
    //users
    Route::apiResource('users', UserController::class)->except(['update']);
    Route::put('users/{user}/profile', [UserController::class, 'updateProfile']);
    Route::put('users/{user}/account', [UserController::class, 'updateAccount']);
    Route::post('users/{user}/avatar', [UserController::class, 'createAvatar']);
    Route::delete('users/{user}/avatar', [UserController::class, 'deleteAvatar']);
});
