<?php

namespace App\Http\Controllers\v1\Order\Job\Factory;

use App\Http\Controllers\Controller;
use App\Services\v1\Order\Job\Factory\AddNewCard;
use App\Services\v1\Order\Job\Factory\DeletedJobPosition;
use App\Services\v1\Order\Job\Factory\UpdateJobPosition;
use Illuminate\Http\Request;

class FactoryCardController extends Controller
{

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, AddNewCard $service)
    {
        return $service->addPosition($request);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id, UpdateJobPosition $service)
    {
        return $service->updatePosition($request, $id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id, DeletedJobPosition $service)
    {
        return $service->deletedPosition($id);
    }
}
