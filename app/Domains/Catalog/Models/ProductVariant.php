<?php

namespace App\Domains\Catalog\Models;

use App\Domains\Catalog\Infrastructure\Models\ProductVariant;

if (! class_exists('App\Domains\Catalog\Models\ProductVariant', false)) {
    class_alias(ProductVariant::class, 'App\Domains\Catalog\Models\ProductVariant');
}
