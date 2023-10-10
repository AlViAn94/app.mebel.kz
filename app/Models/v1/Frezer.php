<?php

namespace App\Models\v1;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Frezer extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'user_id',
        'status'
    ];
}
