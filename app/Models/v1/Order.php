<?php

namespace App\Models\v1;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

class Order extends Model
{
    use HasFactory, UsesTenantConnection;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'client_id',
        'address',
        'order_num',
        'sum',
        'type',
        'comment',
        'date_end'
    ];

    public static function findById($id)
    {
        return self::where('id', $id)->first();
    }

    public function design()
    {
        return $this->hasOne(Design::class, 'order_id');
    }
    public function metring()
    {
        return $this->hasOne(Metring::class, 'order_id');
    }
    public function tehnologist()
    {
        return $this->hasOne(Technologist::class, 'order_id');
    }

    public function createdAllPosition()
    {
        $this->hasOne(Design::class, 'order_id')->create();
        $this->hasOne(Metring::class, 'order_id')->create();
        $this->hasOne(Technologist::class, 'order_id')->create();
    }

    public function getAllPosition($id)
    {
        $design = Design::where('order_id', $id)->first();
        $metring = Metring::where('order_id', $id)->first();
        $tehnologist = Technologist::where('order_id', $id)->first();

        $position['design'][0] = $design;
        $position['tehnologist'][0] = $tehnologist;
        $position['metring'][0] = $metring;

        $job = Job::where('order_id', $id)->get();
        foreach ($job as $v) {
            $id_job = $v->id;
            $job1 = Job::where('id', $id_job)->first();
            $position[$job1['position']][$id_job] = $job1;
        }
        return $position;
    }

    public function deletedAllPosition()
    {
            $this->hasOne(Design::class, 'order_id')->delete();
            $this->hasOne(Metring::class, 'order_id')->delete();
            $this->hasOne(Technologist::class, 'order_id')->delete();
    }

    public static function getLastNum()
    {
        return self::select('order_num')
            ->orderBy('order_num', 'desc')
            ->first();
    }

    public static function updateOrder($data)
    {
        $order = Order::find($data['id']);

        if ($order) {
            $order->update([
                'address' => $data['address'],
                'date_end' => $data['date_end'],
                'type' => $data['type'],
                'sum' => $data['sum']
            ]);

            return response()->json(["message" => "Заказ успешно обновлен!"]);
        } else {
            return response()->json(["message" => "Заказ не найден!"]);
        }
    }
}
