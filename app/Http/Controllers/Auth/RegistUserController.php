<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Services\Auth\RegistAdminService;
use App\Http\Requests\Auth\RgistAdminRequest;

class RegistUserController extends Controller
{

    /**
     * @param RgistAdminRequest $request
     * @param RegistAdminService $service
     * @return JsonResponse
     */

    public function actionRegistPersonal(RgistAdminRequest $request, RegistAdminService $service)
    {
        return $service->registration($request->all());
    }
}
