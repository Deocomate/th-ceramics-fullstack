<?php

namespace App\Domains\Catalog\Models;

use App\Domains\Catalog\Infrastructure\Models\VariantPublicId;

if (! class_exists('App\Domains\Catalog\Models\VariantPublicId', false)) {
    class_alias(VariantPublicId::class, 'App\Domains\Catalog\Models\VariantPublicId');
}
