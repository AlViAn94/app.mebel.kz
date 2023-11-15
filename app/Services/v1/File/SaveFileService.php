<?php

namespace App\Services\v1\File;

use App\Models\v1\Connection;
use App\Models\v1\Design;
use App\Models\v1\Metring;
use App\Models\v1\Role;
use App\Models\v1\Technologist;
use Carbon\Carbon;
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

        // новый метод

        $connection_name = Connection::where('id', $user['connection_id'])->pluck('database');
        $date = Carbon::now();
        $year = $date->format('Y');
        $save_path = '/var/www/vhosts/app-mebel.kz/files/'. $year . '/' . $connection_name[0]  . '/' . $db . '/';
        $files_link = [];
        $i = 0;
        foreach ($files as $file){
            $extension = $file->getClientOriginalExtension();
            $file_name = Str::random(15) . $extension;
            $file_path = $save_path . $file_name;

            if (!File::exists($save_path)) {
                File::makeDirectory($save_path, 0755, true, true);
            }

            File::put($file_path, file_get_contents($file));

            $file_link = 'https://files.app-mebel.kz/'. $year . '/' . $connection_name[0]  . '/' . $db . '/' . $file_name;
            $files_link[$i] = $file_link;
        }

        // Удалите исходные файлы, если это необходимо
        foreach ($files as $file) {
            if (File::exists($file->getRealPath())) {
                File::delete($file->getRealPath());
            }
        }

        $service = new AddLinkDataBaseService();

        $result = $service->importFileLinkDb($model, $files_link, $db, $id);
        if ($result !== true) {
            return $result;
        } else {
            return response()->json(['message' => 'Файлы успешно сохранены!']);
        }
    }
}
