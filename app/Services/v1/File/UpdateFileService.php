<?php

namespace App\Services\v1\File;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use ZipArchive;

class UpdateFileService
{
    public function updateFile($file, $db, $id)
    {
        $model = DB::table($db)->where('order_id', $id)->first();
        if($model){
            $user = Auth::user();
            if($model->user_id != $user['id']){
                return response()->json(['message' => 'У вас нет прав на это действие!'], 404);
            }
            // путь к старому файлу для удаления
            $old_link = $model->file;
        }

        $zipName = Str::random(10) . '.zip';
        $savePath = public_path('downloads/files/' . $db . '/');

        $zipPath = $savePath . $zipName;

        $zip = new ZipArchive();
        if ($zip->open($zipPath, ZipArchive::CREATE) === true) {
            $zip->addFile($file->getRealPath(), $file->getClientOriginalName());
            $zip->close();
        }

        if (File::exists($file->getRealPath())) {
            File::delete($file->getRealPath());
        }
        $zipLink = $zipPath;

        $service = new AddLinkDataBaseService();

        $result = $service->importFileLincDb($zipLink, $db, $id);
        if($result !== true){
            return $result;
        }else{
            if (file_exists($old_link)) {
                unlink($old_link);
            }
            return response()->json(['message' => 'Файл успешно сохранён!']);
        }
    }
}
