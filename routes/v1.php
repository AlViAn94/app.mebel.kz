<?php

use App\Http\Controllers\v1\Admin\PassConfirmController;
use App\Http\Controllers\v1\Auth\AuthController;
use App\Http\Controllers\v1\Order\OrderController;
use Illuminate\Support\Facades\Route;

// version 1.0 API
Route::post('/login', [AuthController::class, 'actionLoginUser']);
Route::post('admin/user/confirm', [PassConfirmController::class, 'addPassword']);

// landing
Route::prefix('landing')->group(function () {
    Route::resource('application', '\App\Http\Controllers\v1\Landing\ApplicationController')->only('create');
    Route::resource('comment/landing', '\App\Http\Controllers\v1\Landing\CommentClientController')->only('index', 'store', 'create', 'update');
    Route::get('check/order', [\App\Http\Controllers\v1\Landing\CheckOrderController::class, 'checkOrder']);
});

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
            Route::resource('comment', '\App\Http\Controllers\v1\Order\OrderCommentController')->only('index', 'create', 'destroy');
        });

        // Sklad
           Route::resource('/sklad', '\App\Http\Controllers\v1\Sklad\SkladController')->only('index', 'update', 'store', 'destroy');
           Route::post('/sklad/import', [\App\Http\Controllers\v1\Sklad\SkladController::class, 'importXls']);

        // Store
        Route::prefix('store')->group(function () {
            Route::post('/ticket', [\App\Http\Controllers\v1\Store\StoreController::class, 'getTicket']);
            Route::get('/list', [\App\Http\Controllers\v1\Store\StoreController::class, 'historyList']);
        });

        // RegMy
        Route::prefix('regmy')->group(function () {
            Route::post('registration', [\App\Http\Controllers\v1\Regmy\RegmyController::class, 'reg']);
            Route::post('list', [\App\Http\Controllers\v1\Regmy\RegmyController::class, 'list']);
            Route::get('check', [\App\Http\Controllers\v1\Regmy\RegmyController::class, 'checkReg']);
            Route::post('edit', [\App\Http\Controllers\v1\Regmy\RegmyController::class, 'addTime']);
        });
        // Statistic
        Route::prefix('statistic')->group(function () {
            Route::get('mix', [\App\Http\Controllers\v1\Statistics\StatisticController::class, 'statisticMix']);
            Route::get('graphic/{period}', [\App\Http\Controllers\v1\Statistics\StatisticController::class, 'graphicalStatistics']);
            Route::get('map', [\App\Http\Controllers\v1\Statistics\OrderMapStatisticController::class, 'mapStatistic']);
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
                    Route::get('list', 'getList');
                });
        });

        // factory
        Route::prefix('factory')->group(function () {
            Route::resource('card', '\App\Http\Controllers\v1\Order\Job\Factory\FactoryCardController');
            Route::post('submitted/order', [App\Http\Controllers\v1\Order\Job\SubmittedOrderController::class, 'submittedOrder']);
            Route::post('cancel/order', [App\Http\Controllers\v1\Order\Job\CancelOrderController::class, 'store']);
            Route::resource('position', '\App\Http\Controllers\v1\Order\Job\Factory\FactoryTypeController');
            Route::get('users/list', [App\Http\Controllers\v1\Order\Job\Factory\FactoryDirController::class, 'index']);
            Route::post('appoint/user', [App\Http\Controllers\v1\Order\Job\Factory\FactoryDirController::class, 'store']);
        });

        // location
        Route::get('location/list', [\App\Http\Controllers\v1\Locations\LocationControllers::class, 'list']);

    Route::prefix('landing')->group(function () {
        Route::resource('application', '\App\Http\Controllers\v1\Landing\ApplicationController')->only('index', 'update');
    });

});// middleware auth:api, tenant



// test
Route::get('/test', [\App\Console\Commands\Custom\CustomMigrationService::class, 'actionCustomMigration']);
Route::get('/test-mass', [\App\Console\Commands\Custom\CustomMigrationService::class, 'actionMassMigration']);
Route::get('/test-order', [OrderController::class, 'actionTest']);
// test end
