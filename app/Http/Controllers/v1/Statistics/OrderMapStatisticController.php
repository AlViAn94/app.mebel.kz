<?php

namespace App\Http\Controllers\v1\Statistics;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\v1\Order;

class OrderMapStatisticController extends Controller
{
    public static function mapStatistic(Request $request)
    {
        return Order::getMapStatistic($request->all());
    }
}
