<?php

namespace App\Domains\Commerce\Http\Middleware;

use App\Domains\Commerce\Application\Ports\EcommerceStatusPort;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureEcommerceEnabled
{
    public function __construct(private readonly EcommerceStatusPort $status) {}

    public function handle(Request $request, Closure $next): Response
    {
        if (! $this->status->enabled()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Tính năng đặt hàng trực tuyến đang tạm khóa.',
                ], 403);
            }

            return redirect()
                ->route('client.home')
                ->with('error', 'Tính năng đặt hàng trực tuyến hiện đang bảo trì.');
        }

        return $next($request);
    }
}
