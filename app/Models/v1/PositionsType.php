<?php

namespace App\Models\v1;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PositionsType extends Model
{
    use HasFactory;

    protected $table = 'positions_type';

    protected $fillable = [
        'position_id',
        'position',
        'name'
    ];
}
