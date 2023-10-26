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
        'user_name',
        'passed_date',
        'take_date',
        'status'
    ];

    public static function submittedOrder($id)
    {
        $user = Auth::user();
        return self::where('id', $id)
            ->where('user_id', $user['id'])
            ->update([
            'status' => 2
        ]);
    }

    public static function takeDesign($data)
    {
        $user = Auth::user();
        $user_id = $user['id'];
        $name = $user->name;

        $datetime = Carbon::now();
        $date = $datetime->format('Y-m-d H:i');

        self::where('id', $data['id'])->where('status', 0)->update([
            'user_id' => $user_id,
            'user_name' => $name,
            'take_date' => $date,
            'status' => 1
        ]);

        $order = self::where('id', $data['id'])->select('order_id')->first();

        $order_id = $order['order_id'];

        return $order_id;
    }

    public static function updateCard($id)
    {
        $result = self::where('id', $id)->update([
            'user_id' => null,
            'user_name' => null,
            'take_date' => null,
            'status' => 0,
        ]);
        if($result){
            return response()->json(['message' => 'Карта сброшена.']);
        }
        return response()->json(['message' => 'Что то пошло не так.'], 404);
    }
}
