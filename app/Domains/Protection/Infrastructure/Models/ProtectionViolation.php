<?php

namespace App\Domains\Protection\Infrastructure\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Prunable;

class ProtectionViolation extends Model
{
    use Prunable;

    public const UPDATED_AT = null;

    protected $fillable = [
        'session_hash',
        'ip_address',
        'user_agent',
        'user_id',
        'detector',
        'path',
    ];

    public function prunable(): Builder
    {
        return static::query()->where(
            'created_at',
            '<=',
            now()->subDays((int) config('content_protection.devtools.retention_days', 90)),
        );
    }
}
