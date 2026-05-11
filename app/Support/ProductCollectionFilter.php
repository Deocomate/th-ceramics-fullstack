<?php

namespace App\Support;

use Illuminate\Support\Collection;

class ProductCollectionFilter
{
    public static function apply(Collection $products, array $filters): Collection
    {
        $search = trim((string) ($filters['search'] ?? ''));
        $sort = (string) ($filters['sort'] ?? '');

        if ($search !== '') {
            $needle = mb_strtolower($search, 'UTF-8');
            $products = $products->filter(function ($product) use ($needle) {
                $haystack = mb_strtolower(
                    implode(' ', [
                        data_get($product, 'name', ''),
                        data_get($product, 'code', ''),
                        data_get($product, 'size', ''),
                    ]),
                    'UTF-8'
                );

                return str_contains($haystack, $needle);
            });
        }

        return match ($sort) {
            'price_asc' => $products->sortBy(fn ($product) => (float) data_get($product, 'price', 0)),
            'price_desc' => $products->sortByDesc(fn ($product) => (float) data_get($product, 'price', 0)),
            'name_asc' => $products->sortBy(fn ($product) => data_get($product, 'name', '')),
            default => $products,
        };
    }
}
