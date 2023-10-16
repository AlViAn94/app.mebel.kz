<?php

namespace App\Http\Controllers\v1\Order\Job;

use App\Services\v1\Order\Job\TechnologistJobService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TechnologistController extends Controller
{
    public function create(Request $request, TechnologistJobService $service)
    {
        return $service->takeOrder($request->all());
    }
}
