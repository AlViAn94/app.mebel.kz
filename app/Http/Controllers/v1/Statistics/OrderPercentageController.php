<?php

namespace App\Http\Controllers\v1\Statistics;

use App\Http\Controllers\Controller;
use App\Models\v1\Order;

class OrderPercentageController extends Controller
{
    public static function statistic($period)
    {
        return Order::getOrdersPercentage($period);
    }
}
