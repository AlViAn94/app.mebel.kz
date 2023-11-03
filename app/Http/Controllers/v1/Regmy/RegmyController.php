<?php

namespace App\Http\Controllers\v1\Regmy;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\File\PhotoRequest;
use App\Models\v1\Regmy;

class RegmyController extends Controller
{
    public function entrance(PhotoRequest $request)
    {
        $action = 'entrance';
        $file = $request->file('file');
        $fileName = $file->getClientOriginalName();
        return Regmy::regMyImportPhoto($request, $fileName, $action);
    }

    public function exit(PhotoRequest $request)
    {
        $action = 'exit';
        $file = $request->file('file');
        $fileName = $file->getClientOriginalName();
        return Regmy::regMyImportPhoto($request, $fileName, $action);
    }
}
