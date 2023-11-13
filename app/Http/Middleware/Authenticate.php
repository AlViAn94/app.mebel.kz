<?php

namespace App\Http\Middleware;

use App\Models\v1\User;
use Closure;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Facades\JWTAuth;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */

    /**
     * Модифицированный метод для обновления токена
     * Время жизни токена 48 часов
     * Если токену от 23 до 48 часов он отдаст новый токен
     */
//    public function handle($request, Closure $next, ...$guards)
//    {
//        try {
//            $token = JWTAuth::getToken();
//
//            if ($token) {
//                $payload = JWTAuth::getPayload($token)->toArray();
//                $issuedAt = $payload['iat'];
//                $tokenAge = time() - $issuedAt;
////                    if ($tokenAge > 60 * 60 * 23 && $tokenAge <= 60 * 60 * 48) {
//                    if ($tokenAge > 20 && $tokenAge <= 60) {
//                        try {
//                            $user = Auth::user();
//                            $user = User::chechEmail($user['email']);
//                            if (! $token = JWTAuth::fromUser($user)) {
//                                return response()->json(['message' => 'Could not create token'], 500);
//                            }
//                            return response()->json(['access_token' => $token], 407);
//                        } catch (\Exception $e) {
//                            return response()->json(['message' => $e], 403);
//                        }
//                    }
//            }
//        } catch (TokenExpiredException $e) {
//            return response()->json(['message' => $e], 403);
//        }
//        return parent::handle($request, $next, ...$guards);
//    }

    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {
            return route('login');
        }
    }
}
