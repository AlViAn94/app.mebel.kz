<?php

namespace App\Services\v1\Order\Job\Factory\FactoryDir;

use App\Models\v1\Job;
use App\Models\v1\Role;
use Illuminate\Support\Facades\Auth;

class AppointUserCardService
{
    public function appointUser($request)
    {
        $id = $request['user_id'];
        $card_id = $request['card_id'];

        $user = Auth::user();
        $user_id = $user['id'];

        $roles = Role::getPositions($user_id);

        if (!in_array('foreman', $roles)) {
            return response()->json(['message' => 'Вы не можете взять заказ.'], 404);
        }

        $model = Job::whereId($card_id);

        if($model){
            $job = $model->select('user_id')->first();
            if($job->user_id != null){
                return response()->json(['message' => 'Работник уже назначен.'], 404);
            }
            $result = $model->update([
                'user_id' => $id,
                'status' => 1
            ]);
            if($result){
                return response()->json(['message' => 'Работник добавлен.']);
            }
            return response()->json(['message' => 'Не верные данные.'], 404);
        }

    }
}
