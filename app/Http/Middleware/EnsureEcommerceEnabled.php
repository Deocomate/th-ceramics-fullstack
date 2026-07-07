<?php

namespace App\Http\Middleware;

use App\Models\TrangChu;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\HttpFoundation\Response;

class EnsureEcommerceEnabled
{
    public function handle(Request $request, Closure $next): Response
    {
        $enabled = true;

        if (Schema::hasTable('trang_chu') && Schema::hasColumn('trang_chu', 'is_ecommerce_enabled')) {
            $enabled = Cache::rememberForever(
                'site_ecommerce_enabled',
                static fn () => (bool) (TrangChu::query()->value('is_ecommerce_enabled') ?? true),
            );
        }

        if (! $enabled) {
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
