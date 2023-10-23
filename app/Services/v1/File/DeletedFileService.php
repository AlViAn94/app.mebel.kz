<?php

namespace App\Services\v1\File;

use App\Models\v1\Connection;
use App\Models\v1\Design;
use App\Models\v1\Metring;
use App\Models\v1\Order;
use App\Models\v1\Technologist;

class DeletedFileService
{
    public function deletedFile($dir, $id)
    {
        switch ($dir){
            case 'metrings':
                $link = Metring::where('order_id', $id)->select('file')->first();
                break;

            case 'design':
                $link = Design::where('order_id', $id)->select('file')->first();
                break;

            case 'technologists':
                $link = Technologist::where('order_id', $id)->select('file')->first();
                break;
        }
        if($link->file == null){
            return response()->json(['message' => 'Файл не найден!'], 404);
        }

        // Временная пока не подключимся к серверу
        $replacedPath = str_replace(env('APP_URL'), public_path(), $link->file);
        //

        if (file_exists($replacedPath)) {
            unlink($replacedPath);

            switch ($dir){
                case 'metrings':
                    Metring::where('order_id', $id)->update([
                        'file' => null,
                        'status' => 1
                    ]);
                    Order::where('id', $id)->update([
                        $dir => 1
                    ]);
                    break;

                case 'design':
                    Design::where('order_id', $id)->update([
                        'file' => null,
                        'status' => 1
                    ]);
                    Order::where('id', $id)->update([
                        $dir => 1
                    ]);
                    break;

                case 'technologists':
                    Technologist::where('order_id', $id)->update([
                        'file' => null,
                        'status' => 1
                    ]);
                    Order::where('id', $id)->update([
                        $dir => 1,
                        'status' => 0
                    ]);
                    break;
            }

            return response()->json(['message' => 'Файл удален успешно.']);
        } else {
            return response()->json(['message' => 'Файл не найден в директории!'], 404);
        }
    }
}
