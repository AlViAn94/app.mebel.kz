<?php

namespace App\Http\Controllers\v1\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\Client\ClientRequest;
use App\Models\v1\Client;
use App\Models\v1\UserActionLog;

class CreatedClientController extends Controller
{
    public function actionCreatedClient(ClientRequest $request)
    {
        $result = Client::newClient($request->all());

        if(empty($result['message']))
        {
            $text = 'добавил нового клиента!';
            UserActionLog::createdLog($text);
            return $result;
        }
        return $result;
    }
}
