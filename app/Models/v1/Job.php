<?php

namespace App\Models\v1;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Job extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'position',
        'user_id',
        'status'
    ];

    public static function takeCard($id)
    {
        $user = Auth::user();
        $user_id = $user->id;
        $result = self::where('id', $id)->update([
            'user_id' => $user_id,
            'status' => 1
        ]);
        if ($result) {
            return response()->json(['message' => 'Заказ успешно взят']);
        } else {
            return response()->json(['error' => 'Ошибка при сохранении записи.']);
        }
    }
}
