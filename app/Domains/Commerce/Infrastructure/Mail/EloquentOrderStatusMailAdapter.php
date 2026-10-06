<?php

namespace App\Domains\Commerce\Infrastructure\Mail;

use App\Domains\Commerce\Application\Ports\OrderStatusMailPort;
use App\Domains\Commerce\Infrastructure\Models\Order;
use Illuminate\Support\Facades\Mail;

class EloquentOrderStatusMailAdapter implements OrderStatusMailPort
{
    public function send(int $orderId, string $email): void
    {
        Mail::to($email)->send(new OrderStatusUpdatedMail(Order::query()->with('items')->findOrFail($orderId)));
    }
}
