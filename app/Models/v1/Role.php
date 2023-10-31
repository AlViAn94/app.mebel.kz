<?php

namespace App\Models\v1;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Role extends Model
{
    use HasFactory;

    protected $table = 'user_role';

    protected $fillable = [
        'user_id',
        'role',
        'name'
    ];

    public static function createdRole($data)
    {
        return self::create([
            'user_id' => $data['id'],
            'role' => $data['role'],
            'name' => $data['name'],
        ]);
    }

    public static function getPositions($id)
    {
        return self::where('user_id', $id)->get()->pluck('role')->toArray();
    }

    public static function assignPosition($data)
    {
        $user_id = $data['user_id'];
        $position = $data['position'];
        $position_name = $data['position_name'];

        $user = Auth::user();
        $roles = Role::getPositions($user['id']);

        if (!in_array('admin', $roles)) {
            return response()->json(['message' => 'У вас нет доступа.'], 404);
        }

        if(!User::where('id', $user_id)->where('connection_id', $user['connection_id'])->first()){
            return response()->json(['message' => 'Не найден.'], 404);
        }

        $result = self::where('user_id', $user_id)->where('role', $position)->first();
        if($result){
            return response()->json(['message' => 'Позиция уже добавлена к данному работнику.'], 404);
        }

        $role = self::create([
            'user_id' => $user_id,
            'role' => $position,
            'name' => $position_name,
        ]);
        if($role){
            return response()->json(['message' => 'Позиция добавлена.']);
        }
        return response()->json(['message' => 'Позиция не добавлена.'], 404);
    }

    public static function deletedPositionUser($id)
    {
        $user = Auth::user();
        $roles = Role::getPositions($user['id']);

        if (!in_array('admin', $roles)) {
            return response()->json(['message' => 'У вас нет доступа.'], 404);
        }

        $role = self::find($id);

        if ($role) {
            $role->delete();
            return response()->json(['message' => 'Позиция удалена.']);
        }
        return response()->json(['message' => 'Позиция не удалена.'], 404);
    }
}
