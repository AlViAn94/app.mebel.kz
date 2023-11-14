<?php

namespace App\Models\v1;

use Carbon\Carbon;
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
                'district' => $data['district'],
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

        if (empty($sort)) {
            $sort = 'created_at';
        }
        $firstItemNumber = ($page - 1) * $page + 1;
        if($status == null){
            $orders = Order::where('status', '>=', 1)
                ->where(function ($query) use ($search) {
                    $query->where('order_num', 'LIKE', "%{$search}%");
                })
                ->orderBy('status', 'ASC')
                ->orderBy('date_end', 'ASC')
                ->paginate($count, ['*'], 'page', $page);
        }else{
            $orders = Order::where('status', $status)
                ->where(function ($query) use ($search) {
                    $query->where('order_num', 'LIKE', "%{$search}%");
                })
                ->orderBy($sort, $asc ? 'asc' : 'desc')
                ->paginate($count, ['*'], 'page', $page);
        }

        foreach ($orders as $v) {
            $v->order_number = $firstItemNumber++;
            $client = Client::where('id', $v['client_id'])->first();
            $full_name = $client['name'] . ' ' . $client['surname'] . ' ' . $client['lastname'];
            $address = $v['district'] . ' ' . $v['address'];
            $v->full_name = $full_name;
            $v->full_address = $address;
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
        $job_id = [];
        $take_date = [];
        $passed_date = [];
        $i = 0;
        foreach ($model as $v){
            $orders_id[$i] = $v['order_id'];
            $job_id[$i] = $v['id'];
            $take_date[$i] = $v['take_date'];
            $passed_date[$i] = $v['passed_date'];
            $i++;
        }
        $firstItemNumber = ($page - 1) * $page + 1;

        if(isset($data['status'])){
            $orders = Order::whereIn('id', $orders_id)
                ->where('status', $data['status'])
                ->where(function ($query) use ($search) {
                    $query
                        ->where('order_num', 'LIKE', "%{$search}%");
                })
                ->orderBy($sort, $asc ? 'asc' : 'desc')
                ->paginate($count, ['*'], 'page', $page);
        }else{
            $orders = Order::whereIn('id', $orders_id)
                ->where(function ($query) use ($search) {
                    $query
                        ->where('order_num', 'LIKE', "%{$search}%");
                })
                ->orderBy($sort, $asc ? 'asc' : 'desc')
                ->paginate($count, ['*'], 'page', $page);
        }

        $s = 0;
        foreach ($orders as $v) {
            $client = Client::where('id', $v['client_id'])->first();
            $full_name = $client['name'] . ' ' . $client['surname'] . ' ' . $client['lastname'];
            $address = $v['district'] . ' ' . $v['address'];
            $v->full_address = $address;
            $v->order_number = $firstItemNumber++;
            $v->full_name = $full_name;
            $v->card_id = $job_id[$s];
            $v->take_date = $take_date[$s];
            $v->passed_date = $passed_date[$s];
            $s++;
        }
        return $orders;
    }

    public static function takeOrder($position, $order_id)
    {
        Order::where('id', $order_id)->update([
            $position => 1
        ]);
        return response()->json(['message' => 'Вы взяли заказ.']);
    }

    public static function dropOrder($order_id, $position)
    {
        $order = Order::where('id', $order_id)->update([
            $position => 0
        ]);
        return $order;
    }

    public static function getList($data)
    {
        $search = $data['search'];
        $status = $data['status'];
        $sort = $data['sort'];
        $asc = $data['asc'];
        $count = $data['count'];
        $page = $data['page'];

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
    public static function getDateEndAttribute($value)
    {
        return Carbon::parse($value);
    }
    public static function calendar($data)
    {
        $start = Carbon::parse($data['date_start'])->startOfDay();
        $end = Carbon::parse($data['date_end'])->endOfDay();

//        $orders_created = Order::whereBetween('created_at', [$start, $end])->get(['id'])->toArray();
//        $orders_date_end = Order::whereBetween('date_end', [$start, $end])->get(['id'])->toArray();
        $orders = Order::where(function ($query) use ($start, $end) {
            $query->where(function ($query) use ($start, $end) {
                $query->where('created_at', '>=', $start)
                    ->where('created_at', '<=', $end);
            })->orWhere(function ($query) use ($start, $end) {
                $query->where('date_end', '>=', $start)
                    ->where('date_end', '<=', $end);
            })->orWhere(function ($query) use ($start, $end) {
                $query->where('created_at', '<=', $start)
                    ->where('date_end', '>=', $end);
            });
        })->pluck('id');

//        $combinedOrders = array_merge($orders_created, $orders_date_end);
//        $uniqueOrders = collect($combinedOrders)->unique('id')->values()->all();

        $array = [];
        $i = 0;
        foreach ($orders as $v){
            $order = self::whereId($v)->first();
            if($order){
                if($order['date_end'] > $end){
                    $end = Carbon::parse($end)->format('Y-m-d');
                    $date_end = $end;
                }else{
                    $date_end = $order['date_end']->format('Y-m-d');
                }
                if($order['created_at'] < $start){
                    $start = Carbon::parse($start)->format('Y-m-d');
                    $date_start = $start;
                }else{
                    $date_start = $order['created_at']->format('Y-m-d');
                }
            }else{
                return $array;
            }

            switch ($order['status']){
                case 0:
                    $color = 'blue';
                    break;
                case 1:
                case 2:
                    $color = 'yellow';
                    break;
                case 3:
                    $color = 'green';
                    break;
            }

            $client = Client::where('id', $order['client_id'])->first();

            $array[$i]['with'] = $client['surname'] . ' ' . $client['name'] . ' ' . $client['lastname'];
            $array[$i]['title'] = $order['order_num'];
            $array[$i]['time']['start'] = $date_start;
            $array[$i]['time']['end'] = $date_end;
            $array[$i]['color'] = $color;
            $array[$i]['id'] = $order['id'];
            $array[$i]['description'] = $order['comment'];
            $i++;
        }

        return $array;
    }

    public static function getStatisticSum($data)
    {
        $year = $data['year'];
        $month = $data['month'];
        if(isset($month)){
            $date = $year . '-' . $month;
            $date_time = self::getDateEndAttribute($date);
            $day = $date_time->format('Y-m');
        }else{
            $date = $year;
            $date_time = self::getDateEndAttribute($date);
            $day = $date_time->format('Y');
        }
        $result = self::whereDate('created_at', 'like', $day . '%')
            ->sum('sum');

        if(!$result){
            $result = [];
            return $result;
        }
        return $result;

    }

    public static function getStatisticCount($data)
    {
        $year = $data['year'];
        $month = $data['month'];
        if(isset($month)){
            $date = $year . '-' . $month;
            $date_time = self::getDateEndAttribute($date);
            $day = $date_time->format('Y-m');
        }else{
            $date = $year;
            $date_time = self::getDateEndAttribute($date);
            $day = $date_time->format('Y');
        }
        $result = self::whereDate('created_at', 'like', $day . '%')
            ->count('id');

        if(!$result){
            $result = [];
            return $result;
        }
        return $result;
    }

    static function graphicalStatistics($data)
    {
        $today = Carbon::today();
        switch ($data){
            case 'day':
                $newCount = self::whereDate('created_at', $today)
                    ->count();
                return response()->json(['count' => $newCount]);

            case 'week':
                $s = 0;
                for ($i = 0; $i < 7; $i++) {
                    $newCount[$s] = self::whereDate('created_at', $today)
                        ->count();
                    $today->subDay();
                    $s++;
                }
                $newCountReverse = array_reverse($newCount);

                return response()->json(['count' => $newCountReverse]);

            case 'month':
                $s = 0;
                for ($i = 0; $i < 31; $i++) {
                    $newCount[$s] = self::whereDate('created_at', $today)
                        ->count();
                    $today->subDay();
                    $s++;
                }

                $newCountReverse = array_reverse($newCount);

                return response()->json(['count' => $newCountReverse]);

            case 'all':
                $accCount = self::count();
                return response()->json(['count' => $accCount]);
        }
        return response()->json(['message' => 'bad request'], 400);
    }

    public function getOrdersPercentage()
    {
        $results = self::selectRaw('
            WITH OrdersCount AS (
                SELECT raions.id AS raion_id, raions.raion_name, COUNT(orders.id) AS order_count
                FROM raions
                LEFT JOIN orders ON raions.id = orders.raion_id
                GROUP BY raions.id, raions.raion_name
            ),
            MaxOrders AS (
                SELECT MAX(order_count) AS max_orders
                FROM OrdersCount
            )

            SELECT raion_id, raion_name,
                (order_count * 100.0 / (SELECT max_orders FROM MaxOrders)) AS order_percentage
            FROM OrdersCount, MaxOrders
            ORDER BY order_percentage DESC;
        ')->get();

        return $results;
    }
}
