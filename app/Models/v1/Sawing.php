<?php

namespace App\Models\v1;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sawing extends Model
{
    use HasFactory;

    protected $table = 'sawing';

    protected $fillable = [
        'order_id',
        'user_id',
        'status'
    ];



}
