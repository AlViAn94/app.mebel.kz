<?php

namespace App\Services\v1\Order\Job;

use App\Models\v1\Job;
use App\Models\v1\Role;
use Illuminate\Support\Facades\Auth;

class SubmittedOrderService
{
    public function submittedOrederSercice($data)
    {
        $position = $data['position'];
        $user = Auth::user();
        $user_id = $user['id'];
        $data['user_id'] = $user_id;

        $roles = Role::getPositions($user_id);
        if (!in_array($position, $roles)) {
            return response()->json(['message' => 'Вы не можете закончить этот заказ.']);
        }
                $model = Job::submittedOrder($data);
                if(!$model){
                    return response()->json(['message' => 'Что то пошло не так, обновите страницу.']);
                }
                    return response()->json(['message' => 'Вы сдали заказ.']);
    }
}
