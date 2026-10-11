<?php

namespace App\Domains\Content\Infrastructure\Services;

use App\Domains\Content\Infrastructure\Models\Catalog;
use App\Domains\Media\Infrastructure\FileUploadHelper;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class CatalogService
{
    public function getAll(int $perPage = 10): LengthAwarePaginator
    {
        return Catalog::query()->latest()->paginate($perPage);
    }

    public function findById(int $id): Catalog
    {
        return Catalog::query()->findOrFail($id);
    }

    /** @param array<string, mixed> $data */
    public function store(array $data): Catalog
    {
        $fillable = [
            'tieu_de' => $data['tieu_de'] ?? null,
        ];

        try {
            if (isset($data['anh_dai_dien']) && $data['anh_dai_dien'] instanceof UploadedFile) {
                $fillable['anh_dai_dien'] = FileUploadHelper::upload($data['anh_dai_dien'], 'catalog/images');
            }

            if (isset($data['file']) && $data['file'] instanceof UploadedFile) {
                $fillable['file'] = FileUploadHelper::upload($data['file'], 'catalog/files');
            }
        } catch (\Throwable $e) {
            Log::error('Catalog file upload failed during store', [
                'error' => $e->getMessage(),
                'data' => [
                    'tieu_de' => $data['tieu_de'] ?? null,
                    'anh_dai_dien' => isset($data['anh_dai_dien']) ? $data['anh_dai_dien']->getClientOriginalName() : null,
                    'file' => isset($data['file']) ? $data['file']->getClientOriginalName() : null,
                ],
            ]);
            throw new \RuntimeException('Không thể lưu file, vui lòng thử lại.', 0, $e);
        }

        return Catalog::query()->create($fillable);
    }

    /** @param array<string, mixed> $data */
    public function update(int $id, array $data): Catalog
    {
        $model = $this->findById($id);

        $fillable = [
            'tieu_de' => array_key_exists('tieu_de', $data) ? $data['tieu_de'] : $model->tieu_de,
        ];

        try {
            if (isset($data['anh_dai_dien']) && $data['anh_dai_dien'] instanceof UploadedFile) {
                $fillable['anh_dai_dien'] = FileUploadHelper::replace($data['anh_dai_dien'], $model->anh_dai_dien, 'catalog/images');
            }

            if (isset($data['file']) && $data['file'] instanceof UploadedFile) {
                $fillable['file'] = FileUploadHelper::replace($data['file'], $model->file, 'catalog/files');
                // Page images belong to the file they were rendered from.
                $fillable['pages'] = null;
            }
        } catch (\Throwable $e) {
            Log::error('Catalog file upload/replace failed during update', [
                'catalog_id' => $id,
                'error' => $e->getMessage(),
                'data' => [
                    'tieu_de' => $data['tieu_de'] ?? null,
                    'anh_dai_dien' => isset($data['anh_dai_dien']) ? $data['anh_dai_dien']->getClientOriginalName() : null,
                    'file' => isset($data['file']) ? $data['file']->getClientOriginalName() : null,
                ],
            ]);
            throw new \RuntimeException('Không thể lưu file, vui lòng thử lại.', 0, $e);
        }

        $previousBatch = $this->batchDirectory($model->pages);
        $model->fill($fillable)->save();

        if (array_key_exists('pages', $fillable)) {
            $this->purgePageBatches($model, $previousBatch);
        }

        return $model->fresh();
    }

    public function destroy(int $id): void
    {
        $model = $this->findById($id);
        FileUploadHelper::delete($model->anh_dai_dien);
        FileUploadHelper::delete($model->file);
        $this->purgePageBatches($model, $this->batchDirectory($model->pages));

        $model->delete();
    }

    /**
     * Store one rendered page of a batch. Readers do not see the batch until it is finalized.
     */
    public function storePage(int $id, string $batch, int $index, UploadedFile $image): string
    {
        $catalog = $this->findById($id);
        $directory = $this->pagesRoot($catalog).'/'.$batch;

        if ($index === 0) {
            // A new run supersedes any batch that was abandoned before it was finalized.
            $this->purgePageBatches($catalog, null, array_filter([$this->batchDirectory($catalog->pages), $directory]));
        }

        $path = $image->storeAs($directory, sprintf('%03d.webp', $index), 'public');
        if ($path === false) {
            throw new \RuntimeException('Không thể lưu ảnh trang, vui lòng thử lại.');
        }

        return $path;
    }

    /**
     * Publish a batch once every page is on disk, then drop the batch it replaces.
     *
     * @param  array<int, array{pdf_page: int|string, side: string}>  $pages
     */
    public function finalizePages(int $id, string $batch, array $pages): Catalog
    {
        $catalog = $this->findById($id);
        $disk = Storage::disk('public');
        $directory = $this->pagesRoot($catalog).'/'.$batch;

        $items = [];
        foreach (array_values($pages) as $index => $page) {
            $path = sprintf('%s/%03d.webp', $directory, $index);
            $size = $disk->exists($path) ? @getimagesize($disk->path($path)) : false;
            if ($size === false) {
                throw ValidationException::withMessages([
                    'pages' => 'Thiếu ảnh của trang '.($index + 1).'. Hãy tạo lại ảnh trang.',
                ]);
            }

            $items[] = [
                'path' => $path,
                'w' => $size[0],
                'h' => $size[1],
                'pdf_page' => (int) $page['pdf_page'],
                'side' => $page['side'],
            ];
        }

        $previousBatch = $this->batchDirectory($catalog->pages);
        $catalog->update(['pages' => ['batch' => $batch, 'items' => $items]]);
        $this->purgePageBatches($catalog, $previousBatch, [$directory]);

        return $catalog;
    }

    private function pagesRoot(Catalog $catalog): string
    {
        return 'catalog/pages/'.$catalog->getKey();
    }

    /**
     * Directory holding the page images of a manifest, or null when it has none.
     *
     * @param  array<string, mixed>|null  $pages
     */
    private function batchDirectory(?array $pages): ?string
    {
        $path = $pages['items'][0]['path'] ?? null;

        return is_string($path) && str_starts_with($path, 'catalog/pages/') && ! str_contains($path, '..')
            ? dirname($path)
            : null;
    }

    /**
     * Delete the catalog's page image batches except the ones to keep.
     *
     * @param  string|null  $previousBatch  Batch directory of a manifest that was just replaced or removed.
     * @param  array<int, string>  $keep
     */
    private function purgePageBatches(Catalog $catalog, ?string $previousBatch, array $keep = []): void
    {
        $disk = Storage::disk('public');
        $root = $this->pagesRoot($catalog);

        // An imported archive can leave another catalog showing images stored under this id.
        $inUse = Catalog::query()
            ->whereKeyNot($catalog->getKey())
            ->whereNotNull('pages')
            ->get()
            ->map(fn (Catalog $other): ?string => $this->batchDirectory($other->pages))
            ->filter()
            ->all();

        $stale = array_diff(array_filter([...$disk->directories($root), $previousBatch]), $keep, $inUse);
        foreach (array_unique($stale) as $directory) {
            $disk->deleteDirectory($directory);
        }

        if ($disk->allFiles($root) === []) {
            $disk->deleteDirectory($root);
        }
    }
}
