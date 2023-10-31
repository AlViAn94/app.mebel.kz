<?php

namespace App\Http\Controllers\v1\Admin;

use App\Http\Controllers\Controller;
use App\Models\v1\Order;
use Illuminate\Http\Request;

class AdminOrdersController extends Controller
{
    public function list(Request $request)
    {
        return Order::getListDate($request);
    }
}
