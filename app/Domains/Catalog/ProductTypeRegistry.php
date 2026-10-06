<?php

namespace App\Domains\Catalog;

use App\Domains\Catalog\Domain\ProductTypeRegistry as DomainProductTypeRegistry;

final class ProductTypeRegistry
{
    public static function all(): array
    {
        return DomainProductTypeRegistry::all();
    }

    public static function get(string $type): ?array
    {
        return DomainProductTypeRegistry::get($type);
    }

    public static function fromRoute(?string $route): ?string
    {
        return DomainProductTypeRegistry::fromRoute($route);
    }

    public static function detailRoute(string $type, ?string $categoryType = null): string
    {
        return DomainProductTypeRegistry::detailRoute($type, $categoryType);
    }
}
