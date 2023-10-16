<?php

namespace App\Services\v1\Order\Job;

use App\Models\v1\Design;
use App\Models\v1\Technologist;

class TechnologistJobService
{
    public function takeOrder($request)
    {
        $order_id = $request['order_id'];
        return Technologist::takeTechnologist($order_id);
    }
}
