<?php

namespace App\Services\v1\File;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use ZipArchive;

class ImportFileService
{
    public function importFile($file, $dir, $id)
    {
        $zipName = Str::random(10) . '.zip';
        $savePath = public_path('downloads/files/' . $dir . '/');

        $zipPath = $savePath . $zipName;

        $zip = new ZipArchive();
        if ($zip->open($zipPath, ZipArchive::CREATE) === true) {
            $zip->addFile($file->getRealPath(), $file->getClientOriginalName());
            $zip->close();
        }

        if (File::exists($file->getRealPath())) {
            File::delete($file->getRealPath());
        }
        $zipLink = env('APP_URL') . '/' . $zipPath;

        $service = new AddLinkDataBaseService();

        $result = $service->importFile($zipLink, $dir, $id);

        if($result === false){
            return response()->json(['message' => 'Не удалось записать файл!'], 404);
        }else{
            return response()->json(['message' => 'Файл успешно сохранён!']);
        }

    }
}
