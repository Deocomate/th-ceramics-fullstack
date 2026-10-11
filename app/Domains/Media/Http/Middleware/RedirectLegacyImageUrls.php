<?php

namespace App\Domains\Media\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class RedirectLegacyImageUrls
{
    /**
     * Send the old JPG/PNG URL of an image that was converted to its WebP file.
     *
     * The web server serves existing files itself, so a request that reaches
     * the application for an image path means the file is gone.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $pattern = '~^(storage/|assets/images/)(.+)\.(?:jpe?g|png)$~i';
        // Files are looked up by their decoded name; the Location header keeps the encoded one.
        $path = $request->decodedPath();

        if ($request->isMethodSafe()
            && ! str_contains($path, '..')
            && ! str_contains($path, "\0")
            && preg_match($pattern, $path, $match)
            && preg_match($pattern, $request->path(), $raw)
        ) {
            $webp = $match[2].'.webp';
            $converted = $match[1] === 'storage/'
                ? ! Storage::disk('public')->exists(substr($path, 8)) && Storage::disk('public')->exists($webp)
                : ! is_file(public_path($path)) && is_file(public_path($match[1].$webp));

            if ($converted) {
                return redirect()->to('/'.$raw[1].$raw[2].'.webp', 301);
            }
        }

        return $next($request);
    }
}
