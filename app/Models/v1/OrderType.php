<?php

namespace App\Models\v1;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

class OrderType extends Model
{
    use HasFactory, UsesTenantConnection;

    protected $table = 'orders_type';
}
