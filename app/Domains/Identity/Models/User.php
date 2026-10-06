<?php

namespace App\Domains\Identity\Models;

use App\Domains\Identity\Infrastructure\Models\User as TargetUser;

class_alias(TargetUser::class, __NAMESPACE__.'\\User');
