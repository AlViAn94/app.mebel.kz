<?php

namespace App\Services\v1\Order\Job\Factory;

use App\Models\v1\Job;
use App\Models\v1\Order;

class AddNewCard
{
    public function addPosition($request)
    {
        $order = $request->order_id;
        $position = $request->position;
        $order = Order::whereId($order)->first();

        if(!$order){
            return response()->json(['message' => 'Заказ не найден.']);
        }else{
            Job::create([
                'order_id' => $order['id'],
                'position' => $position
            ]);
            return response()->json(['message' => 'Карточка добавлена.']);
        }
    }
}
