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
                    $model = Metring::where('id', $id)->update([
                        'file' => $zipLink,
                        'status' => 2,
                        'passed_date' => $date
                    ]);
                    $order_id = Metring::whereId($id)->select('order_id')->first();
                    break;

                case 'design':
                    $model = Design::where('id', $id)->update([
                        'file' => $zipLink,
                        'status' => 2,
                        'passed_date' => $date
                    ]);
                    $order_id = Design::whereId($id)->select('order_id')->first();
                    break;

                case 'technologists':
                    $model = Technologist::where('id', $id)->update([
                        'file' => $zipLink,
                        'status' => 2,
                        'passed_date' => $date
                    ]);
                    $order_id = Technologist::whereId($id)->select('order_id')->first();
                    break;
            }
            Order::where('id', $order_id['order_id'])->update([
                $db => 2,
            ]);
            if($db == 'technologists'){
                Order::where('id', $order_id['order_id'])->update([
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
