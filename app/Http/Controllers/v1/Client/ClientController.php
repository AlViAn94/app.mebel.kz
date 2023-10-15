<?php

namespace App\Http\Controllers\v1\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\Client\CheckClientRequest;
use App\Http\Requests\v1\Client\ClientRequest;
use App\Models\v1\Client;
use App\Models\v1\UserActionLog;
use Illuminate\Http\Request;
class ClientController extends Controller
{
    public function actionCheckClient(CheckClientRequest $request)
    {
        return Client::checkClient($request);
    }
    public function create(ClientRequest $request)
    {
        $result = Client::newClient($request->all());

        if(empty($result['message']))
        {
            $text = 'добавил нового клиента: ' . $result['name'] . ' ' . $result['surname'];
            UserActionLog::createdLog($text);
            return $result;
        }
        return $result;
    }

    public function show(CheckClientRequest $request)
    {
        return Client::checkClient($request);
    }

    public function update(Request $request)
    {
        return Client::updateClient($request->all());
    }

    public function destroy()
    {
    }
}
