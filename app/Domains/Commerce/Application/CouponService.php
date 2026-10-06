<?php

namespace App\Domains\Commerce\Application;

use App\Domains\Commerce\Application\Ports\CouponRepositoryPort;
use App\Domains\Commerce\Domain\CouponProductTypes;

class CouponService
{
    public function __construct(private readonly CouponRepositoryPort $coupons) {}

    public static function productTypes(): array
    {
        return CouponProductTypes::all();
    }

    public function getAll(): mixed
    {
        return $this->coupons->getAll();
    }

    public function getDeleted(): mixed
    {
        return $this->coupons->getDeleted();
    }

    public function findById(int $id): mixed
    {
        return $this->coupons->findById($id);
    }

    public function findDeletedById(int $id): mixed
    {
        return $this->coupons->findDeletedById($id);
    }

    public function store(array $data): mixed
    {
        return $this->coupons->store($data);
    }

    public function update(int $id, array $data): mixed
    {
        return $this->coupons->update($id, $data);
    }

    public function destroy(int $id): void
    {
        $this->coupons->destroy($id);
    }

    public function restore(int $id): void
    {
        $this->coupons->restore($id);
    }

    public function forceDelete(int $id): void
    {
        $this->coupons->forceDelete($id);
    }

    public function validateAndCalculate(string $code, array $cartItems): array
    {
        return $this->coupons->validateAndCalculate($code, $cartItems);
    }

    public function incrementUsage(string $code): void
    {
        $this->coupons->incrementUsage($code);
    }

    public function decrementUsage(string $code): void
    {
        $this->coupons->decrementUsage($code);
    }

    public function getCartSubtotal(array $cartItems): int
    {
        return $this->coupons->getCartSubtotal($cartItems);
    }
}
