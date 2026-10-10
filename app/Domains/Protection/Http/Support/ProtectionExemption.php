<?php

namespace App\Domains\Protection\Http\Support;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProtectionExemption
{
    public function isExempt(Request $request): bool
    {
        if (in_array($request->user()?->role, ['superadmin', 'admin'], true)) {
            return true;
        }

        $userAgent = (string) $request->userAgent();

        return $userAgent !== ''
            && Str::contains($userAgent, (array) config('content_protection.exempt_user_agents', []), ignoreCase: true);
    }
}
