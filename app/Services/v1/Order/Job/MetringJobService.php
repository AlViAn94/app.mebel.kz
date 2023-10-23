<?php

namespace App\Services\v1\Order\Job;

use App\Models\v1\Metring;

class MetringJobService
{
    public function takeOrder($request)
    {
        $id = $request['id'];
        return Metring::takeMetring($id);
    }
}
