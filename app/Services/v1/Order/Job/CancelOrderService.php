<?php

namespace App\Services\v1\Order\Job;

use App\Models\v1\Job;
use Illuminate\Support\Facades\Auth;

class CancelOrderService
{
    public function cancelOrder($data)
    {
        $user = Auth::user();
        $user_id = $user['id'];

        $job = Job::whereId($data['id'])->where('position', $data['position'])->first();
        if($job){
            if($job['user_id'] != $user_id){
                return response()->json(['message' => 'Вы не можете отменить заказ.'], 404);
            }
            $job->update([
               'take_date' => null,
               'status' => 0,
            ]);
            return response()->json(['message' => 'Вы отменили заказ.']);
        }
        return response()->json(['message' => 'Не верные данные.'], 404);
    }
}
