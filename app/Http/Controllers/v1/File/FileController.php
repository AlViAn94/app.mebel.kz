<?php

namespace App\Http\Controllers\v1\File;

use App\Http\Controllers\Controller;
use App\Services\v1\File\DeletedFileService;
use App\Services\v1\File\DownloadFileService;
use App\Services\v1\File\SaveFileService;
use App\Services\v1\File\UpdateFileService;
use Illuminate\Http\Request;

class FileController extends Controller
{
    public function save(Request $request, SaveFileService $service)
    {
        if ($request->hasFile('file') && $request->file('file')->isValid()) {
            return $service->importFile($request->file('file'), $request->dir, $request->id);
        } else {
            return response()->json(['message' => 'Ошибка загрузки файла'], 400);
        }
    }

    public function download(Request $request, DownloadFileService $service)
    {
        return $service->downloadFile($request->dir, $request->id);
    }

    public function update(Request $request, UpdateFileService $service)
    {
        if ($request->hasFile('file') && $request->file('file')->isValid()) {
            return $service->updateFile($request->file('file'), $request->dir, $request->id);
        } else {
            return response()->json(['message' => 'Ошибка загрузки файла'], 400);
        }
    }

    public function deleted(Request $request, DeletedFileService $service)
    {
        return $service->deletedFile($request->dir, $request->id);
    }
}
