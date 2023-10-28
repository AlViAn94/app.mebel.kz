<?php

namespace App\Services\v1\Order;

use App\Models\v1\Order;
use App\Models\v1\Role;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class CompletedOrderService
{
    public function completedOrder($id)
    {
        $user = Auth::user();
        $user_id = $user['id'];

        $roles = Role::getPositions($user_id);

        if (!in_array('foreman', $roles)) {
            return response()->json(['message' => 'У вас нет прав на это действие.']);
        }


        $datetime = Carbon::now();
        $date = $datetime->format('Y-m-d H:i');

       $order = Order::find($id);
        if($order){

            if($order['status'] >= 2){
                return response()->json(['message' => 'Заказ уже завершён.'], 404);
            }

            $order->update([
                'status' => 2,
                'date_completed' => $date
            ]);
            return response()->json(['message' => 'Заказ завершён.']);
        }

        return response()->json(['message' => 'Заказ не найден.'], 404);
    }
}
