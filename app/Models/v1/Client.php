<?php

namespace App\Models\v1;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

class Client extends Model
{
    use HasFactory, UsesTenantConnection;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'iin',
        'name',
        'surname',
        'lastname',
        'phone',
        'email'
    ];

    public static function newClient($data)
    {
        $existingRecord = static::where('phone', $data['phone'])->first();

        if (!$existingRecord) {
            $newRecord = new static;
            $newRecord->fill($data);
            $newRecord->save();
            return $newRecord;
        }else{
            return response()->json([
                'message' => 'Данный клиент уже зарегистрирован!'
            ], 404);
        }
    }

    public static function checkClient($data)
    {
        $existingRecord = static::where('iin', $data['iin'])
            ->orWhere('phone', $data['phone'])
            ->first();
        if($existingRecord)
        {
            return $existingRecord;
        }else{
            return response()->json(['message' => "Клиент не найден!"], 404);
        }
    }
    public static function updateClient($data)
    {
        $order = self::find($data['id']);

        if ($order) {
            $order->update([
                'iin' => $data['iin'],
                'email' => $data['email'],
                'name' => $data['name'],
                'surname' => $data['surname'],
                'lastname' => $data['lastname'],
                'phone' => $data['phone']
            ]);

            return response()->json(["message" => "Клиент успешно обновлен!"]);
        } else {
            return response()->json(["message" => "Клиент не найден!"], 404);
        }
    }

    public static function getList($request)
    {
        $search = $request['search'];
        $sort = $request['sort'];
        $asc = $request['asc'];
        $page = $request['page'];
        $count = $request['count'];

        if (empty($sort)) {
            $sort = 'created_at';
        }

        $firstItemNumber = ($page - 1) * $page + 1;

        $user = Auth::user();
        $user_id = $user['id'];
        $roles = Role::getPositions($user_id);

        if (!in_array('dir', $roles)) {
            return response()->json(['message' => 'У вас нет доступа.'], 404);
        }
        $users = self::where(function ($query) use ($search) {
                $query
                    ->where('name', 'LIKE', "%{$search}%");
            })
            ->orderBy($sort, $asc ? 'asc' : 'desc')
            ->paginate($count, ['*'], 'page', $page);

        foreach ($users as $v) {
            $v->user_number = $firstItemNumber++;
            $v->fulname = $v['surname'] . ' ' . $v['name'] . ' ' . $v['lastname'];
        }
        return $users;
    }

    public static function deleteClient($id)
    {
        $user = Auth::user();
        $roles = Role::getPositions($user['id']);

        if (!in_array('admin', $roles)) {
            return response()->json(['message' => 'У вас нет доступа.'], 404);
        }

        $role = self::find($id);

        if ($role) {
            $role->delete();
            return response()->json(['message' => 'Клиент удален.']);
        }
        return response()->json(['message' => 'Не удалось удалить клиента.'], 404);
    }
    public static function getDateEndAttribute($value)
    {
        return Carbon::parse($value);
    }
    public static function getNewClients($data)
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
}
