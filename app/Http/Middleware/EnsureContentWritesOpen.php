<?php

namespace App\Http\Middleware;

use App\Domains\Content\Http\Middleware\EnsureContentWritesOpen as CanonicalEnsureContentWritesOpen;

class EnsureContentWritesOpen extends CanonicalEnsureContentWritesOpen
{
}
