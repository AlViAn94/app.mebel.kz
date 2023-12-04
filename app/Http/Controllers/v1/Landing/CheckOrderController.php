<?php

namespace App\Http\Controllers\v1\Landing;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\Landing\CheckOrderRequest;
use App\Models\v1\Order;

class CheckOrderController extends Controller
{
    public function checkOrder(CheckOrderRequest $request)
    {
        if($result = Order::where('order_num', $request->order_num)->select('status')->first()){
            return $result;
        }else{
            return response()->json(['message' => 'Заказ не найден.'], 404);
        }
    }
}
