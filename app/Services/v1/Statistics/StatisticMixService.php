<?php

namespace App\Services\v1\Statistics;

use App\Models\v1\Client;
use App\Models\v1\Order;
use App\Models\v1\Role;
use App\Models\v1\User;
use Illuminate\Support\Facades\Auth;

class StatisticMixService
{
    public function getStatistic($request)
    {
        $user = Auth::user();
        $roles = Role::getPositions($user['id']);

        if (!in_array('dir', $roles)) {
            return response()->json(['message' => 'У вас нет доступа.'], 404);
        }

        if(!$order_sum = Order::getStatisticSum($request)){
            $order_sum = 0;
        }
        if(!$order_count = Order::getStatisticCount($request)){
            $order_count = 0;
        }
        if(!$client_count = Client::getNewClients($request))
        {
            $client_count = 0;
        }
        if(!$user_salary = User::getStatisticSalary())
        {
            $user_salary = 0;
        }
        return [
          'order_sum' => $order_sum,
          'order_count' => $order_count,
          'client' => $client_count,
          'salary' => $user_salary
        ];

    }
}
