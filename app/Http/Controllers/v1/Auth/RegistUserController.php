<?php

namespace App\Http\Controllers\v1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\Auth\RgistAdminRequest;
use App\Services\v1\Auth\RegistAdminService;
use Illuminate\Http\JsonResponse;

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
