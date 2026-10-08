<?php

namespace App\Domains\Catalog\Models;

if (! class_exists(Product::class, false)) {
    class_alias(\App\Domains\Catalog\Infrastructure\Models\Product::class, Product::class);
}
