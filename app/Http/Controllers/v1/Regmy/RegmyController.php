<?php

namespace App\Http\Controllers\v1\Regmy;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\File\PhotoRequest;
use App\Models\v1\Regmy;

class RegmyController extends Controller
{
    public function reg(PhotoRequest $request)
    {
        $file = $request->file('file');
        $file->isValid();
        return Regmy::regMyImportPhoto($file);
    }

    public function list(PhotoRequest $request)
    {
        return Regmy::getList($request->all());
    }
}
