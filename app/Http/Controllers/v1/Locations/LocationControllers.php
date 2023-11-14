<?php

namespace App\Http\Controllers\v1\Locations;

use App\Http\Controllers\Controller;
use App\Models\v1\District;
use Illuminate\Http\Request;

class LocationControllers extends Controller
{
    public function list(Request $request)
    {
        return District::list($request->all());
    }
}
