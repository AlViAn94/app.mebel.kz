<?php

namespace App\Services\v1\Order\Job\Factory;

use App\Models\v1\Job;
use App\Models\v1\Role;
use Illuminate\Support\Facades\Auth;

class DeletedJobPosition
{
    public function deletedPosition($id)
    {

        $job = Job::find($id);
        $user = Auth::user();
        $user_id = $user['id'];
        $roles = Role::getPositions($user_id);

        if (!in_array('foreman', $roles)) {
            return response()->json(['message' => 'Только начальник цеха может удалить карту.'], 404);
        }
        if ($job) {
            $job->delete();
            return response()->json(['message' => 'Запись успешно удалена!']);
        } else {
            return response()->json(['message' => 'Не удалось удалить!'], 404);
        }
    }
}
