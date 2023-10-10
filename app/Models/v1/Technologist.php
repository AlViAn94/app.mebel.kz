<?php

namespace App\Models\v1;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Technologist extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'file',
        'user_id',
        'status'
    ];

}
