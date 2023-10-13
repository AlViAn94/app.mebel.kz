<?php

namespace App\Http\Controllers\v1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\Auth\AuthRequest;
use App\Services\v1\Auth\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
        $user = Auth::user();
        $data = DB::table('user_role')
            ->where('user_id', $user['id'])
            ->get();

        $role = [];
        $i = 0;

        foreach ($data as $v) {
            $role[$i] = $v->role;
            $i++;
        }
        $user['role'] = $role;

        return $user;
    }
}
