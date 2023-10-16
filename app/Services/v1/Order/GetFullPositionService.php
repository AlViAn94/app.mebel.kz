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
                    $result = $order->getAllPosition($id);

                    return $result;
                } else {
                    return response()->json(['error' => 'Запись не найдена'], 404);
                }
    }
}
