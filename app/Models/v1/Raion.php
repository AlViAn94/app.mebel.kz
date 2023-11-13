<?php

namespace App\Models\v1;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Raion extends Model
{
    use HasFactory;

    public static function list($data)
    {
        $region = $data['region'];
        $type = $data['type'];

        $result = self::where('city', $type)
            ->where('location_id', $region)
            ->get()
            ->toArray();
dd($result);
        if($result){
            return $result;
        }else{
            return [];
        }

    }

}
