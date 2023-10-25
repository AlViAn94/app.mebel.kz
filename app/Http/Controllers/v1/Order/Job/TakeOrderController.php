<?php

namespace App\Http\Controllers\v1\Order\Job;

use App\Http\Controllers\Controller;
use App\Services\v1\Order\Job\TakeOrderService;
use Illuminate\Http\Request;

class TakeOrderController extends Controller
{
    public function takeOrder(Request $request, TakeOrderService $service)
    {
        return $service->takeOrederSercice($request->all());
    }
}
