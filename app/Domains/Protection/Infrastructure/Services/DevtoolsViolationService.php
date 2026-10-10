<?php

namespace App\Domains\Protection\Infrastructure\Services;

use App\Domains\Protection\Infrastructure\Models\ProtectionViolation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DevtoolsViolationService
{
    // The detector keeps firing while the tools stay open, and several tabs can
    // report at once; reports this close together are one detection.
    private const DEDUPE_SECONDS = 5;

    private const DETECTORS = [
        0 => 'reg-to-string',
        1 => 'define-id',
        2 => 'size',
        3 => 'date-to-string',
        4 => 'func-to-string',
        5 => 'debugger',
        6 => 'performance',
        7 => 'debug-lib',
    ];

    /** @return array{count: int, escalated: bool, id: int} */
    public function record(Request $request): array
    {
        // Only the hash is stored so a leaked table cannot be used to hijack sessions.
        $sessionHash = hash('sha256', $request->session()->getId());
        $ipAddress = (string) $request->ip();
        $path = $this->text($request->input('path'));
        $userAgent = $this->text($request->userAgent());

        $violation = ProtectionViolation::query()
            ->where('session_hash', $sessionHash)
            ->where('created_at', '>', now()->subSeconds(self::DEDUPE_SECONDS))
            ->latest('id')
            ->first()
            ?? ProtectionViolation::query()->create([
                'session_hash' => $sessionHash,
                'ip_address' => $ipAddress,
                'user_agent' => $userAgent === '' ? null : $userAgent,
                'user_id' => $request->user()?->getAuthIdentifier(),
                'detector' => $this->detectorName($request->input('detector')),
                'path' => $path,
            ]);

        $count = $this->countInWindow('session_hash', $sessionHash);
        if (config('content_protection.devtools.count_by_ip', true)) {
            $count = max($count, $this->countInWindow('ip_address', $ipAddress));
        }

        $escalated = $count >= max(1, (int) config('content_protection.devtools.threshold', 3));

        if ($escalated && $violation->wasRecentlyCreated) {
            Log::warning('Developer tools detected repeatedly; visitor sent to the copyright warning page.', [
                'ip' => $ipAddress,
                'user_agent' => $userAgent,
                'path' => $path,
                'count' => $count,
            ]);
        }

        return ['count' => $count, 'escalated' => $escalated, 'id' => $violation->id];
    }

    private function countInWindow(string $column, string $value): int
    {
        $hours = max(1, (int) config('content_protection.devtools.window_hours', 24));

        return ProtectionViolation::query()
            ->where($column, $value)
            ->where('created_at', '>', now()->subHours($hours))
            ->count();
    }

    private function detectorName(mixed $type): string
    {
        return is_int($type) ? (self::DETECTORS[$type] ?? 'unknown') : 'unknown';
    }

    private function text(mixed $value): string
    {
        return is_string($value) ? mb_substr($value, 0, 255) : '';
    }
}
