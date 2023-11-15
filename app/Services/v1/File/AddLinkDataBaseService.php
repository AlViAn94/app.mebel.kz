<?php

namespace App\Services\v1\File;

use App\Models\v1\Design;
use App\Models\v1\OrderFile;
use App\Models\v1\Metring;
use App\Models\v1\Order;
use App\Models\v1\Technologist;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class AddLinkDataBaseService
{
    public function importFileLinkDb($model, $file_link, $position, $order_id, $extension, $file_name)
    {
        $user = Auth::user();
        $datetime = Carbon::now();
        $date = $datetime->format('Y-m-d H:i');

        if($model){
            if($model->user_id != $user['id']){
                return response()->json(['message' => 'У вас нет прав на это действие!'], 404);
            }

            $result = OrderFile::saveFile($file_link, $position, $order_id, $extension, $user['id'], $file_name);
            if(!$result){
                return response()->json(['message' => 'bad request'], 400);
            }

            switch ($position){
                case 'metrings':
                    $model = Metring::where('order_id', $order_id)->update([
                        'status' => 2,
                        'file' => 'exist',
                        'passed_date' => $date
                    ]);
                    break;

                case 'design':
                    $model = Design::where('order_id', $order_id)->update([
                        'status' => 2,
                        'file' => 'exist',
                        'passed_date' => $date
                    ]);
                    break;

                case 'technologists':
                    $model = Technologist::where('order_id', $order_id)->update([
                        'status' => 2,
                        'file' => 'exist',
                        'passed_date' => $date
                    ]);
                    break;
            }

            Order::where('id', $order_id)->update([
                $position => 2,
            ]);

            if($position == 'technologists'){
                Order::where('id', $order_id)->update([
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
