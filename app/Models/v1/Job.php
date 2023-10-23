<?php

namespace App\Models\v1;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

class Job extends Model
{
    use HasFactory, UsesTenantConnection;

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
        $name = $user->name;

        $datetime = Carbon::now();
        $date = $datetime->format('Y-m-d');

        $result = self::where('id', $id)->update([
            'user_id' => $user_id,
            'user_name' => $name,
            'take_date' => $date,
            'status' => 1
        ]);

        if ($result) {
            return response()->json(['message' => 'Заказ успешно взят']);
        } else {
            return response()->json(['error' => 'Ошибка при сохранении записи.']);
        }
    }
}
