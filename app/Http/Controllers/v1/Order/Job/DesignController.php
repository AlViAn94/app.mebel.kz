<?php

namespace App\Http\Controllers\v1\Order\Job;

use App\Http\Controllers\Controller;
use App\Services\v1\Order\Job\DesignJobService;
use Illuminate\Http\Request;

class DesignController extends Controller
{
    public function create(Request $request, DesignJobService $service)
    {
        return $service->takeOrder($request->all());
    }
}
