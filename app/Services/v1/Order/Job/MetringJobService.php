<?php

namespace App\Services\v1\Order\Job;

use App\Models\v1\Metring;

class MetringJobService
{
    public function takeOrder($request)
    {
        $order_id = $request['order_id'];
        return Metring::takeMetring($order_id);
    }
}
