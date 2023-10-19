<?php

namespace App\Models\v1;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Technologist extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'file',
        'user_id',
        'status'
    ];

    public static function takeTechnologist($order_id)
    {
        $user = Auth::user();
        $id = $user->id;
        $result = self::where('order_id', $order_id)->update([
            'user_id' => $id,
            'status' => 1
        ]);
        Order::where('id', $order_id)->update([
            'design' => 1
        ]);
        if ($result) {
            return response()->json(['message' => 'Заказ успешно взят']);
        } else {
            return response()->json(['error' => 'Ошибка при сохранении записи.']);
        }
    }
}
