<?php

namespace App\Models\v1;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

class OrderFile extends Model
{
    use HasFactory, UsesTenantConnection;

    protected $table = 'order_files';

    protected $fillable = [
        'user_id',
        'order_id',
        'position',
        'extension'
    ];

    public static function saveFile($file_link, $position, $order_id, $extension, $user_id, $file_name)
    {
        return self::insert([
            'link' => $file_link,
            'position' => $position,
            'order_id' => $order_id,
            'user_id' => $user_id,
            'file_name' => $file_name,
            'extension' => $extension
        ]);
    }

    public static function fileList($data)
    {
        $order_id = $data['order_id'];
        $position = $data['position'];

        $result = self::where('order_id', $order_id)
            ->where('position', $position)
            ->get()
            ->toArray();
        if(!$result){
            return response()->json(['message' => 'bad request.'], 400);
        }

        $format = ['jpeg', 'jpg', 'png', 'gif', 'bmp', 'tiff', 'webp'];

        $array = [
            'img' => [],
            'doc' => [],
        ];

        foreach ($result as $item) {
            if (in_array($item['extension'], $format)) {
                $array['img'][] = $item;
            }
            $array['doc'][] = $item;
        }
        return $array;
    }
}
