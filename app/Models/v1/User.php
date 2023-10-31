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
        'email',
        'address',
        'password',
        'position',
        'status',
        'phone',
        'salary',
        'connection_id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'email',
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

        $column = [
            'id',
            'iin',
            'name',
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
            return self::select($column)
                ->where('status', $status)
                ->where(function ($query) use ($search) {
                    $query
                        ->where('name', 'LIKE', "%{$search}%");
                })
                ->where('connection_id', $connection)
                ->where('position', '!=', 'admin')
                ->orderBy($sort, $asc ? 'asc' : 'desc')
                ->paginate($count, ['*'], 'page', $page);
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
            'super_admin',
        ];
        $data = [];
        $i = 0;
        $role = Role::whereNotIn('role', $filter)->get()->toArray();

        foreach ($role as $item) {
            $results = self::where('id', $item['user_id'])
                ->where('connection_id', $connection)
                ->first()->toArray();
            $results['position'] = $item['role'];
            if($results != null){
                $data[$i] = $results;
                $i++;
            }
        }

        if(!$data){
            return response()->json(['message' => 'Не верные данные'], 404);
        }
        return $data;
    }
}
