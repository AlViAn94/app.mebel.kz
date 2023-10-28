<?php

namespace App\Services\v1\Order;

use App\Models\v1\Order;
use Illuminate\Support\Facades\DB;

class DeletedOrderService
{
    public function deletedOrder($id)
    {
        return DB::transaction(function () use ($id) {
            $order = Order::findById($id);
            if ($order) {
                if ($order->status < 2) {
                    // Удаляем шаблоны для заказа
                    $order->deletedAllPosition();
                    // Удалить запись
                    $order->delete();
                    return response()->json(['message' => 'Запись успешно удалена']);
                } else {
                    return response()->json(['message' => 'Невозможно удалить заказ!']);
                }
            } else {
                return response()->json(['message' => 'Запись не найдена'], 404);
            }
        });
    }

}
