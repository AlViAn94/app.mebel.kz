<?php

namespace App\Services\v1\File;

use App\Models\v1\Design;
use App\Models\v1\Metring;
use App\Models\v1\Order;
use App\Models\v1\Technologist;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class AddLinkDataBaseService
{
    public function importFileLinkDb($model, $zipLink, $db, $id)
    {
        $user = Auth::user();
        $datetime = Carbon::now();
        $date = $datetime->format('Y-m-d H:i');

        if($model){
            if($model->user_id != $user['id']){
                return response()->json(['message' => 'У вас нет прав на это действие!'], 404);
            }
            switch ($db){
                case 'metrings':
                    $model = Metring::where('order_id', $id)->update([
                        'file' => $zipLink,
                        'status' => 2,
                        'passed_date' => $date
                    ]);
                    break;

                case 'design':
                    $model = Design::where('order_id', $id)->update([
                        'file' => $zipLink,
                        'status' => 2,
                        'passed_date' => $date
                    ]);
                    break;

                case 'technologists':
                    $model = Technologist::where('order_id', $id)->update([
                        'file' => $zipLink,
                        'status' => 2,
                        'passed_date' => $date
                    ]);
                    break;
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
