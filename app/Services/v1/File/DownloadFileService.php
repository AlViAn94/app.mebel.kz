<?php

namespace App\Services\v1\File;

use App\Models\v1\Connection;
use App\Models\v1\OrderFile;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use ZipArchive;

class DownloadFileService
{
    /**
     * Получаем файлы из БД и архивируем их в zip
     * Сохраняем в директорию и сразу отдаём на скачивание
     * При повторном вызове zip файл перезапишется
     */
    public function downloadFile($data)
    {
        $position = $data['position'];
        $order_id = $data['order_id'];

        $order_files = OrderFile::where('order_id', $order_id)
            ->where('position', $position)
            ->get()
            ->toArray();


        $order_links = [];
        foreach ($order_files as $file){
            $order_links[] = $file['link'];
        }
        $date = Carbon::now();
        $year = $date->format('Y');

        $user = Auth::user();
        $connection_name = Connection::where('id', $user['connection_id'])->pluck('database');
        $save_path = env('FILE_LINK') . '/' . $year . '/' . $connection_name[0] . '/zip';

        if (!File::exists($save_path)) {
            File::makeDirectory($save_path, 0755, true, true);
        }

        $zipFilename = $save_path . '/app.zip';

        $zip = new ZipArchive;

        if ($zip->open($zipFilename, ZipArchive::CREATE) === TRUE) {
            foreach ($order_links as $link) {
                $newPath = str_replace(env('APP_URLFILE'), env('REPLACE_DIR'), $link);
                $filename = basename($newPath);
                $zip->addFile($newPath, $filename);
            }
            $zip->close();

            return response()->json(['link' => $zipFilename]);
        } else {
            return false;
        }
    }
}
