<?php

namespace App\Domains\Commerce\Application\Ports;

interface OrderStatusMailPort
{
    public function send(int $orderId, string $email): void;
}
