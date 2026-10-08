<?php

namespace App\Domains\Commerce\Application\Ports;

interface EcommerceStatusPort
{
    public function enabled(): bool;
}
