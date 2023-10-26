<?php

namespace App\Services\v1\File;

use App\Models\v1\Design;
use App\Models\v1\Metring;
use App\Models\v1\Role;
use App\Models\v1\Technologist;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use ZipArchive;
class SaveFileService
{
    public function importFiles($files, $db, $id)
    {
        $user = Auth::user();
        $user_id = $user['id'];

        $roles = Role::getPositions($user_id);
        $error = 'У вас нет прав на это действие.';

        if (!in_array($db, $roles)) {
            return response()->json(['message' => $error]);
        }

        switch ($db){
            case 'metrings':
                $model = Metring::where('order_id', $id)->first();
                break;

            case 'design':
                $model = Design::where('order_id', $id)->first();
                break;

            case 'technologists':
                $model = Technologist::where('order_id', $id)->first();
            break;
        }

        if ($model) {
            $user = Auth::user();
            if ($model->user_id != $user['id']) {
                return response()->json(['message' => 'У вас нет прав на это действие!'], 404);
            }
            if ($model->file != null) {
                return response()->json(['message' => 'Файл уже добавлен!'], 404);
            }
        }

        $zipName = Str::random(15) . '.zip';
        $savePath = public_path('downloads/files/' . $db . '/');
        $zipPath = $savePath . $zipName;

        $zip = new ZipArchive();

        if ($zip->open($zipPath, ZipArchive::CREATE) === true) {
            foreach ($files as $file) {
                $zip->addFile($file->getRealPath(), $file->getClientOriginalName());
            }
            $zip->close();
        }

        // Удалите исходные файлы, если это необходимо
        foreach ($files as $file) {
            if (File::exists($file->getRealPath())) {
                File::delete($file->getRealPath());
            }
        }

        $zipLink = env('APP_URL') . '/downloads/files/' . $db . '/' . $zipName;

        $service = new AddLinkDataBaseService();

        $result = $service->importFileLinkDb($model, $zipLink, $db, $id);
        if ($result !== true) {
            return $result;
        } else {
            return response()->json(['message' => 'Файлы успешно сохранены!']);
        }
    }
}
