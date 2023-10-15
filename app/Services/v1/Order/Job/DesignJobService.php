<?php

namespace App\Services\v1\Order\Job;

use App\Models\v1\Design;

class DesignJobService
{
    public function takeOrder($request)
    {
        $order_id = $request['order_id'];
        return Design::takeDesign($order_id);
    }
}
