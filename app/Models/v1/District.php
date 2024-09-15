<?php

namespace App\Models\v1;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    use HasFactory;

    public static function list($data): array
    {

        $result = self::query()->get()
            ->toArray();
        if($result){
            return $result;
        }else{
            return [];
        }

    }

}
