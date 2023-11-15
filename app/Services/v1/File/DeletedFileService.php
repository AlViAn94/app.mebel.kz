<?php

namespace App\Services\v1\File;

use App\Models\v1\Connection;
use App\Models\v1\Design;
use App\Models\v1\File;
use App\Models\v1\Metring;
use App\Models\v1\Order;
use App\Models\v1\Role;
use App\Models\v1\Technologist;
use Illuminate\Support\Facades\Auth;

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

        $result = File::whereId($file_id)->delete();
        if(!$result){
            return response()->json(['message' => 'Файл не найден!'], 404);
        }

        return response()->json(['message' => 'Файл успешно удален.']);
    }
}
