<?php

namespace Tests\Unit\Architecture\Fixtures\Domains\Catalog\Application;

use App\Domains\Commerce\Application\Services\OrderService;

class ApplicationViolatingCrossContextImport
{
    public function handle(OrderService $orderService): void {}
}
