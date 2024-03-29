<?php

namespace App\Http\Controllers\v1\Store;

use App\Http\Controllers\Controller;
use App\Models\v1\Store;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    public function getTicket(Request $request) {

        $values = array_values($request['position']);

        $position_string = implode(',', $values);

        return Store::insert([
            'position' => $position_string,
            'sum' => $request['sum']
        ]);
    }
}
