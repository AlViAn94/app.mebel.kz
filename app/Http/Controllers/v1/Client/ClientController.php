<?php

namespace App\Http\Controllers\v1\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\Client\CheckClientRequest;
use App\Http\Requests\v1\Client\ClientRequest;
use App\Models\v1\Client;
use App\Models\v1\User;
use App\Models\v1\UserActionLog;
use Illuminate\Http\Request;
class ClientController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse|\LaravelIdea\Helper\App\Models\v1\_IH_User_C|User[]
     */
    public function index(Request $request)
    {
        return Client::getList($request->all());
    }

    public function create(ClientRequest $request)
    {
        $result = Client::newClient($request->all());

        if(empty($result))
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
}
