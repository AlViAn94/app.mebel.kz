<?php

namespace App\Services\v1\File;

use App\Models\v1\OrderFile;
use App\Models\v1\Role;
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

        if ($file) {
            $file_dir = $file->value('link');

            if (File::exists($file_dir)){
                File::delete($file_dir);
            }

            $result = $file->delete();

            if (!$result) {
                return response()->json(['message' => 'Файл не найден!'], 404);
            }
        }

        return response()->json(['message' => 'Файл успешно удален.']);
    }

}
