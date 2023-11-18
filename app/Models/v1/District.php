<?php

namespace App\Models\v1;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    use HasFactory;

    public static function list($data)
    {

        $result = self::get()
            ->toArray();
        if($result){
            return $result;
        }else{
            return [];
        }

    }

}
