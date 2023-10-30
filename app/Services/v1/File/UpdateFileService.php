<?php

namespace App\Services\v1\File;

use App\Models\v1\Design;
use App\Models\v1\Metring;
use App\Models\v1\Technologist;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use ZipArchive;

class UpdateFileService
{
    public function importFiles($files, $db, $id)
    {
        switch ($db){
            case 'metrings':
                $model = Metring::where('id', $id)->first();
                break;

            case 'design':
                $model = Design::where('id', $id)->first();
                break;

            case 'technologists':
                $model = Technologist::where('id', $id)->first();
                break;
        }

        if($model){
            $user = Auth::user();
            if($model->user_id != $user['id']){
                return response()->json(['message' => 'У вас нет прав на это действие!'], 404);
            }
            $old_link = $model->file;
        }

        $zipName = Str::random(10) . '.zip';
        $savePath = public_path('downloads/files/' . $db . '/');
        $zipPath = $savePath . $zipName;

        $zip = new ZipArchive();

        if ($zip->open($zipPath, ZipArchive::CREATE) === true) {
            foreach ($files as $file) {
                $zip->addFile($file->getRealPath(), $file->getClientOriginalName());
            }
            $zip->close();
        }

        foreach ($files as $file) {
            if (File::exists($file->getRealPath())) {
                File::delete($file->getRealPath());
            }
        }

        $zipLink = env('APP_URL') . '/downloads/files/' . $db . '/' . $zipName;

        $service = new AddLinkDataBaseService();

        $result = $service->importFileLinkDb($model, $zipLink, $db, $id);
        if($result !== true){
            return $result;
        }else{
            if (file_exists($old_link)) {
                unlink($old_link);
            }
            return response()->json(['message' => 'Файл успешно заменён!']);
        }
    }
}
