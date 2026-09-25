<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class StagedImageUploadController
{
    private const CHUNK_BYTES = 512 * 1024;

    private const MAX_IMAGE_BYTES = 999_999;

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'chunk' => ['required', 'file', 'max:512'],
            'upload_id' => ['required', 'uuid'],
            'chunk_index' => ['required', 'integer', 'min:0', 'max:1'],
            'total_chunks' => ['required', 'integer', 'min:1', 'max:2'],
            'original_name' => ['required', 'string', 'max:255'],
        ], [
            'chunk.max' => 'Mỗi phần ảnh tải lên không được vượt quá 512KB.',
        ]);

        $index = (int) $validated['chunk_index'];
        $total = (int) $validated['total_chunks'];
        if ($index >= $total) {
            throw ValidationException::withMessages(['chunk_index' => 'Phần ảnh tải lên không hợp lệ.']);
        }

        $originalName = $validated['original_name'];
        if (! preg_match('/\.(jpe?g|png|webp|heic|heif)$/i', $originalName)) {
            throw ValidationException::withMessages(['original_name' => 'Ảnh phải là JPG, PNG, WebP, HEIC hoặc HEIF.']);
        }

        $disk = Storage::disk('local');
        $userId = (int) $request->user()->getAuthIdentifier();
        $uploadDirectory = "image-upload-chunks/{$userId}/{$validated['upload_id']}";
        $disk->put("{$uploadDirectory}/_meta.json", json_encode([
            'created_at' => now()->timestamp,
        ], JSON_THROW_ON_ERROR));
        $disk->putFileAs($uploadDirectory, $validated['chunk'], (string) $index);

        if ($index < $total - 1) {
            return response()->json([
                'success' => true,
                'received' => $index + 1,
                'total_chunks' => $total,
            ]);
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
                for ($chunkIndex = 0; $chunkIndex < $total; $chunkIndex++) {
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

            $token = (string) str()->uuid();
            $stagedDirectory = "staged-images/{$userId}/{$token}";
            $sessionId = (string) $request->session()->getId();
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

            return response()->json([
                'success' => true,
                'complete' => true,
                'token' => $token,
                'size' => $size,
                'width' => $dimensions[0],
                'height' => $dimensions[1],
            ]);
        } finally {
            @unlink($temporaryPath);
            $disk->deleteDirectory($uploadDirectory);
        }
    }
}
