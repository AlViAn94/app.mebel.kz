<?php

namespace App\Models\v1;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

class Design extends Model
{
    use HasFactory, UsesTenantConnection;

    protected $table = "design";

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'order_id',
        'file',
        'user_id',
        'status'
    ];

    public static function takeDesign($id)
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

        $order_id = self::where('id', $id)->select('order_id')->first();
        Order::where('id', $order_id['order_id'])->update([
            'design' => 1
        ]);

        if ($result) {
            return response()->json(['message' => 'Заказ успешно взят']);
        } else {
            return response()->json(['error' => 'Ошибка при сохранении записи.']);
        }
    }

    public static function submittedOrder($id)
    {
        $user = Auth::user();
        return self::where('id', $id)
            ->where('user_id', $user['id'])
            ->update([
            'status' => 2
        ]);
    }

    public static function takeOrder($data)
    {
        $user = Auth::user();
        $user_id = $user['id'];
        $name = $user->name;

        $datetime = Carbon::now();
        $date = $datetime->format('Y-m-d');

        $model = self::where('id', $data['id'])->where('status', 0)->update([
            'user_id' => $user_id,
            'user_name' => $name,
            'take_date' => $date,
            'status' => 1
        ]);

        return $model;
    }
}
