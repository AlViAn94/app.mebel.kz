<?php

namespace App\Services\v1\File;

use Illuminate\Support\Facades\DB;

class DeletedFileService
{
    public function deletedFile($dir, $id)
    {
        $link = DB::table($dir)->where('order_id', $id)->select('file')->first();

        if($link->file == null){
            return response()->json(['message' => 'Файл не найден!'], 404);
        }

        if (file_exists($link->file)) {
            unlink($link->file);

            DB::table($dir)->where('order_id', $id)->update([
                'file' => null,
                'status' => 1
            ]);

            return response()->json(['message' => 'Файл удален успешно.']);
        } else {
            return response()->json(['message' => 'Файл не найден в директории!'], 404);
        }
    }
}
