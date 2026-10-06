<?php

namespace Tests\Unit\Architecture\Fixtures\Domains\Catalog\Domain;

class ValidDomainClass
{
    public function __construct(
        private readonly string $sku,
        private readonly int $priceInCents
    ) {
        if ($this->priceInCents < 0) {
            throw new \InvalidArgumentException('Price cannot be negative');
        }
    }

    public function getSku(): string
    {
        return $this->sku;
    }

    public function getPriceInCents(): int
    {
        return $this->priceInCents;
    }
}
