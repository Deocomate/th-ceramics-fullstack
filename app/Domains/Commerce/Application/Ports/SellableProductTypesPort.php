<?php

namespace App\Domains\Commerce\Application\Ports;

interface SellableProductTypesPort
{
    /** @return array<string, string> product type key => display label */
    public function labels(): array;
}
