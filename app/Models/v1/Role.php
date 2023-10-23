<?php

namespace App\Models\v1;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

class Role extends Model
{
    use HasFactory, UsesTenantConnection;

    protected $table = 'user_role';

    protected $fillable = [
        'user_id',
        'role',
        'name'
    ];

    public static function createdRole($data)
    {
        return self::create([
            'user_id' => $data['id'],
            'role' => $data['role'],
            'name' => $data['name'],
        ]);
    }

    public static function deletedRole($data)
    {

    }

}
