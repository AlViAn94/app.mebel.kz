<?php

namespace App\Http\Controllers\v1\Order;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\Other\iDRequest;
use App\Models\v1\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function actionOrderType()
    {
        return DB::table('orders_type')->select('id', 'name')->get();
    }

    public function actionProcessing(IdRequest $request)
    {
        $id = $request['id'];
        return Order::where('id', $id)->update('status', 1);
    }

    public function actionConfirmOrder(IdRequest $request)
    {
        $id = $request['id'];
        return Order::where('id', $id)->update('status', 2);
    }

    public function actionOrderSort(Request $request)
    {
        return Order::getSortOrder($request->all());
    }

    public function actionUpdate(Request $request)
    {
        return Order::updateOrder($request->all());
    }
}
