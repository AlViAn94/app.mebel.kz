<?php

namespace App\Http\Controllers\v1\Order\Job;

use App\Http\Controllers\Controller;
use App\Services\v1\Order\Job\CancelOrderService;
use Illuminate\Http\Request;

class CancelOrderController extends Controller
{
    public function store(Request $request, CancelOrderService $service)
    {
        return $service->cancelOrder($request->all());
    }
}
