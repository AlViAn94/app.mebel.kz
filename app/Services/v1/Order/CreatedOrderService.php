<?php

namespace App\Services\v1\Order;

use App\Models\v1\Order;
use Illuminate\Support\Facades\DB;

class CreatedOrderService
{
    public function createOrder($request)
    {
        DB::transaction(function () use ($request) {
            $client_id = $request['client_id'];
            $district = $request['district'];
            $address = $request['address'];
            $sum = $request['sum']?null:0;
            $type = $request['type'];
            $date_end = $request['date_end'];
            $comment = $request['comment'];

            $last = Order::getLastNum();
            $order_num = $last == null?201000:$last->order_num+1;

            // Создание заказа
            $order = Order::create([
                'client_id' => $client_id,
                'district' => $district['district'],
                'district_name' => $district['name'],
                'order_num' => $order_num,
                'address' => $address,
                'sum' => $sum,
                'type' => $type,
                'date_end' => $date_end,
                'comment' => $comment,
            ]);
            // Создаём шаблоны для заказа
           $order->createdAllPosition();
        });
        return response()->json(['message' => 'Заказ успешно создан!']);
    }
}
