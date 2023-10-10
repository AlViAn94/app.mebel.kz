<?php

namespace App\Http\Controllers\v1\Order;

use App\Http\Controllers\Controller;
use App\Services\v1\Order\CreatedOrderService;
use Illuminate\Http\Request;

class CreatedOrderController extends Controller
{
    public function actionCreated(Request $request, CreatedOrderService $service)
    {
        return $service->createOrder($request->all());
    }
}
