<?php

namespace App\Http\Controllers\v1\Order\Job;

use App\Http\Controllers\Controller;
use App\Services\v1\Order\Job\SubmittedOrderService;
use Illuminate\Http\Request;

class SubmittedOrderController extends Controller
{
    public function submittedOrder(Request $request, SubmittedOrderService $service)
    {
        return $service->submittedOrederSercice($request);
    }
}
