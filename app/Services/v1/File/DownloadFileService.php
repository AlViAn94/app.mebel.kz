<?php

namespace App\Services\v1\File;

use Illuminate\Support\Facades\DB;

class DownloadFileService
{
    public function downloadFile($dir, $id)
    {
        $link = DB::table($dir)->where('order_id', $id)->select('file')->first();
        if(!$link){
            return response()->json(['message' => 'Файл не найден!'], 404);
        }

        $zipLink['link'] = $link->file;

        return $zipLink;
    }
}
