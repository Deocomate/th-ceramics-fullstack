<?php

namespace App\Domains\Media\Infrastructure;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class StagedImageStore
{
    public const CHUNK_BYTES = 512 * 1024;

    public const MAX_IMAGE_BYTES = 999_999;

    /**
     * Store an individual chunk for an upload session.
     */
    public function storeChunk(int $userId, string $uploadId, UploadedFile $chunk, int $chunkIndex, int $totalChunks): void
    {
        $disk = Storage::disk('local');
        $uploadDirectory = "image-upload-chunks/{$userId}/{$uploadId}";

        if (! $disk->put("{$uploadDirectory}/_meta.json", json_encode([
            'created_at' => now()->timestamp,
        ], JSON_THROW_ON_ERROR))
            || ! $disk->putFileAs($uploadDirectory, $chunk, (string) $chunkIndex)) {
            throw ValidationException::withMessages(['chunk' => 'Máy chủ chưa lưu được phần ảnh này. Hãy thử lại.']);
        }
    }

    /**
     * Assemble uploaded chunks, validate image constraints, and store into staged storage.
     *
     * @return array{success: bool, complete: bool, token: string, size: int, width: int, height: int}
     */
    public function assembleAndStage(int $userId, string $uploadId, int $totalChunks, string $originalName, string $sessionId): array
    {
        $disk = Storage::disk('local');
        $uploadDirectory = "image-upload-chunks/{$userId}/{$uploadId}";

        $lockPath = $disk->path("{$uploadDirectory}/_lock");
        $lock = fopen($lockPath, 'c');
        if ($lock === false || ! flock($lock, LOCK_EX)) {
            if (is_resource($lock)) {
                fclose($lock);
            }
            throw ValidationException::withMessages(['chunk' => 'Không thể tiếp tục tải ảnh lúc này. Hãy thử lại.']);
        }

        try {
            $completedPath = "{$uploadDirectory}/completed.json";
            if ($disk->exists($completedPath)) {
                $completed = json_decode($disk->get($completedPath), true);
                if (is_array($completed) && ! empty($completed['token'])
                    && $disk->exists("staged-images/{$userId}/{$completed['token']}/image.webp")) {
                    return $completed;
                }
            }

            $temporaryPath = tempnam(sys_get_temp_dir(), 'staged-webp-');
            if ($temporaryPath === false) {
                throw ValidationException::withMessages(['chunk' => 'Không thể tạo file tạm để ghép ảnh.']);
            }

            try {
                $output = fopen($temporaryPath, 'wb');
                if ($output === false) {
                    throw ValidationException::withMessages(['chunk' => 'Không thể ghi file ảnh tạm.']);
                }

                try {
                    $size = 0;
                    for ($chunkIndex = 0; $chunkIndex < $totalChunks; $chunkIndex++) {
                        $chunkPath = "{$uploadDirectory}/{$chunkIndex}";
                        if (! $disk->exists($chunkPath)) {
                            throw ValidationException::withMessages(['chunk' => 'Thiếu một phần ảnh tải lên. Hãy thử lại.']);
                        }

                        $chunkSize = $disk->size($chunkPath);
                        $size += $chunkSize;
                        if ($chunkSize > self::CHUNK_BYTES || $size > self::MAX_IMAGE_BYTES) {
                            throw ValidationException::withMessages(['chunk' => 'Ảnh WebP sau khi tối ưu phải nhỏ hơn 1MB.']);
                        }

                        $input = fopen($disk->path($chunkPath), 'rb');
                        if ($input === false) {
                            throw ValidationException::withMessages(['chunk' => 'Không đọc được một phần ảnh tải lên.']);
                        }
                        stream_copy_to_stream($input, $output);
                        fclose($input);
                    }
                } finally {
                    fclose($output);
                }

                $mime = (new \finfo(FILEINFO_MIME_TYPE))->file($temporaryPath);
                $dimensions = @getimagesize($temporaryPath);
                if ($mime !== 'image/webp'
                    || $dimensions === false
                    || max($dimensions[0], $dimensions[1]) > 2560
                    || ($dimensions[0] * $dimensions[1]) > 6_553_600
                    || $size === 0
                    || $size >= 1_000_000
                ) {
                    throw ValidationException::withMessages(['chunk' => 'Ảnh không phải WebP hợp lệ hoặc vượt quá giới hạn 1MB. Hãy chọn lại ảnh.']);
                }

                $token = $uploadId;
                $stagedDirectory = "staged-images/{$userId}/{$token}";
                if (! $disk->put("{$stagedDirectory}/image.webp", file_get_contents($temporaryPath))
                    || ! $disk->put("{$stagedDirectory}/metadata.json", json_encode([
                        'user_id' => $userId,
                        'session_hash' => hash('sha256', $sessionId),
                        'original_name' => pathinfo($originalName, PATHINFO_FILENAME).'.webp',
                        'expires_at' => now()->addHour()->timestamp,
                        'width' => $dimensions[0],
                        'height' => $dimensions[1],
                        'size' => $size,
                    ], JSON_THROW_ON_ERROR))) {
                    $disk->deleteDirectory($stagedDirectory);
                    throw ValidationException::withMessages(['chunk' => 'Không thể lưu ảnh tạm. Hãy thử lại.']);
                }

                $result = [
                    'success' => true,
                    'complete' => true,
                    'token' => $token,
                    'size' => $size,
                    'width' => $dimensions[0],
                    'height' => $dimensions[1],
                ];

                if (! $disk->put($completedPath, json_encode($result, JSON_THROW_ON_ERROR))) {
                    throw ValidationException::withMessages(['chunk' => 'Máy chủ chưa xác nhận ảnh đã tải lên. Hãy thử lại.']);
                }

                for ($chunkIndex = 0; $chunkIndex < $totalChunks; $chunkIndex++) {
                    $disk->delete("{$uploadDirectory}/{$chunkIndex}");
                }

                return $result;
            } finally {
                @unlink($temporaryPath);
            }
        } finally {
            flock($lock, LOCK_UN);
            fclose($lock);
        }
    }

    /**
     * Retrieve and validate a staged file by token, returning a test-mode UploadedFile.
     */
    public function getStagedFile(int $userId, string $token, string $sessionHash, string $errorField = 'images'): UploadedFile
    {
        $disk = Storage::disk('local');
        $directory = "staged-images/{$userId}/{$token}";
        $metadataPath = "{$directory}/metadata.json";
        $imagePath = "{$directory}/image.webp";

        if (! $disk->exists($metadataPath) || ! $disk->exists($imagePath)) {
            throw ValidationException::withMessages([
                $errorField => 'Ảnh tạm đã hết hạn hoặc không còn tồn tại. Hãy chọn lại ảnh rồi thử lại.',
            ]);
        }

        $metadata = json_decode($disk->get($metadataPath), true);
        if (! is_array($metadata) || (int) ($metadata['user_id'] ?? 0) !== $userId) {
            throw ValidationException::withMessages([
                $errorField => 'Ảnh tạm không thuộc tài khoản hiện tại. Hãy chọn lại ảnh rồi thử lại.',
            ]);
        }

        if (! hash_equals($sessionHash, (string) ($metadata['session_hash'] ?? ''))) {
            throw ValidationException::withMessages([
                $errorField => 'Phiên tải ảnh đã thay đổi. Hãy chọn lại ảnh rồi thử lại.',
            ]);
        }

        if ((int) ($metadata['expires_at'] ?? 0) < now()->timestamp
            || $disk->size($imagePath) >= 1_000_000
            || (new \finfo(FILEINFO_MIME_TYPE))->file($disk->path($imagePath)) !== 'image/webp'
        ) {
            throw ValidationException::withMessages([
                $errorField => 'Ảnh tạm không hợp lệ hoặc đã hết hạn. Hãy chọn lại ảnh rồi thử lại.',
            ]);
        }

        return new UploadedFile(
            $disk->path($imagePath),
            (string) ($metadata['original_name'] ?? 'optimized-image.webp'),
            'image/webp',
            UPLOAD_ERR_OK,
            true,
        );
    }

    /**
     * Clean up expired staged images and old upload chunks.
     */
    public function cleanExpired(): int
    {
        $disk = Storage::disk('local');
        $now = now()->timestamp;
        $removed = 0;

        foreach ($disk->allFiles('staged-images') as $path) {
            if (! str_ends_with($path, '/metadata.json')) {
                continue;
            }

            $metadata = json_decode($disk->get($path), true);
            if (! is_array($metadata) || (int) ($metadata['expires_at'] ?? 0) < $now) {
                $disk->deleteDirectory(dirname($path));
                $removed++;
            }
        }

        foreach ($disk->allFiles('image-upload-chunks') as $path) {
            if (! str_ends_with($path, '/_meta.json')) {
                continue;
            }

            $metadata = json_decode($disk->get($path), true);
            if (! is_array($metadata) || (int) ($metadata['created_at'] ?? 0) < $now - 3600) {
                $disk->deleteDirectory(dirname($path));
                $removed++;
            }
        }

        foreach ($disk->allFiles('gallery-chunks') as $path) {
            if (! str_ends_with($path, '/_meta.json')) {
                continue;
            }

            $metadata = json_decode($disk->get($path), true);
            if (! is_array($metadata) || (int) ($metadata['created_at'] ?? 0) < $now - 3600) {
                $disk->deleteDirectory(dirname($path));
                $removed++;
            }
        }

        return $removed;
    }
}
