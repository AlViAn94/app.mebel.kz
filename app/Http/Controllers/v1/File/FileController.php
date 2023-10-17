<?php

namespace App\Http\Controllers\v1\File;

use App\Http\Controllers\Controller;
use App\Services\v1\File\ImportFileService;
use Illuminate\Http\Request;

class FileController extends Controller
{
    public function create(Request $request, ImportFileService $service)
    {
        if ($request->hasFile('file') && $request->file('file')->isValid()) {
            return $service->importFile($request->file('file'), $request->dir);
        } else {
            return response()->json(['message' => 'Ошибка загрузки файла'], 400);
        }
    }

    public function show()
    {

    }

    public function update()
    {

    }

    public function destroy()
    {

    }
}
