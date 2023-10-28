<?php

namespace App\Services\v1\Order\Job\Factory\Position;

use App\Models\v1\Job;
use App\Models\v1\PositionsType;
use App\Models\v1\RoleType;

class DeletePositionService
{
    public function deletePositionType($id)
    {
        $type = PositionsType::find($id);

        if ($type) {
            $type->delete();
            RoleType::where('position_id', $id)->delete();
            return response()->json(['message' => 'Позиция удалена!']);
        } else {
            return response()->json(['message' => 'Не удалось удалить позицию.'], 400);
        }
    }
}
