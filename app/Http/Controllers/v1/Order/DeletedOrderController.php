<?php

namespace App\Http\Controllers\v1\Order;

use App\Http\Controllers\Controller;
use App\Services\v1\Order\DeletedOrderService;
use Illuminate\Http\Request;

class DeletedOrderController extends Controller
{
    public function actionDeletedOrder(Request $request, DeletedOrderService $service)
    {
        return $service->deletedOrder($request->all());
    }
}
