<?php

namespace App\Models;

use App\Domains\Identity\Infrastructure\Models\User as IdentityUser;

// Existing queued notifications store this model name in ModelIdentifier.
class_alias(IdentityUser::class, __NAMESPACE__.'\\User');
