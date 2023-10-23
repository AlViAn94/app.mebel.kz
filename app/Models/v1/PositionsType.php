<?php

namespace App\Models\v1;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

class PositionsType extends Model
{
    use HasFactory, UsesTenantConnection;

    protected $table = 'positions_type';

    protected $fillable = [
        'position_id',
        'position',
        'name'
    ];
}
