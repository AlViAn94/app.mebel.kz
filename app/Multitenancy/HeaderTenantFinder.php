<?php

namespace App\Multitenancy;

use Spatie\Multitenancy\TenantFinder\TenantFinder;
use Spatie\Multitenancy\Models\Concerns\UsesTenantModel;
use Spatie\Multitenancy\Models\Tenant;
use Illuminate\Http\Request;

class HeaderTenantFinder extends TenantFinder
{
    use UsesTenantModel;

    public function findForRequest(Request $request):?Tenant
    {
        $connection_id = $request->header('X-Connection-Id')??0;
        return $this->getTenantModel()::find($connection_id);
    }

}
