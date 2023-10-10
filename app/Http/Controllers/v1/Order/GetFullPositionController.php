<?php

namespace App\Http\Controllers\v1\Order;

use App\Http\Controllers\Controller;
use App\Services\v1\Order\GetFullPositionService;
use Illuminate\Http\Request;

class GetFullPositionController extends Controller
{
    public function actionGetFullPosition(Request $request, GetFullPositionService $service)
    {
        return $service->getFullPosition($request->all());
    }
}
