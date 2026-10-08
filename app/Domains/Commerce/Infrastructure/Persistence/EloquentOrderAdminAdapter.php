<?php

namespace App\Domains\Commerce\Infrastructure\Persistence;

use App\Domains\Commerce\Application\Ports\OrderAdminRepositoryPort;
use App\Domains\Commerce\Infrastructure\Models\Order;

class EloquentOrderAdminAdapter implements OrderAdminRepositoryPort
{
    public function listing(): mixed
    {
        return Order::with('user')->latest()->paginate(15);
    }

    public function find(int $id): mixed
    {
        return Order::query()->with(['items', 'user'])->findOrFail($id);
    }

    public function forUser(int $userId): mixed
    {
        return Order::query()->where('user_id', $userId)->with('items')->latest()->get();
    }

    public function updateStatus(int $id, string $status): array
    {
        $order = Order::query()->findOrFail($id);
        $changed = $order->status !== $status;
        if ($changed) {
            $order->update(['status' => $status]);
        }

        return ['changed' => $changed, 'email' => $order->email];
    }
}
