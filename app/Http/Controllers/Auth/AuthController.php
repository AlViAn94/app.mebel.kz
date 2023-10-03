<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\JsonResponse;
use App\Services\Auth\AuthService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Auth\AuthRequest;

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
