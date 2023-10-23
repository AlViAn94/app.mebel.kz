<?php

namespace App\Services\v1\Order\Job;

use App\Models\v1\Design;
use App\Models\v1\Technologist;

class TechnologistJobService
{
    public function takeOrder($request)
    {
        $id = $request['id'];
        return Technologist::takeTechnologist($id);
    }
}
