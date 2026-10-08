<?php

namespace Tests\Unit\Architecture\Fixtures\Domains\Catalog\Domain;

class DomainViolatingLaravelHelper
{
    public function getUrl(): string
    {
        return route('client.home');
    }
}
