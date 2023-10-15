<?php

namespace App\Http\Controllers\v1\Order;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class OrderTypeController extends Controller
{
    public function actionOrderType(Request $request)
    {
        return DB::table('orders_type')->select('id', 'name')->get();
    }
}
