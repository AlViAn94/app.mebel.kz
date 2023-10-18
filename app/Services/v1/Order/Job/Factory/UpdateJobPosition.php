<?php

namespace App\Services\v1\Order\Job\Factory;

use App\Models\v1\Job;

class UpdateJobPosition
{
    public function updatePosition($request, $id)
    {

        $job = Job::find($id);

        if ($job) {
            $job->update([
                'user_id' => null,
                'status' => 0
            ]);
            return response()->json(['message' => 'Карта успешно сброшена!']);
        } else {
            return response()->json(['error' => 'Не удалось сбросить!'], 404);
        }
    }
}
