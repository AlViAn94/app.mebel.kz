<?php

use App\Http\Controllers\v1\Auth\AuthController;
use App\Http\Controllers\v1\Order\OrderController;
use Illuminate\Support\Facades\Route;

// version 1.0 API
    Route::post('/login', [AuthController::class, 'actionLoginUser']);

Route::middleware('auth:api')->group(function (){

        // user interface
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
        Route::prefix('office')->group(function () {
            Route::resource('metring', '\App\Http\Controllers\v1\Order\Job\MetringController')->only(['create', 'show', 'update', 'destroy']);
            Route::resource('design', '\App\Http\Controllers\v1\Order\Job\DesignController')->only(['create', 'show', 'update', 'destroy']);
            Route::resource('technologist', '\App\Http\Controllers\v1\Order\Job\TechnologistController')->only(['create', 'show', 'update', 'destroy']);
        });

        // Files controller
        Route::prefix('file')->group(function () {
            Route::controller(\App\Http\Controllers\v1\File\FileController::class)
                ->group(function () {
                    Route::post('save', 'save');
                    Route::get('download', 'download');
                    Route::post('update', 'update');
                    Route::get('deleted', 'deleted');
                });
        });

        // factory
        Route::prefix('factory')->group(function () {
            // create a new card for factory to accept a new order
            Route::resource('card', '\App\Http\Controllers\v1\Order\Job\Factory\FactoryCardController');
            // create new positions for the factory
            Route::resource('position', '\App\Http\Controllers\v1\Order\Job\Factory\FactoryTypeController');
        });

});// middleware auth:api



// test
Route::get('/test', [\App\Console\Commands\Custom\CustomMigrationService::class, 'actionCustomMigration']);
Route::get('/test-mass', [\App\Console\Commands\Custom\CustomMigrationService::class, 'actionMassMigration']);
Route::get('/test-order', [OrderController::class, 'actionTest']);
// test end
