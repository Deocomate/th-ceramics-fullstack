<?php

namespace App\Domains\Content\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureContentWritesOpen
{
    public const LOCK_FILE = 'app/private/content-write.lock';

    public function handle(Request $request, Closure $next): Response
    {
        if ($request->isMethodSafe() || ! is_file(storage_path(self::LOCK_FILE))
            || $request->routeIs('admin.auth.*', 'admin.orders.*', 'admin.coupons.*',
                'admin.consultation-requests.*', 'admin.users.*', 'admin.content-archive.*')) {
            return $next($request);
        }

        abort(423, 'Đang đối soát dữ liệu nội dung. Vui lòng thử lại sau vài phút.');
    }
}
