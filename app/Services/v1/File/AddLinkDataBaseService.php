<?php

namespace App\Services\v1\File;

use App\Models\v1\Design;
use App\Models\v1\File;
use App\Models\v1\Metring;
use App\Models\v1\Order;
use App\Models\v1\Technologist;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class AddLinkDataBaseService
{
    public function importFileLinkDb($file_link, $position, $id, $extension)
    {
        $user = Auth::user();
        $datetime = Carbon::now();
        $date = $datetime->format('Y-m-d H:i');

        if($model){
            if($model->user_id != $user['id']){
                return response()->json(['message' => 'У вас нет прав на это действие!'], 404);
            }

            $result = File::saveFile($file_link, $position, $id, $extension);
            if(!$result){
                return response()->json(['message' => 'bad request'], 400);
            }
            Order::where('id', $id)->update([
                $db => 2,
            ]);

            if($db == 'technologists'){
                Order::where('id', $id)->update([
                    'status' => 1
                ]);
            }
            if(!$model){
                return response()->json(['message' => 'Не удалось сохранить файл!'], 404);
            }
            return true;
        }
            return false;
    }
}
