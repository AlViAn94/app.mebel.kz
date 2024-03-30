<?php

namespace App\Http\Controllers\v1\Store;

use App\Http\Controllers\Controller;
use App\Models\v1\Store;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    public function getTicket(Request $request): JsonResponse
    {
        return Store::ticket($request->json()->all());
    }

    public function historyList(Request $request): LengthAwarePaginator|JsonResponse
    {
        return Store::list($request->json()->all());
    }
}
