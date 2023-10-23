<?php

namespace App\Models\v1;

use Illuminate\Database\Eloquent\Builder;
use Spatie\Multitenancy\Models\Tenant;

/**
 * @mixin Builder
 */
class Connection extends Tenant
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = "connections";

    protected $guarded = [];

    public $timestamps = false;

    protected $hidden = [
    ];
}
