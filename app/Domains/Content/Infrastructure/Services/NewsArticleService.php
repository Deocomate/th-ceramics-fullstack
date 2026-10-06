<?php

namespace App\Domains\Content\Infrastructure\Services;

use App\Domains\Content\Infrastructure\Models\NewsArticle;
use App\Domains\Media\Infrastructure\FileUploadHelper;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class NewsArticleService
{
    public function getAll(?int $danhMucId = null, int $perPage = 10): LengthAwarePaginator
    {
        $query = NewsArticle::query()->with('category')->latest();

        if ($danhMucId) {
            $query->where('danh_muc_tin_tuc_id', $danhMucId);
        }

        return $query->paginate($perPage);
    }

    public function findById(int $id): NewsArticle
    {
        return NewsArticle::findOrFail($id);
    }

    public function generateUniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $baseSlug = Str::slug($name);
        $slug = $baseSlug;
        $counter = 1;

        while (NewsArticle::where('slug', $slug)->when($ignoreId, fn ($q) => $q->where('tin_tuc_id', '!=', $ignoreId))->exists()) {
            $slug = $baseSlug.'-'.$counter;
            $counter++;
        }

        return $slug;
    }

    /**
     * @param  array<int, array<string, mixed>>  $blocks
     * @param  array<int, array<string, mixed>>  $blockImages
     * @return array<int, array<string, mixed>>
     */
    private function processBlocks(array $blocks, array $blockImages = []): array
    {
        foreach ($blocks as $index => &$block) {
            if (isset($blockImages[$index]['image_url']) && $blockImages[$index]['image_url'] instanceof UploadedFile) {
                if (! empty($block['data']['image_url'])) {
                    FileUploadHelper::delete($block['data']['image_url']);
                }
                $block['data']['image_url'] = FileUploadHelper::upload($blockImages[$index]['image_url'], 'tin_tuc/blocks');
            }

            if (isset($blockImages[$index]['image_url_1']) && $blockImages[$index]['image_url_1'] instanceof UploadedFile) {
                if (! empty($block['data']['image_url_1'])) {
                    FileUploadHelper::delete($block['data']['image_url_1']);
                }
                $block['data']['image_url_1'] = FileUploadHelper::upload($blockImages[$index]['image_url_1'], 'tin_tuc/blocks');
            }
            if (isset($blockImages[$index]['image_url_2']) && $blockImages[$index]['image_url_2'] instanceof UploadedFile) {
                if (! empty($block['data']['image_url_2'])) {
                    FileUploadHelper::delete($block['data']['image_url_2']);
                }
                $block['data']['image_url_2'] = FileUploadHelper::upload($blockImages[$index]['image_url_2'], 'tin_tuc/blocks');
            }

            if (isset($block['data']['specs']) && is_array($block['data']['specs'])) {
                $block['data']['specs'] = array_values(array_filter($block['data']['specs'], function ($spec) {
                    return ! empty($spec['label']) || ! empty($spec['value']);
                }));
            }
        }

        return array_values($blocks);
    }

    /** @param array<string, mixed> $data */
    public function create(array $data): NewsArticle
    {
        return DB::transaction(function () use ($data) {
            $fillable = [
                'danh_muc_tin_tuc_id' => $data['danh_muc_tin_tuc_id'],
                'tieu_de' => $data['tieu_de'],
                'slug' => $this->generateUniqueSlug($data['tieu_de']),
                'mo_ta_ngan' => $data['mo_ta_ngan'],
                'the_loai' => $data['the_loai'] ?? null,
                'trang_thai' => $data['trang_thai'],
                'ngay_dang' => $data['trang_thai'] === 'published' ? now() : null,
            ];

            if (isset($data['anh_dai_dien']) && $data['anh_dai_dien'] instanceof UploadedFile) {
                $fillable['anh_dai_dien'] = FileUploadHelper::upload($data['anh_dai_dien'], 'tin_tuc/images');
            }

            if (isset($data['blocks']) && is_array($data['blocks'])) {
                $fillable['noi_dung_blocks'] = $this->processBlocks($data['blocks'], $data['block_images'] ?? []);
            } else {
                $fillable['noi_dung_blocks'] = [];
            }

            return NewsArticle::create($fillable);
        });
    }

    /** @param array<string, mixed> $data */
    public function update(int $id, array $data): NewsArticle
    {
        $model = $this->findById($id);

        return DB::transaction(function () use ($model, $data) {
            $fillable = [
                'danh_muc_tin_tuc_id' => $data['danh_muc_tin_tuc_id'],
                'tieu_de' => $data['tieu_de'],
                'mo_ta_ngan' => $data['mo_ta_ngan'],
                'the_loai' => $data['the_loai'] ?? null,
                'trang_thai' => $data['trang_thai'],
            ];

            if ($model->trang_thai !== 'published' && $data['trang_thai'] === 'published') {
                $fillable['ngay_dang'] = now();
            }

            if ($model->tieu_de !== $data['tieu_de']) {
                $fillable['slug'] = $this->generateUniqueSlug($data['tieu_de'], $model->tin_tuc_id);
            }

            if (isset($data['anh_dai_dien']) && $data['anh_dai_dien'] instanceof UploadedFile) {
                $fillable['anh_dai_dien'] = FileUploadHelper::replace($data['anh_dai_dien'], $model->anh_dai_dien, 'tin_tuc/images');
            }

            if (isset($data['blocks']) && is_array($data['blocks'])) {
                $fillable['noi_dung_blocks'] = $this->processBlocks($data['blocks'], $data['block_images'] ?? []);
            } else {
                $fillable['noi_dung_blocks'] = [];
            }

            $model->update($fillable);

            return $model->fresh();
        });
    }

    public function destroy(int $id): void
    {
        $model = $this->findById($id);

        FileUploadHelper::delete($model->anh_dai_dien);

        if (is_array($model->noi_dung_blocks)) {
            foreach ($model->noi_dung_blocks as $block) {
                if (! empty($block['data']['image_url'])) {
                    FileUploadHelper::delete($block['data']['image_url']);
                }
                if (! empty($block['data']['image_url_1'])) {
                    FileUploadHelper::delete($block['data']['image_url_1']);
                }
                if (! empty($block['data']['image_url_2'])) {
                    FileUploadHelper::delete($block['data']['image_url_2']);
                }
            }
        }

        $model->delete();
    }
}
