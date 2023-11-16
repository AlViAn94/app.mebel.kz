<?php

namespace App\Services\v1\Auth;

use App\Models\v1\Role;
use App\Models\v1\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthService
{
    public function login($data): JsonResponse
    {
        try {
            $user = User::chechEmail($data['email']);

            if (! $user) {
                return response()->json(['message' => 'User not found'], 404);
            }

            if (! Hash::check($data['password'], $user->password)) {
                return response()->json(['message' => 'Invalid credentials'], 401);
            }

            if (! $token = JWTAuth::fromUser($user)) {
                return response()->json(['message' => 'Could not create token'], 500);
            }
        } catch (JWTException $e) {
            return response()->json(['message' => $e], 500);
        }

        $data = Role::where('user_id', $user['id'])->get();
        $role = [];
        $i = 0;

        $type_office = ['technologists', 'metrings', 'design', 'manager', 'dir', 'admin'];

        foreach ($data as $v) {
            switch ($v->role) {
                case in_array($v->role, $type_office):
                    $type = 'office';
                    break;
                default:
                    $type = 'work_shop';
                    break;
            }

            $role[$i]['to'] = '/' . $v->role;
            $role[$i]['title'] = $v->name;
            $role[$i]['type'] = $type;
            $i++;
        }

        return response()->json([
            'token' => $token,
            'user_id' => $user['id'],
            'name' => $user['name'],
            'connection' => $user['connection_id'],
            'role' => $role
            ]);
    }

    public function refreshToken(): JsonResponse
    {
        try {
            $newToken = JWTAuth::parseToken()->refresh();
            return response()->json(['access_token' => $newToken]);
        } catch (\Exception $e) {
            return response()->json(['message' => $e], 401);
        }
    }

    public function logout(): JsonResponse
    {
        JWTAuth::invalidate(JWTAuth::getToken());

        return response()->json(['message' => 'Logout successful']);
    }
}
