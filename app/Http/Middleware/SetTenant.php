<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Multitenancy\Models\Concerns\UsesLandlordConnection;
use App\Models\v1\Connection;
use Illuminate\Support\Facades\Auth;

class SetTenant
{
    use UsesLandlordConnection;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\JsonResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if ($connectionId = $request->header('X-Connection-Id')) {
            $user = Auth::user();
            if ($user->connection_id == $connectionId) {
                $tenant = Connection::whereId($connectionId)->first();
                $tenant->makeCurrent();
            } else {
                return response()->json(['message' => 'Bad request'], 404);
            }
        }

        return $next($request);
    }
}
