<?php

use App\Http\Controllers\v1\Auth\AuthController;
use App\Http\Controllers\v1\Order\OrderController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\v1\Client\ClientController;

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

        Route::controller(\App\Http\Controllers\v1\Client\CreatedClientController::class)
            ->group(function (){
                Route::post('/created-client', 'actionCreatedClient');
            });

        Route::post('/reg-personal', [\App\Http\Controllers\v1\Auth\RegistUserController::class, 'actionRegistPersonal']);

        Route::post('/new-order', [\App\Http\Controllers\v1\Order\CreatedOrderController::class, 'actionCreatedOrder']);

        Route::post('/deleted-order', [\App\Http\Controllers\v1\Order\DeletedOrderController::class, 'actionDeletedOrder']);

        Route::post('/test', [\App\Http\Controllers\v1\Order\CreatedOrderController::class, 'actionCreated']);

        Route::post('/full-position', [\App\Http\Controllers\v1\Order\GetFullPositionController::class, 'actionGetFullPosition']);

        Route::controller(ClientController::class)
            ->group(function (){
                Route::get('/check-client', 'actionCheckClient');
            });

        Route::controller(OrderController::class)
            ->group(function (){
                Route::get('/order-type', 'actionOrderType');
                Route::post('/confirm-order', 'actionConfirmOrder');
                Route::post('/processing-order', 'actionProcessing');
                Route::get('/order-sort', 'actionOrderSort');
                Route::post('/order-update', 'actionUpdate');
            });
    });
});

// test
Route::get('/test', [\App\Console\Commands\Custom\CustomMigrationService::class, 'actionCustomMigration']);
Route::get('/test-mass', [\App\Console\Commands\Custom\CustomMigrationService::class, 'actionMassMigration']);
// test end
