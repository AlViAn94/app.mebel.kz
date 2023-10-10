<?php

namespace App\Services\v1\Order;

use App\Models\v1\Order;
use Illuminate\Support\Facades\DB;

class DeletedOrderService
{
    public function deletedOrder($request)
    {
        DB::transaction(function () use ($request) {
            $id = $request['id'];
            $order = Order::findById($id);
            if($order->status < 2){
                if ($order) {
                    // Удаляем шаблоны для заказа
                    $order->deletedAllPosition();
                    // Удалить запись
                    $order->delete();
                    return response()->json(['message' => 'Запись успешно удалена']);
                } else {
                    return response()->json(['error' => 'Запись не найдена'], 404);
                }
            }else{
                return response()->json(['message' => 'Невозможно удалить заказ!']);
            }
        });
    }
}
