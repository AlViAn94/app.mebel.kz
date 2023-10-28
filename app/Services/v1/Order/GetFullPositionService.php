<?php

namespace App\Services\v1\Order;

use App\Models\v1\Order;
use Illuminate\Support\Facades\DB;

class GetFullPositionService
{
    public function getFullPosition($request)
    {
            $id = $request['id'];
            $order = Order::findById($id);
                if ($order) {
                    $result = $order->getAllPosition($order['id']);
                    return $result;
                } else {
                    return response()->json(['message' => 'Запись не найдена'], 404);
                }
    }
}
