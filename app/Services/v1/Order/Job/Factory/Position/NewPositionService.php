<?php

namespace App\Services\v1\Order\Job\Factory\Position;

use App\Models\v1\Job;
use App\Models\v1\PositionsType;
use App\Models\v1\RoleType;

class NewPositionService
{
    public function addPositionType($request)
    {
        $type = PositionsType::where('position', $request->position)->first();
        $role = RoleType::where('role', $request->position)->first();
        if (!$type && !$role) {
            $position = PositionsType::create([
                'position' => $request->position,
                'name' => $request->name
            ]);
            RoleType::insert([
                'position_id' => $position->id,
                'role' => $request->position,
                'name' => $request->name
            ]);
            return response()->json(['message' => 'Позиция создана!']);
        } else {
            return response()->json(['error' => 'Дублирующаяся позиция.'], 400);
        }
    }
}
