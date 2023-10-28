<?php

namespace App\Models\v2;

use App\Models\v1\Client;
use App\Models\v1\Design;
use App\Models\v1\Job;
use App\Models\v1\Metring;
use App\Models\v1\Technologist;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

/**
 * @mixin Builder
 */
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
        'date_completed',
        'status',
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
        $design['position'] = 'design';
        $metring = Metring::where('order_id', $id)->first();
        $metring['position'] = 'metrings';
        $tehnologist = Technologist::where('order_id', $id)->first();
        $tehnologist['position'] = 'technologists';

        $position[0] = $design;
        $position[1] = $tehnologist;
        $position[2] = $metring;

        $job = Job::where('order_id', $id)->get();
        $i = 3;
        foreach ($job as $v) {
            $id_job = $v->id;
            $job1 = Job::where('id', $id_job)->first();
            $position[$i] = $job1;
            $i++;
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
            return response()->json(["message" => "Заказ не найден!"], 404);
        }
    }

    public static function list($data)
    {
        $search = $data['search'];
        $status = $data['status'];
        $sort = $data['sort'];
        $asc = $data['asc'];
        $count = $data['count'];
        $page = $data['page'];
        $position = ['position'];

        $user = Auth::user();
        $user_id = $user['id'];

        switch ($position){
            case 'metrings':
                $model = Metring::where('user_id', $user_id)->where('status', $position_status)->get();
                break;

            case 'design':
                $model = Design::where('user_id', $user_id)->where('status', $position_status)->get();
                break;

            case 'technologists':
                $model = Technologist::where('user_id', $user_id)->where('status', $position_status)->get();
                break;

            default:
                $model = Job::where('user_id', $user_id)->where('status', $position_status)->where('position', $position)->get();
                break;
        }

        if (empty($sort)) {
            $sort = 'created_at';
        }
        $firstItemNumber = ($page - 1) * $page + 1;

        $orders = Order::where('status', $status)
            ->where(function ($query) use ($search) {
                $query->where('order_num', 'LIKE', "%{$search}%");
            })
            ->orderBy($sort, $asc ? 'asc' : 'desc')
            ->paginate($count, ['*'], 'page', $page);

        foreach ($orders as $v) {
            $v->order_number = $firstItemNumber++;
            $client = Client::where('id', $v['client_id'])->first();
            $full_name = $client['name'] . ' ' . $client['surname'] . ' ' . $client['lastname'];
            $v->full_name = $full_name;
        }
        return $orders;
    }

    public static function listPosition($data)
    {
        $search = $data['search'];
        $position = $data['position'];
        $position_status = $data['position_status'];
        $user = Auth::user();
        $user_id = $user['id'];
        $sort = $data['sort'];
        $asc = $data['asc'];
        $count = $data['count'];
        $page = $data['page'];

        if (empty($sort)) {
            $sort = 'created_at';
        }

        switch ($position){
            case 'metrings':
                $model = Metring::where('user_id', $user_id)->where('status', $position_status)->get();
                break;

            case 'design':
                $model = Design::where('user_id', $user_id)->where('status', $position_status)->get();
                break;

            case 'technologists':
                $model = Technologist::where('user_id', $user_id)->where('status', $position_status)->get();
                break;

            default:
                $model = Job::where('user_id', $user_id)->where('status', $position_status)->where('position', $position)->get();
                break;
        }

        $orders_id = [];
        $i = 0;
        foreach ($model as $v){
            $orders_id[$i] = $v['order_id'];
            $i++;
        }
        $firstItemNumber = ($page - 1) * $page + 1;

        $orders = Order::whereIn('id', $orders_id)
            ->where(function ($query) use ($search) {
                $query
                    ->where('order_num', 'LIKE', "%{$search}%");
            })
            ->orderBy($sort, $asc ? 'asc' : 'desc')
            ->paginate($count, ['*'], 'page', $page);

        foreach ($orders as $v) {
            $client = Client::where('id', $v['client_id'])->first();
            $full_name = $client['name'] . ' ' . $client['surname'] . ' ' . $client['lastname'];
            $v->order_number = $firstItemNumber++;
            $v->full_name = $full_name;
        }
        return $orders;
    }

    public static function takeOrder($position, $order_id)
    {
        Order::where($position, $position)->where('id', $order_id)->update([
            $position => 1
        ]);
        return response()->json(['message' => 'Вы взяли заказ.']);
    }
}
