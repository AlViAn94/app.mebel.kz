<?php

namespace App\Http\Controllers\v1\Order;

use App\Http\Controllers\Controller;
use App\Models\v1\OrderType;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class OrderTypeController extends Controller
{
    public function actionOrderType()
    {
        return OrderType::get();
    }
}
