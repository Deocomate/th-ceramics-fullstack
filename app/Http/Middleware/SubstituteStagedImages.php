<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class SubstituteStagedImages
{
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

        $disk = Storage::disk('local');
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

                $directory = "staged-images/{$userId}/{$token}";
                $metadataPath = "{$directory}/metadata.json";
                $imagePath = "{$directory}/image.webp";
                if (! $disk->exists($metadataPath) || ! $disk->exists($imagePath)) {
                    throw ValidationException::withMessages([
                        $fieldName => 'Ảnh tạm đã hết hạn hoặc không còn tồn tại. Hãy chọn lại ảnh rồi thử lại.',
                    ]);
                }

                $metadata = json_decode($disk->get($metadataPath), true);
                if (! is_array($metadata) || (int) ($metadata['user_id'] ?? 0) !== $userId) {
                    throw ValidationException::withMessages([
                        $fieldName => 'Ảnh tạm không thuộc tài khoản hiện tại. Hãy chọn lại ảnh rồi thử lại.',
                    ]);
                }
                if (! hash_equals($sessionHash, (string) ($metadata['session_hash'] ?? ''))) {
                    throw ValidationException::withMessages([
                        $fieldName => 'Phiên tải ảnh đã thay đổi. Hãy chọn lại ảnh rồi thử lại.',
                    ]);
                }
                if ((int) ($metadata['expires_at'] ?? 0) < now()->timestamp
                    || $disk->size($imagePath) >= 1_000_000
                    || (new \finfo(FILEINFO_MIME_TYPE))->file($disk->path($imagePath)) !== 'image/webp'
                ) {
                    throw ValidationException::withMessages([
                        $fieldName => 'Ảnh tạm không hợp lệ hoặc đã hết hạn. Hãy chọn lại ảnh rồi thử lại.',
                    ]);
                }

                $uploadedFiles[] = new UploadedFile(
                    $disk->path($imagePath),
                    (string) ($metadata['original_name'] ?? 'optimized-image.webp'),
                    'image/webp',
                    UPLOAD_ERR_OK,
                    true,
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
