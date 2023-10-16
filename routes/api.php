<?php

use App\Http\Controllers\v1\Auth\AuthController;
use App\Http\Controllers\v1\Order\OrderController;
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

        // Clients
        Route::prefix('clients')->group(function () {

        });
        Route::resource('clients', '\App\Http\Controllers\v1\Client\ClientController')->only(['create', 'show', 'update']);

        // Orders
        Route::prefix('orders')->group(function () {
            Route::get('type', [\App\Http\Controllers\v1\Order\OrderTypeController::class, 'actionOrderType']);
            Route::post('index', [\App\Http\Controllers\v1\Order\OrderController::class, 'index']);
            Route::get('positions', [\App\Http\Controllers\v1\Order\GetFullPositionController::class, 'actionGetFullPosition']);
        });
        Route::resource('orders', '\App\Http\Controllers\v1\Order\OrderController')->only(['create', 'show', 'update', 'destroy']);

        // Office jobs
        Route::resource('office/metring', '\App\Http\Controllers\v1\Order\Job\MetringController')->only(['create', 'show', 'update', 'destroy']);
        Route::resource('office/design', '\App\Http\Controllers\v1\Order\Job\DesignController')->only(['create', 'show', 'update', 'destroy']);
        Route::resource('office/technologist', '\App\Http\Controllers\v1\Order\Job\TechnologistController')->only(['create', 'show', 'update', 'destroy']);

        Route::prefix('office/job')->group(function () {
        });

    });
});






// test
Route::get('/test', [\App\Console\Commands\Custom\CustomMigrationService::class, 'actionCustomMigration']);
Route::get('/test-mass', [\App\Console\Commands\Custom\CustomMigrationService::class, 'actionMassMigration']);
Route::get('/test-order', [OrderController::class, 'actionTest']);
// test end
