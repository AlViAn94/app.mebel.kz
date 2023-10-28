<?php

namespace App\Http\Controllers\v1\Order\Job\Factory;

use App\Http\Controllers\Controller;
use App\Models\v1\PositionsType;
use App\Services\v1\Order\Job\Factory\Position\DeletePositionService;
use App\Services\v1\Order\Job\Factory\Position\NewPositionService;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;

class FactoryTypeController extends Controller
{

    /**
     * @return Builder|mixed
     * * @return \Illuminate\Http\Response
     */

    public function index()
    {
        return PositionsType::get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request, NewPositionService $service)
    {
        return $service->addPositionType($request);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id, DeletePositionService $service)
    {
        return $service->deletePositionType($id);
    }
}
