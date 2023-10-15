<?php

namespace App\Http\Controllers\v1\Order\Job;

use App\Http\Controllers\Controller;
use App\Services\v1\Order\Job\MetringJobService;
use Illuminate\Http\Request;

class MetringController extends Controller
{
    public function create(Request $request, MetringJobService $service)
    {
        return $service->takeOrder($request->all());
    }
}
