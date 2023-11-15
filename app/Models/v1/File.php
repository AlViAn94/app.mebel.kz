<?php

namespace App\Models\v1;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

class File extends Model
{
    use HasFactory, UsesTenantConnection;

    protected $table = 'order_files';

    protected $fillable = [
        'user_id',
        'order_id',
        'position',
        'extension'
    ];

    public static function saveFile($file_link, $position, $id, $extension)
    {
        return File::insert([
            'link' => $file_link,
            'position' => $position,
            'user_id' => $id,
            'extension' => $extension
        ]);
    }
}
