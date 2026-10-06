<?php

namespace Tests\Unit\Architecture\Fixtures\Domains\Catalog\Domain;

use App\Domains\Commerce\Domain\Order;

class DomainViolatingCrossContextImport
{
    public function calculate(Order $order): void {}
}
