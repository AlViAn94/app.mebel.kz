<?php

namespace App\Http\Controllers\v1\Statistics;

use App\Http\Controllers\Controller;
use App\Services\v1\Statistics\StatisticMixService;
use Illuminate\Http\Request;

class StatisticController extends Controller
{
    public function statisticMix(Request $request, StatisticMixService $service)
    {
        return $service->getStatistic($request->all());
    }
}
