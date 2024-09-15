<?php

namespace App\Models\v1;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

class ClientComment extends Model
{
    use HasFactory, UsesTenantConnection;

    protected $table = 'client_comments';

    protected $fillable = [
        'file',
        'text',
        'order_num'
    ];

    public static function addFileLink($file_link, $text, $order_num): bool
    {
        $result = self::query()->create([
            'file' => $file_link,
            'text' => $text,
            'order_num' => $order_num
        ]);

        if($result){
            return true;
        }else{
            return false;
        }
    }

    public static function checkComment($order_num): bool
    {
        if(self::query()->where('order_num', $order_num)->first()){
            return false;
        }else{
            return true;
        }

    }
}
