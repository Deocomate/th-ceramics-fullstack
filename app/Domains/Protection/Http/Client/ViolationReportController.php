<?php

namespace App\Domains\Protection\Http\Client;

use App\Domains\Protection\Http\Support\ProtectionExemption;
use App\Domains\Protection\Infrastructure\ProtectionSettings;
use App\Domains\Protection\Infrastructure\Services\DevtoolsViolationService;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ViolationReportController extends Controller
{
    public const SESSION_KEY = 'protection_violation_id';

    public function __invoke(
        Request $request,
        ProtectionSettings $settings,
        ProtectionExemption $exemption,
        DevtoolsViolationService $violations,
    ): JsonResponse|Response {
        if (! $settings->devtoolsGuardEnabled() || $exemption->isExempt($request)) {
            return response()->noContent();
        }

        $result = $violations->record($request);
        $request->session()->put(self::SESSION_KEY, $result['id']);

        return response()->json([
            'redirect' => route($result['escalated'] ? 'client.protection.warning' : 'client.protection.notice'),
        ]);
    }
}
