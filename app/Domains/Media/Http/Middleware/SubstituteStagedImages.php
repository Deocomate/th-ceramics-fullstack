<?php

namespace App\Domains\Media\Http\Middleware;

use App\Domains\Media\Infrastructure\StagedImageStore;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Validation\ValidationException;

class SubstituteStagedImages
{
    public function __construct(
        protected StagedImageStore $stagedStore,
    ) {}

    public function handle(Request $request, Closure $next): mixed
    {
        $mapping = $request->input('__staged_images');
        if (! is_string($mapping) || $mapping === '') {
            return $next($request);
        }

        $stagedFields = json_decode($mapping, true);
        if (! is_array($stagedFields)) {
            throw ValidationException::withMessages([
                'images' => 'Thông tin ảnh tải lên không hợp lệ. Hãy chọn lại ảnh rồi thử lại.',
            ]);
        }

        $userId = (int) ($request->user()?->getAuthIdentifier() ?? 0);
        if ($userId === 0) {
            throw ValidationException::withMessages([
                'images' => 'Phiên đăng nhập đã hết hạn. Hãy đăng nhập lại rồi thử lại.',
            ]);
        }

        $sessionHash = hash('sha256', (string) $request->session()->getId());
        // Read from Symfony's bag directly so Laravel's allFiles() conversion cache stays empty
        // until the staged files have been inserted.
        $files = $request->files->all();

        foreach ($stagedFields as $fieldName => $tokens) {
            if (! is_string($fieldName)
                || ! preg_match('/^[A-Za-z][A-Za-z0-9_.\[\]-]*$/', $fieldName)
                || str_starts_with($fieldName, '__')
                || ! is_array($tokens)
            ) {
                throw ValidationException::withMessages([
                    'images' => 'Thông tin trường ảnh không hợp lệ. Hãy tải lại trang rồi thử lại.',
                ]);
            }

            preg_match_all('/[^\[\].]+/', $fieldName, $matches);
            $segments = $matches[0] ?? [];
            if ($segments === []) {
                throw ValidationException::withMessages(['images' => 'Không xác định được trường ảnh cần lưu.']);
            }

            $uploadedFiles = [];
            foreach ($tokens as $token) {
                if (! is_string($token) || ! preg_match('/^[0-9a-f-]{36}$/i', $token)) {
                    throw ValidationException::withMessages([
                        $fieldName => 'Ảnh tạm không hợp lệ. Hãy chọn lại ảnh rồi thử lại.',
                    ]);
                }

                $uploadedFiles[] = $this->stagedStore->getStagedFile(
                    $userId,
                    $token,
                    $sessionHash,
                    $fieldName,
                );
            }

            $arrayField = str_ends_with($fieldName, '[]');
            Arr::set($files, implode('.', $segments), $arrayField ? $uploadedFiles : ($uploadedFiles[0] ?? null));
        }

        $request->request->remove('__staged_images');
        $request->files->replace($files);

        return $next($request);
    }
}
