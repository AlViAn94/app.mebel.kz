<?php

namespace App\Models\v1;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'iin',
        'name',
        'address',
        'password',
        'position',
        'status',
        'phone',
        'salary',
        'email',
        'connection_id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public static function chechEmail($email)
    {
        return self::where('email', $email)->first();
    }

    public static function checkUser($iin)
    {
        return self::where('iin', $iin)->first();
    }

    public static function list($request)
    {
        $status = $request['status'];
        $search = $request['search'];
        $sort = $request['sort'];
        $asc = $request['asc'];
        $page = $request['page'];
        $count = $request['count'];

        if (empty($sort)) {
            $sort = 'created_at';
        }

        $firstItemNumber = ($page - 1) * $page + 1;

        $column = [
            'id',
            'iin',
            'name',
            'email',
            'phone',
            'position',
            'created_at'
        ];

        $user = Auth::user();
        $user_id = $user['id'];
        $roles = Role::getPositions($user_id);

        if (!in_array('admin', $roles)) {
            return response()->json(['message' => 'У вас нет доступа.'], 404);
        }
            $connection = $user['connection_id'];
            $users = self::select($column)
                ->where('status', $status)
                ->where(function ($query) use ($search) {
                    $query
                        ->where('name', 'LIKE', "%{$search}%");
                })
                ->where('connection_id', $connection)
                ->where('position', '!=', 'admin')
                ->orderBy($sort, $asc ? 'asc' : 'desc')
                ->paginate($count, ['*'], 'page', $page);

        foreach ($users as $v) {
            $role = Role::where('user_id', $v['id'])->pluck('role' )->toArray();
            $v->position = $role;
            $name = Role::where('user_id', $v['id'])->pluck('name')->toArray();
            $v->position_name = $name;
            $v->user_number = $firstItemNumber++;
        }
        return $users;
    }

    public static function factoryUsersList()
    {
        $user = Auth::user();
        $connection = $user['connection_id'];

        $filter = [
            'dir',
            'manager',
            'design',
            'metrings',
            'technologists',
            'admin',
            'foreman',
            'salary',
            'super_admin',
        ];
        $data = [];
        $i = 0;
        $role = Role::whereNotIn('role', $filter)->get()->toArray();

        foreach ($role as $item) {
            $results = self::where('id', $item['user_id'])
                ->where('connection_id', $connection)
                ->first();

            if($results != null){
                $results['position'] = $item['role'];
                $data[$i] = $results;
                $i++;
            }
        }

        if(!$data){
            return response()->json(['message' => 'Не верные данные'], 404);
        }
        return $data;
    }

    public static function getUser($id)
    {
        $user = Auth::user();
        $roles = Role::getPositions($user['id']);

        if (!in_array('admin', $roles)) {
            return response()->json(['message' => 'У вас нет доступа.'], 404);
        }
        $result = [];
        $user = self::whereId($id)->first();
        $roles = Role::where('user_id', $id)->get()->toArray();
        $user['positions'] = $roles;

        return $user;
    }

    public static function updateUser($data, $id)
    {
        $order = self::find($id);

        if ($order) {
            $order->update([
                'iin' => $data['iin'],
                'email' => $data['email'],
                'name' => $data['name'],
                'address' => $data['address'],
                'phone' => $data['phone'],
                'salary' => $data['salary']
            ]);

            return response()->json(["message" => "Данные работника успешно обновлены."]);
        } else {
            return response()->json(["message" => "Не верные данные."], 404);
        }
    }

    public static function dropPassword($id)
    {
        $user = Auth::user();
        $roles = Role::getPositions($user['id']);

        if (!in_array('admin', $roles)) {
            return response()->json(['message' => 'У вас нет доступа.'], 404);
        }

        $result = self::whereId($id)->update([
            'password' => null,
            'status' => 3
        ]);

        if(!$result){
            return response()->json(['message' => 'Не верный запрос.'], 404);
        }
        return response()->json(['message' => 'Пароль сброшен.']);
    }

    public static function getStatisticSalary()
    {
        $filter = [
            'dir',
            'admin',
        ];

        $user = Auth::user();
        $roles = Role::getPositions($user['id']);
        $connection = $user['connection_id'];
        if (!in_array('dir', $roles)) {
            return response()->json(['message' => 'У вас нет доступа.'], 404);
        }
        $result = self::whereNotIn('position', $filter)
            ->where('status', 1)
            ->where('connection_id', $connection)
            ->sum('salary');

        if(!$result){
            $result = [];
            return $result;
        }
        return $result;
    }

    public static function getUserListFio($array)
    {
        $users = [];
        $i = 0;
        foreach ($array as $item)
        {
            $users[$i] = self::where('id', $item['user_id'])->first();
            $i++;
        }
        return $users;
    }
}
