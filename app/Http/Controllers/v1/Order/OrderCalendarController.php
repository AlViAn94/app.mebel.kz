<?php

namespace App\Http\Controllers\v1\Order;

use App\Http\Controllers\Controller;
use App\Models\v1\Order;
use Illuminate\Http\Request;

class OrderCalendarController extends Controller
{
    public function calendar(Request $request)
    {
        return Order::calendar($request);
    }
}
