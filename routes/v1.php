<?php

use App\Http\Controllers\v1\Admin\PassConfirmController;
use App\Http\Controllers\v1\Auth\AuthController;
use App\Http\Controllers\v1\Order\OrderController;
use Illuminate\Support\Facades\Route;

// version 1.0 API
Route::post('/login', [AuthController::class, 'actionLoginUser']);
Route::post('admin/user/confirm', [PassConfirmController::class, 'addPassword']);

Route::middleware('auth:api','tenant')->group(function (){
        // user interface
        Route::controller(AuthController::class)
            ->group(function (){
                Route::get('/refresh-token', 'actionRefreshToken');
                Route::get('/logout', 'actionLogoutUser');
                Route::get('/check-user', 'actionCheckUser');
            });

        // Admin
        Route::prefix('admin')->group(function (){
           Route::resource('users', '\App\Http\Controllers\v1\Admin\UsersController');
           Route::resource('positions', '\App\Http\Controllers\v1\Admin\UserPositionController');
           Route::get('orders', [App\Http\Controllers\v1\Admin\AdminOrdersController::class, 'list']);
        });

        // Clients
        Route::resource('clients', '\App\Http\Controllers\v1\Client\ClientController');

        // Orders
        Route::resource('orders', '\App\Http\Controllers\v1\Order\OrderController')->only(['create', 'show', 'update', 'destroy']);

        Route::prefix('order')->group(function () {
            Route::get('type', [\App\Http\Controllers\v1\Order\OrderTypeController::class, 'actionOrderType']);
            Route::post('list', [\App\Http\Controllers\v1\Order\OrderController::class, 'list']);
            Route::post('list/position', [\App\Http\Controllers\v1\Order\OrderController::class, 'listPosition']);
            Route::get('list/cards', [\App\Http\Controllers\v1\Order\GetFullPositionController::class, 'actionGetFullPosition']);
            Route::get('send/{id}', [\App\Http\Controllers\v1\Order\OrderController::class, 'send']);
            Route::get('completed/{id}', [\App\Http\Controllers\v1\Order\OrderController::class, 'completed']);
            Route::get('calendar', [\App\Http\Controllers\v1\Order\OrderCalendarController::class, 'calendar']);
        });

        // Statistic
        Route::prefix('statistic')->group(function () {
            Route::get('mix', [\App\Http\Controllers\v1\Statistics\StatisticController::class, 'statisticMix']);
        });

        // Office jobs
        Route::prefix('office')->group(function () {
            Route::post('take/order', [\App\Http\Controllers\v1\Order\Job\TakeOrderController::class, 'takeOrder']);
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
            Route::resource('card', '\App\Http\Controllers\v1\Order\Job\Factory\FactoryCardController');
            Route::post('submitted/order', [App\Http\Controllers\v1\Order\Job\SubmittedOrderController::class, 'submittedOrder']);
            Route::post('cancel/order', [App\Http\Controllers\v1\Order\Job\CancelOrderController::class, 'store']);
            Route::get('position', [App\Http\Controllers\v1\Order\Job\Factory\FactoryTypeController::class, 'index']);
            Route::get('users/list', [App\Http\Controllers\v1\Order\Job\Factory\FactoryDirController::class, 'index']);
            Route::post('appoint/user', [App\Http\Controllers\v1\Order\Job\Factory\FactoryDirController::class, 'store']);
        });

        // factory-dir

    Route::prefix('dir')->group(function (){

    });

});// middleware auth:api, tenant



// test
Route::get('/test', [\App\Console\Commands\Custom\CustomMigrationService::class, 'actionCustomMigration']);
Route::get('/test-mass', [\App\Console\Commands\Custom\CustomMigrationService::class, 'actionMassMigration']);
Route::get('/test-order', [OrderController::class, 'actionTest']);
// test end
