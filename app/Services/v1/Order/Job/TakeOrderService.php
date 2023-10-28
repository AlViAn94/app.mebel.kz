<?php

namespace App\Services\v1\Order\Job;

use App\Models\v1\Design;
use App\Models\v1\Job;
use App\Models\v1\Metring;
use App\Models\v1\Order;
use App\Models\v1\Role;
use App\Models\v1\Technologist;
use Illuminate\Support\Facades\Auth;

class TakeOrderService
{
    public function takeOrederSercice($data)
    {
        $position = $data['position'];
        $user = Auth::user();
        $user_id = $user['id'];
        $roles = Role::getPositions($user_id);
        $error = 'Вы не можете взять заказ.';

        if (!in_array($position, $roles)) {
            return response()->json(['message' => $error], 404);
        }

        switch ($position){
            case 'metrings':
                $card = Metring::find($data['id']);
                if($card['user_id'] != null){
                    return response()->json(['message' => 'Заказ уже взят.'], 404);
                }
                $order_id = Metring::takeMetring($data);
                if(!$order_id){
                    return response()->json(['message' => $error], 404);
                }
                return Order::takeOrder($position, $order_id);

            case 'design':
                $card = Design::find($data['id']);
                if($card['user_id'] != null){
                    return response()->json(['message' => 'Заказ уже взят.'], 404);
                }
                $order_id = Design::takeDesign($data);
                if(!$order_id){
                    return response()->json(['message' => $error], 404);
                }
                return Order::takeOrder($position, $order_id);

            case 'technologists':
                $card = Technologist::find($data['id']);
                if($card['user_id'] != null){
                    return response()->json(['message' => 'Заказ уже взят.'], 404);
                }
                $order_id = Technologist::takeTechnologist($data);
                if(!$order_id){
                    return response()->json(['message' => $error], 404);
                }
                return Order::takeOrder($position, $order_id);

            default:
                $model = Job::takeOrderJob($data);
                if(!$model){
                    return response()->json(['message' => 'Заказ уже взят.'], 404);
                }
                    return response()->json(['message' => 'Вы взяли заказ.']);
        }
    }
}
