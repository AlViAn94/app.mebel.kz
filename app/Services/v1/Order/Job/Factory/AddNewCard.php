<?php

namespace App\Services\v1\Order\Job\Factory;

use App\Models\v1\Job;
use App\Models\v1\Order;
use App\Models\v1\User;
use Illuminate\Support\Facades\Auth;

class AddNewCard
{
    public function addPosition($request)
    {
        $order = $request->order_id;
        $position = $request->position;
        $position_name = $request->position_name;
        $user_id = $request->user_id;
        $order = Order::whereId($order)->first();

        $user = Auth::user();
        $connection = $user['connection_id'];
        $result = User::where('id', $user_id)->where('connection_id', $connection)->first();

        if(!$result){
            return response()->json(['message' => 'Работник не найден.'], 404);
        }

        if(!$order){
            return response()->json(['message' => 'Заказ не найден.'], 404);
        }else{
            Job::create([
                'order_id' => $order['id'],
                'position' => $position,
                'position_name' => $position_name,
                'user_id' => $user_id,
                'user_name' => $result['name']
            ]);
            return response()->json(['message' => 'Карточка добавлена.']);
        }
    }
}
