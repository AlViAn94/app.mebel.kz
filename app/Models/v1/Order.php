<?php

namespace App\Models\v1;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

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

    public function sawing()
    {
        return $this->hasOne(Sawing::class, 'order_id');
    }

    public function painting()
    {
        return $this->hasOne(Painting::class, 'order_id');
    }

    public function sunset()
    {
        return $this->hasOne(Sunset::class, 'order_id');
    }

    public function vacuum()
    {
        return $this->hasOne(Vacuum::class, 'order_id');
    }

    public function collector()
    {
        return $this->hasOne(Collector::class, 'order_id');
    }

    public function frezer()
    {
        return $this->hasOne(Frezer::class, 'order_id');
    }

    public function createdAllPosition()
    {
        $this->hasOne(Design::class, 'order_id')->create();
        $this->hasOne(Metring::class, 'order_id')->create();
        $this->hasOne(Technologist::class, 'order_id')->create();
        $this->hasOne(Sawing::class, 'order_id')->create();
        $this->hasOne(Painting::class, 'order_id')->create();
        $this->hasOne(Sunset::class, 'order_id')->create();
        $this->hasOne(Vacuum::class, 'order_id')->create();
        $this->hasOne(Collector::class, 'order_id')->create();
        $this->hasOne(Frezer::class, 'order_id')->create();
    }

    public function getAllPosition()
    {
        $designs = Design::where('order_id', $this->id)->get();
        $metrings = Metring::where('order_id', $this->id)->get();
        $technologists = Technologist::where('order_id', $this->id)->get();
        $sawings = Sawing::where('order_id', $this->id)->get();
        $paintings = Painting::where('order_id', $this->id)->get();
        $sunsets = Sunset::where('order_id', $this->id)->get();
        $vacuums = Vacuum::where('order_id', $this->id)->get();
        $collectors = Collector::where('order_id', $this->id)->get();
        $frezers = Frezer::where('order_id', $this->id)->get();

        // Вернуть все полученные позиции
        return [
            'designs' => $designs,
            'metrings' => $metrings,
            'technologists' => $technologists,
            'sawings' => $sawings,
            'paintings' => $paintings,
            'sunsets' => $sunsets,
            'vacuums' => $vacuums,
            'collectors' => $collectors,
            'frezers' => $frezers,
        ];
    }

    public function deletedAllPosition()
    {
        $this->hasOne(Design::class, 'order_id')->delete();
        $this->hasOne(Metring::class, 'order_id')->delete();
        $this->hasOne(Technologist::class, 'order_id')->delete();
        $this->hasOne(Sawing::class, 'order_id')->delete();
        $this->hasOne(Painting::class, 'order_id')->delete();
        $this->hasOne(Sunset::class, 'order_id')->delete();
        $this->hasOne(Vacuum::class, 'order_id')->delete();
        $this->hasOne(Collector::class, 'order_id')->delete();
        $this->hasOne(Frezer::class, 'order_id')->delete();
    }

    public static function getLastNum()
    {
        return self::select('order_num')
            ->orderBy('order_num', 'desc')
            ->first();
    }

    static function getSortOrder($data)
    {
        $search = $data['search'];
        $status = $data['status'];
        $page = $data['page'];
        $sort = $data['sort'];
        $asc = $data['asc'];
        $pageSize = $data['count'];

        if(!$page){
            $page = 1;
        }

        if (empty($sort)) {
            $sort = 'updated_at';
        }
        $firstItemNumber = ($page - 1) * $pageSize + 1;

        $orders = self::where(function ($query) use ($search, $status, $sort, $asc) {
            $query
                ->where('status', $status)
                ->where(function ($query) use ($search) {
                    $query
                        ->where('order_num', 'LIKE', "%{$search}%")
                        ->orWhere('address', 'LIKE', "%{$search}%");
                });
        })
            ->orderBy('status', 'asc')
            ->orderBy($sort, $asc ? 'asc' : 'desc')
            ->paginate($pageSize, ['*'], 'page', $page);

        foreach ($orders as $order) {
            $order->order_number = $firstItemNumber++;
        }
        return $orders;
    }

    public static function updateOrder($data)
    {
        $order = Order::find($data['id']);

        if ($order) {
            $order->update([
                'client_id' => $data['client_id'],
                'address' => $data['address'],
                'date_end' => $data['date_end'],
                'type' => $data['type'],
                'sum' => $data['sum']
            ]);

            return "Заказ успешно обновлен.";
        } else {
            return "Заказ не найден.";
        }
    }
}
