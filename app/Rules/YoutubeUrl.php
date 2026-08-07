<?php

namespace App\Rules;

use App\Support\ProductGallery;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class YoutubeUrl implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! is_string($value) || ProductGallery::extractYoutubeId($value) === null) {
            $fail('Link YouTube không hợp lệ.');
        }
    }
}
