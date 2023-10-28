<?php

namespace App\Services\v1\Order\Job\Factory;

use App\Models\v1\Job;

class DeletedJobPosition
{
    public function deletedPosition($id)
    {

        $job = Job::find($id);

        if ($job) {
            if($job->user_id != null){
                return response()->json(['message' => 'Нельзя удалить принятый заказ!'], 404);
            }
            $job->delete();
            return response()->json(['message' => 'Запись успешно удалена!']);
        } else {
            return response()->json(['message' => 'Не удалось удалить!'], 404);
        }
    }
}
