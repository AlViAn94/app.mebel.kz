<?php

namespace App\Services\v1\Order\Job;

use App\Models\v1\Design;
use App\Models\v1\Job;
use App\Models\v1\Metring;
use App\Models\v1\Order;
use App\Models\v1\Role;
use App\Models\v1\Technologist;
use Illuminate\Support\Facades\Auth;

class SubmittedOrderService
{
    public function submittedOrederSercice($data)
    {
        $position = $data['position'];
        $user = Auth::user();
        $user_id = $user['id'];

        $roles = Role::getPositions($user_id);
        $error = 'Вы не можете взять заказ.';
        if (!in_array($position, $roles)) {
            return response()->json(['message' => $error]);
        }
                $model = Job::submittedOrder($data);
                if(!$model){
                    return response()->json(['message' => $error]);
                }
                    return response()->json(['message' => 'Вы взяли заказ.']);
    }
}
