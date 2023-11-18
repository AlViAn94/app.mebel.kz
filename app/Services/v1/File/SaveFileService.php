<?php

namespace App\Services\v1\File;

use App\Models\v1\Connection;
use App\Models\v1\Design;
use App\Models\v1\Metring;
use App\Models\v1\OrderFile;
use App\Models\v1\Role;
use App\Models\v1\Technologist;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
class SaveFileService
{
    public function importFiles($files, $position, $order_id)
    {
        $user = Auth::user();
        $user_id = $user['id'];

        $roles = Role::getPositions($user_id);
        $error = 'У вас нет прав на это действие.';

        if (!in_array($position, $roles)) {
            return response()->json(['message' => $error]);
        }

        switch ($position){
            case 'metrings':
                $model = Metring::where('order_id', $order_id)->first();
                break;

            case 'design':
                $model = Design::where('order_id', $order_id)->first();
                break;

            case 'technologists':
                $model = Technologist::where('order_id', $order_id)->first();
            break;
        }

        if ($model) {
            $user = Auth::user();
            if ($model->user_id != $user['id']) {
                return response()->json(['message' => 'У вас нет прав на это действие!'], 404);
            }
        }

        // новый метод

        $connection_name = Connection::where('id', $user['connection_id'])->pluck('database');
        $date = Carbon::now();
        $year = $date->format('Y');
        $save_path = env('FILE_PATH'). $year . '/' . $connection_name[0]  . '/' . $position . '/';
        $files_link = [];
        $i = 0;

        if (!File::exists($save_path)) {
            File::makeDirectory($save_path, 0755, true, true);
        }

        foreach ($files as $file){
            $extension = $file->getClientOriginalExtension();
            $file_name = $file->getClientOriginalName();
            $file_exist = OrderFile::where('file_name', $file_name)->first();

            if ($file_exist) {
                $currentDateTime = Carbon::now()->format('YmdHis');
                $file_name = pathinfo($file_name, PATHINFO_FILENAME) . '_' . $currentDateTime . '.' . $extension;
            }

            $file_path = $save_path . $file_name;

            File::put($file_path, file_get_contents($file));

            chmod($file_path, 0644);

            $file_link = env('FILE_LINK'). $year . '/' . $connection_name[0]  . '/' . $position . '/' . $file_name;
            $files_link[$i]['type'] = $extension;

            $service = new AddLinkDataBaseService();
            $service->importFileLinkDb($model, $file_link, $position, $order_id, $extension, $file_name);

            $i++;
        }

        // Удалите исходные файлы, если это необходимо
        foreach ($files as $file) {
            if (File::exists($file->getRealPath())) {
                File::delete($file->getRealPath());
            }
        }



        return response()->json(['message' => 'Файлы успешно сохранены!']);
    }
}
