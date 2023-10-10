<?php

namespace App\Http\Controllers\v1\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\Client\CheckClientRequest;
use App\Models\v1\Client;

class ClientController extends Controller
{
    public function actionCheckClient(CheckClientRequest $request)
    {
        return Client::checkClient($request);
    }
}
