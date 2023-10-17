<?php

namespace App\Services\v1\File;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AddLinkDataBaseService
{
    public function importFile($zipLink, $db, $id)
    {
        $result = DB::table($db)->where('id', $id)->update([
            'file' => $zipLink,
            'status' => 2
            ]);
        if($result){
            return true;
        }else{
            return false;
        }
    }
}
