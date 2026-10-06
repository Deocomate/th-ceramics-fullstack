<?php

namespace App\Domains\Commerce\Application\Ports;

interface CouponRepositoryPort
{
    public function getAll(): mixed;

    public function getDeleted(): mixed;

    public function findById(int $id): mixed;

    public function findDeletedById(int $id): mixed;

    public function store(array $data): mixed;

    public function update(int $id, array $data): mixed;

    public function destroy(int $id): void;

    public function restore(int $id): void;

    public function forceDelete(int $id): void;

    public function validateAndCalculate(string $code, array $cartItems): array;

    public function incrementUsage(string $code): void;

    public function decrementUsage(string $code): void;

    public function getCartSubtotal(array $cartItems): int;
}
