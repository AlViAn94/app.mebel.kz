<?php

namespace App\Models\v1;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoleType extends Model
{
    use HasFactory;

    protected $table = 'role_type';

    protected $fillable = [
        'role',
        'name'
    ];
}
