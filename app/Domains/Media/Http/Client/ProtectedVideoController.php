<?php

namespace App\Domains\Media\Http\Client;

use App\Domains\Media\Infrastructure\MediaDisk;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ProtectedVideoController extends Controller
{
    private const CONTENT_TYPES = [
        'mp4' => 'video/mp4',
        'webm' => 'video/webm',
    ];

    /**
     * Stream a private video to the site's own player. The private disk also holds content
     * archives and image originals, so nothing but a video path may ever be served here.
     */
    public function __invoke(Request $request, string $path): BinaryFileResponse
    {
        $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));

        abort_unless(
            preg_match('~^[A-Za-z0-9_\-./]+$~', $path) === 1
                && ! str_contains($path, '..')
                && isset(self::CONTENT_TYPES[$extension])
                && MediaDisk::forPath($path) === 'local',
            404
        );

        abort_if($this->comesFromOutsideThePlayer($request), 403);

        $disk = Storage::disk('local');
        abort_unless($disk->exists($path), 404);

        // Built directly: response()->file() marks the response public, which would drop "private".
        return new BinaryFileResponse($disk->path($path), 200, [
            'Content-Type' => self::CONTENT_TYPES[$extension],
            'Cache-Control' => 'private, no-store',
        ], false);
    }

    /**
     * Browsers that do not send Sec-Fetch-* or Referer are let through; the signature still applies.
     */
    private function comesFromOutsideThePlayer(Request $request): bool
    {
        if (strtolower((string) $request->headers->get('Sec-Fetch-Dest')) === 'document'
            || strtolower((string) $request->headers->get('Sec-Fetch-Site')) === 'cross-site') {
            return true;
        }

        $referer = (string) $request->headers->get('Referer');

        return $referer !== ''
            && strtolower((string) parse_url($referer, PHP_URL_HOST)) !== strtolower($request->getHost());
    }
}
