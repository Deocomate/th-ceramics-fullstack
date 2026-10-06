<?php

namespace Tests\Unit\Architecture\Fixtures\Domains\Catalog\Domain;

use Illuminate\Support\Str;

class DomainViolatingLaravelImport
{
    public function slugify(string $value): string
    {
        return Str::slug($value);
    }
}
