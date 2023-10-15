<?php

namespace App\Http\Controllers\v1\Order;

use App\Services\v1\Order\SortOrderService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SortOrderController extends Controller
{
    public function index(Request $request, SortOrderService $service)
    {
        return $service->getOrdersService($request->all());
    }
}
