<?php

namespace App\Services\v1\Company;

use Spatie\Multitenancy\Multitenancy;

class NewCompanyService
{
    public function addCompany()
    {
        return Multitenancy::start()->tenant('tenant_name', 'domain.com');
    }
}
