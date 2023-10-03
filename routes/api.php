<?php

use App\Http\Controllers\v1\Auth\AuthController;
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
// Для версии 1.0 API
Route::prefix('v1')->group(function () {
    Route::post('/login', [AuthController::class, 'actionLoginUser']);
});

Route::middleware('auth:api')->group(function (){
    Route::prefix('v1')->group(function () {
        Route::controller(AuthController::class)
            ->group(function (){
                Route::get('/refresh-token', 'actionRefreshToken');
                Route::get('/logout', 'actionLogoutUser');
                Route::get('/check-user', 'actionCheckUser');
            });
        Route::post('/reg-personal', [\App\Http\Controllers\v1\Auth\RegistUserController::class, 'actionRegistPersonal']);
    });
});
