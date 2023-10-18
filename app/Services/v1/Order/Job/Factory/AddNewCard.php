<?php

namespace App\Services\v1\Order\Job\Factory;

use App\Models\v1\Job;

class AddNewCard
{
    public function addPosition($request)
    {
        $order = $request->order_id;
        $position = $request->position;

        $job = Job::create([
            'order_id' => $order,
            'position' => $position
        ]);

        return $job;
    }
}
