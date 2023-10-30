<?php

namespace App\Http\Controllers\v1\Order;

use App\Services\v1\Order\CompletedOrderService;
use App\Services\v1\Order\CreatedOrderService;
use App\Services\v1\Order\DeletedOrderService;
use App\Http\Requests\v1\Other\iDRequest;
use App\Http\Controllers\Controller;
use App\Services\v1\Order\SendOrderService;
use Illuminate\Http\Request;
use App\Models\v1\Client;
use App\Models\v1\Order;

class OrderController extends Controller
{
    public function list(Request $request)
    {
        return Order::list($request->all());
    }

    public function listPosition(Request $request)
    {
        return Order::listPosition($request->all());
    }

    public function create(Request $request, CreatedOrderService $service)
    {
        return $service->createOrder($request->all());
    }

    public function show(Request $request)
    {
        $id = $request->id;
        $result = [];
        $order = Order::findById($id);
        $client = Client::where('id', $order['client_id'])->first();
        $result['order'] = $order;
        $result['client'] = $client;
        return $result;

    }

    public function update(Request $request)
    {
        return Order::updateOrder($request->all());
    }

    public function destroy(iDRequest $request, DeletedOrderService $service)
    {
        return $service->deletedOrder($request->id);
    }

    public function send($id, SendOrderService $service)
    {
        return $service->sendOrder($id);
    }

    public function completed($id, CompletedOrderService $service)
    {
        return $service->completedOrder($id);
    }
}
