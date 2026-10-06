<?php

namespace App\Domains\Catalog\Models;

use App\Domains\Catalog\Infrastructure\Models\ProductPublicId;

if (! class_exists('App\Domains\Catalog\Models\ProductPublicId', false)) {
    class_alias(ProductPublicId::class, 'App\Domains\Catalog\Models\ProductPublicId');
}
