<?php

namespace App\Services\v1\Order\Job\Factory;

use App\Models\v1\Job;

class FactoryTakeCardService
{
    public function takeOrder($request)
    {
        $id = $request['id'];
        return Job::takeCard($id);
    }
}
