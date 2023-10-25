<?php

namespace App\Http\Controllers\v1\File;

use App\Http\Controllers\Controller;
use App\Services\v1\File\DeletedFileService;
use App\Services\v1\File\DownloadFileService;
use App\Services\v1\File\SaveFileService;
use App\Services\v1\File\UpdateFileService;
use Illuminate\Http\Request;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

class FileController extends Controller
{
    public function save(Request $request, SaveFileService $service)
    {
        $files = [];
        $index = 0;
        // Получите файлы и другие параметры
        while ($request->hasFile('file' . $index)) {
            $fileKey = 'file' . $index;
            $files[$fileKey] = $request->file($fileKey);
            $index++;
        }
        $db = $request->input('dir');
        $id = $request->input('id');

        // Ваш код обработки файлов и параметров
        $result = $service->importFiles($files, $db, $id);

        if ($result instanceof \Illuminate\Http\JsonResponse) {
            return $result;
        } else {
            return response()->json(['message' => 'Файлы успешно сохранены!']);
        }
    }

    public function download(Request $request, DownloadFileService $service)
    {
        return $service->downloadFile($request->dir, $request->id);
    }

    public function update(Request $request, UpdateFileService $service)
    {
        $files = [];
        $index = 0;
        // Получите файлы и другие параметры
        while ($request->hasFile('file' . $index)) {
            $fileKey = 'file' . $index;
            $files[$fileKey] = $request->file($fileKey);
            $index++;
        }
        $dir = $request->input('dir');
        $id = $request->input('id');

        // Ваш код обработки файлов и параметров
        $result = $service->importFiles($files, $dir, $id);

        if ($result instanceof \Illuminate\Http\JsonResponse) {
            return $result;
        } else {
            return response()->json(['message' => 'Файлы успешно сохранены!']);
        }
    }

    public function deleted(Request $request, DeletedFileService $service)
    {
        return $service->deletedFile($request->dir, $request->id);
    }
}
