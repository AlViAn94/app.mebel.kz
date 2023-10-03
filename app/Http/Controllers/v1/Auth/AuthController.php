<?php

namespace App\Http\Controllers\v1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\Auth\AuthRequest;
use App\Services\v1\Auth\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{

    /**
     * @param AuthRequest $request
     * @param AuthService $service
     * @return JsonResponse
     */

    protected $AuthService;

    public function __construct(AuthService $AuthService)
    {
        $this->AuthService = $AuthService;
    }

    public function actionLoginUser(AuthRequest $request)
    {
        return $this->AuthService->login($request->all());
    }

    public function actionLogoutUser()
    {
        return $this->AuthService->logout();
    }

    public function actionRefreshToken()
    {
        return $this->AuthService->refreshToken();
    }
    public function actionCheckUser()
    {
        return Auth::user();
    }
}
