<?php

namespace App\Services\v1\File;

use App\Models\v1\OrderFile;

class DownloadFileService
{
    public function downloadFile($data)
    {
        $position = $data['position'];
        $order_id = $data['order_id'];

        OrderFile::where('order_id', $order_id)
            ->where('position', $position)
            ->get()
            ->toArray();
    }
}
