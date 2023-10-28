<?php

namespace App\Http\Controllers\v1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\Admin\RequestConfirmPassword;
use App\Services\v1\Admin\ConfirmPasswordService;

class PassConfirmController extends Controller
{
    public function addPassword(RequestConfirmPassword $request, ConfirmPasswordService $service)
    {
        return $service->addPasswordService($request->all());
    }
}
