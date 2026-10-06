<?php

namespace App\Domains\Media\Http\Admin;

use App\Domains\Media\Infrastructure\StagedImageStore;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class StagedImageUploadController
{
    public function store(Request $request, StagedImageStore $stagedStore): JsonResponse
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

        $userId = (int) $request->user()->getAuthIdentifier();
        $uploadId = (string) $validated['upload_id'];

        $stagedStore->storeChunk($userId, $uploadId, $validated['chunk'], $index, $total);

        if ($index < $total - 1) {
            return response()->json([
                'success' => true,
                'received' => $index + 1,
                'total_chunks' => $total,
            ]);
        }

        $result = $stagedStore->assembleAndStage(
            $userId,
            $uploadId,
            $total,
            $originalName,
            (string) $request->session()->getId(),
        );

        return response()->json($result);
    }
}
