<?php

namespace App\Services\v1\File;

use App\Models\v1\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AddLinkDataBaseService
{
    public function importFileLincDb($zipLink, $db, $id)
    {
        $user = Auth::user();

        $model = DB::table($db)->where('order_id', $id)->first();
        if($model){
            if($model->user_id != $user['id']){
                return response()->json(['message' => 'У вас нет прав на это действие!'], 404);
            }
            $model = DB::table($db)->where('order_id', $id)->update([
                'file' => $zipLink,
                'status' => 2
            ]);
            Order::where('id', $id)->update([
                $db => 2,
                'status' => 1
            ]);
            if($db == 'technologists'){
                Order::where('id', $id)->update([
                    'status' => 2
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
