<?php

namespace Tests\Unit\Architecture\Fixtures\Domains\Catalog\Domain;

use App\Domains\Catalog\Infrastructure\Eloquent\ProductModel;

class DomainViolatingInfrastructureImport
{
    public function find(): ?ProductModel
    {
        return null;
    }
}
