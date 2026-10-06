<?php

namespace App\Domains\Commerce\Application;

use App\Domains\Commerce\Application\Ports\OrderAdminRepositoryPort;
use App\Domains\Commerce\Application\Ports\OrderStatusMailPort;
use App\Domains\Commerce\Domain\OrderStatus;
use InvalidArgumentException;

class OrderAdminService
{
    public function __construct(
        private readonly OrderAdminRepositoryPort $orders,
        private readonly OrderStatusMailPort $mail,
    ) {}

    public function listing(): mixed
    {
        return $this->orders->listing();
    }

    public function find(int $id): mixed
    {
        return $this->orders->find($id);
    }

    public function forUser(int $userId): mixed
    {
        return $this->orders->forUser($userId);
    }

    public function updateStatus(int $id, string $status): void
    {
        if (! in_array($status, OrderStatus::values(), true)) {
            throw new InvalidArgumentException('Trạng thái đơn hàng không hợp lệ.');
        }

        $result = $this->orders->updateStatus($id, $status);
        if ($result['changed'] && $result['email']) {
            $this->mail->send($id, $result['email']);
        }
    }
}
