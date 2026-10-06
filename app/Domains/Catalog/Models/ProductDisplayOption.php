<?php

namespace App\Domains\Catalog\Models;

use App\Domains\Catalog\Infrastructure\Models\ProductDisplayOption;

if (! class_exists('App\Domains\Catalog\Models\ProductDisplayOption', false)) {
    class_alias(ProductDisplayOption::class, 'App\Domains\Catalog\Models\ProductDisplayOption');
}
