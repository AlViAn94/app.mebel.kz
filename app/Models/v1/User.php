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
        $column = [
            'id',
            'iin',
            'name',
            'phone',
            'position',
            'created_at'
        ];
        $user = Auth::user();
        if($user){
            if($user['position'] != 'admin'){
                return response()->json(['message' => 'У вас нет прав на это действие.'], 404);
            }
            $connection = $user['connection_id'];
            return self::select($column)
                ->where('connection_id', $connection)
                ->where('position', '!=', 'admin')
                ->where('status', $status)
                ->get();
        }
        return response()->json(['message' => 'Что то пошло не так.'], 404);
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

        $results = self::where('connection_id', $connection)
            ->whereNotIn('position', $filter)
            ->get();

        if(!$results){
            return response()->json(['message' => 'Не верные данные'], 404);
        }
        return $results;
    }
}
