<?php

namespace App\Services\v1\Order\Job;

use App\Models\v1\Design;

class DesignJobService
{
    public function takeOrder($request)
    {
        $id = $request['id'];
        return Design::takeDesign($id);
    }
}
