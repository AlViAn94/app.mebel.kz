<?php

namespace App\Services\v1\Landing;

use App\Models\v1\ClientComment;
use App\Models\v1\Connection;
use App\Models\v1\Regmy;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class ClientCommentService
{
    public function addComment($data)
    {
        $file = $data->file('file');
        $text = $data['text'];
        $order_num = $data['order_num'];

        $comment = ClientComment::checkComment($order_num);
        if($comment != true){
            return response()->json(['message' => 'Комментарий уже добавлен.'], 404);
        }

        $date = Carbon::now();
        $year = $date->format('Y');
        $month = $date->format('Y-m');

        $user = Auth::user();
        $connection_name = Connection::where('id', $user['connection_id'])->pluck('database');

        $save_path = env('FILE_PATH'). $year . '/' . $month . '/' . $connection_name[0]  . '/comments/';

        if (!File::exists($save_path)) {
            File::makeDirectory($save_path, 0755, true, true);
        }
        $extension = $file->getClientOriginalExtension();
        $file_name = $file->getClientOriginalName();

        $currentDateTime = Carbon::now()->format('YmdHis');
        $file_name = pathinfo($file_name, PATHINFO_FILENAME) . '_' . $currentDateTime . '.' . $extension;

        $file_path = $save_path . $file_name;

        File::put($file_path, file_get_contents($file));

        chmod($file_path, 0644);

        Regmy::resizeImage($file_path);

        $file_link = env('FILE_LINK'). $year . '/' . $month . '/' . $connection_name[0]  . '/comments/' . $file_name;

        $result = ClientComment::addFileLink($file_link, $text, $order_num);

        if (File::exists($file->getRealPath())) {
            File::delete($file->getRealPath());
        }

        return response()->json(['message' => 'Файлы успешно сохранены!']);
    }
}
