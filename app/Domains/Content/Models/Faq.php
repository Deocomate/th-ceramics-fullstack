<?php

namespace App\Domains\Content\Models;

use App\Domains\Content\Infrastructure\Models\Faq as CanonicalFaq;

class_alias(CanonicalFaq::class, __NAMESPACE__.'\\Faq');
