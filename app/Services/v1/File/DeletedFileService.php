<?php

namespace App\Services\v1\File;

use App\Models\v1\Connection;
use App\Models\v1\OrderFile;
use App\Models\v1\Role;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class DeletedFileService
{
    public function deletedFile($data)
    {
        $position = $data['position'];
        $file_id = $data['file_id'];

        $user = Auth::user();
        $user_id = $user['id'];
        $roles = Role::getPositions($user_id);

        if (!in_array($position, $roles)) {
            return response()->json(['message' => 'У вас нет прав на это действие.'], 404);
        }

        $file = OrderFile::find($file_id);
        $connection_name = Connection::where('id', $user['connection_id'])->pluck('database');
        $date = Carbon::now();
        $year = $date->format('Y');

        if ($file) {
            $file_name = $file->file_name;

            $delete_file = env('FILE_PATH') . $year . '/' . $connection_name[0]  . '/' . $position . '/' . $file_name;
            if (File::exists($delete_file)){
                File::delete($delete_file);
            }

            $result = $file->delete();

            if (!$result) {
                return response()->json(['message' => 'Файл не найден!'], 404);
            }
        }

        return response()->json(['message' => 'Файл успешно удален.']);
    }

}
