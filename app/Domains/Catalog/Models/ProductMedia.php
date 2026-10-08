<?php

namespace App\Domains\Catalog\Models;

use App\Domains\Catalog\Infrastructure\Models\ProductMedia;

if (! class_exists('App\Domains\Catalog\Models\ProductMedia', false)) {
    class_alias(ProductMedia::class, 'App\Domains\Catalog\Models\ProductMedia');
}
