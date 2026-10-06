<?php

namespace App\Domains\Commerce\Application\Ports;

interface OrderAdminRepositoryPort
{
    public function listing(): mixed;

    public function find(int $id): mixed;

    public function forUser(int $userId): mixed;

    /** @return array{changed: bool, email: ?string} */
    public function updateStatus(int $id, string $status): array;
}
