<?php

namespace App\Services\v1\File;

use App\Models\v1\Connection;
use App\Models\v1\Design;
use App\Models\v1\Metring;
use App\Models\v1\Order;
use App\Models\v1\Role;
use App\Models\v1\Technologist;
use Illuminate\Support\Facades\Auth;

class DeletedFileService
{
    public function deletedFile($dir, $id)
    {
        $user = Auth::user();
        $user_id = $user['id'];
        $roles = Role::getPositions($user_id);

        if (!in_array($dir, $roles)) {
            return response()->json(['message' => 'Только начальник цеха может удалить карту.'], 404);
        }

        switch ($dir){
            case 'metrings':
                $link = Metring::where('id', $id)->select('file')->first();
                break;

            case 'design':
                $link = Design::where('id', $id)->select('file')->first();
                break;

            case 'technologists':
                $link = Technologist::where('id', $id)->select('file')->first();
                break;
        }
        if($link->file == null){
            return response()->json(['message' => 'Файл не найден!'], 404);
        }

        $old_link = str_replace(env('APP_URL'), public_path(), $link->file);

        if (file_exists($old_link)) {
            unlink($old_link);

            switch ($dir){
                case 'metrings':
                    $metring = Metring::where('id', $id)->first();
                    Metring::where('id', $id)->update([
                        'file' => null,
                        'passed_date' => null,
                        'status' => 1
                    ]);
                    Order::where('id', $metring->order_id)->update([
                        $dir => 1
                    ]);
                    break;

                case 'design':
                    $design = Design::where('id', $id)->first();
                    Design::where('id', $id)->update([
                        'file' => null,
                        'passed_date' => null,
                        'status' => 1
                    ]);
                    Order::where('id', $design->order_id)->update([
                        $dir => 1
                    ]);
                    break;

                case 'technologists':
                    $technologist = Technologist::where('id', $id)->first();

                    Technologist::where('order_id', $id)->update([
                        'file' => null,
                        'passed_date' => null,
                        'status' => 1
                    ]);
                    Order::where('id', $technologist->order_id)->update([
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
