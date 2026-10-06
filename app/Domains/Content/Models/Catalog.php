<?php

namespace App\Domains\Content\Models;

use App\Domains\Content\Infrastructure\Models\Catalog as CanonicalCatalog;

class_alias(CanonicalCatalog::class, __NAMESPACE__.'\\Catalog');
