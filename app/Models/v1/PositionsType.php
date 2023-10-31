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

    public static function getPosition($data)
    {
        $position = $data['type'];

        switch ($position){
            case 'factory':

            $filter = [
                'manager',
                'design',
                'metrings',
                'technologists',
                'admin',
                'super_admin',
                'foreman',
            ];
                return self::whereNotIn('position', $filter)->get()->toArray();

            default:
                return self::get();
        }

    }
}
