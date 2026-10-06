<?php

namespace App\Domains\Archive\Application\Ports;

interface CatalogArchivePort
{
    /**
     * @param  array<string, mixed>  $productData
     * @param  array<string, mixed>  $defaultVariantData
     * @param  list<mixed>  $images
     * @return array{product_id: int, status: 'added'|'updated'}
     */
    public function upsertLegacyProduct(
        string $type,
        int $legacyId,
        array $productData,
        bool $hasDefaultVariant,
        array $defaultVariantData,
        array $images,
    ): array;

    /**
     * @param  array<string, mixed>  $variantData
     * @return array{variant_id: int, status: 'added'|'updated'}|null
     */
    public function upsertLegacyVariant(
        string $parentType,
        int $parentLegacyId,
        int $legacyId,
        array $variantData,
    ): ?array;

    /**
     * @param  array<string, mixed>  $optionData
     * @return array{option_id: int, status: 'added'|'updated'}
     */
    public function upsertLegacyDisplayOption(int $legacyId, array $optionData): array;

    /**
     * Reconcile sequence numbers for public IDs across catalog tables.
     */
    public function reconcileSequences(): void;
}
