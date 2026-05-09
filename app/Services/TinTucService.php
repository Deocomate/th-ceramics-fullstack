<?php

namespace App\Services;

use App\Helpers\FileUploadHelper;
use App\Models\TinTuc;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TinTucService
{
    public function getAll(?int $danhMucId = null)
    {
        $query = TinTuc::query()->with('danhMuc')->latest();

        if ($danhMucId) {
            $query->where('danh_muc_tin_tuc_id', $danhMucId);
        }

        return $query->get();
    }

    public function findById(int $id): TinTuc
    {
        return TinTuc::findOrFail($id);
    }

    public function generateUniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $baseSlug = Str::slug($name);
        $slug = $baseSlug;
        $counter = 1;

        while (TinTuc::where('slug', $slug)->when($ignoreId, fn($q) => $q->where('tin_tuc_id', '!=', $ignoreId))->exists()) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    /**
     * Xử lý file upload trong mảng Blocks (JSON)
     */
    private function processBlocks(array $blocks, array $blockImages =[]): array
    {
        foreach ($blocks as $index => &$block) {
            // Upload ảnh 1 (Dùng cho split_content, image_metadata, full_width, call_to_action)
            if (isset($blockImages[$index]['image_url']) && $blockImages[$index]['image_url'] instanceof UploadedFile) {
                // Xóa ảnh cũ nếu có
                if (!empty($block['data']['image_url'])) FileUploadHelper::delete($block['data']['image_url']);
                $block['data']['image_url'] = FileUploadHelper::upload($blockImages[$index]['image_url'], 'tin_tuc/blocks');
            }

            // Upload ảnh 2 (Dành cho two_image_content)
            if (isset($blockImages[$index]['image_url_1']) && $blockImages[$index]['image_url_1'] instanceof UploadedFile) {
                if (!empty($block['data']['image_url_1'])) FileUploadHelper::delete($block['data']['image_url_1']);
                $block['data']['image_url_1'] = FileUploadHelper::upload($blockImages[$index]['image_url_1'], 'tin_tuc/blocks');
            }
            if (isset($blockImages[$index]['image_url_2']) && $blockImages[$index]['image_url_2'] instanceof UploadedFile) {
                if (!empty($block['data']['image_url_2'])) FileUploadHelper::delete($block['data']['image_url_2']);
                $block['data']['image_url_2'] = FileUploadHelper::upload($blockImages[$index]['image_url_2'], 'tin_tuc/blocks');
            }

            // Clean mảng specs (loại bỏ null)
            if (isset($block['data']['specs']) && is_array($block['data']['specs'])) {
                $block['data']['specs'] = array_values(array_filter($block['data']['specs'], function($spec) {
                    return !empty($spec['label']) || !empty($spec['value']);
                }));
            }
        }
        return array_values($blocks);
    }

    public function create(array $data): TinTuc
    {
        return DB::transaction(function () use ($data) {
            $fillable =[
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

            // Xử lý Blocks JSON
            if (isset($data['blocks']) && is_array($data['blocks'])) {
                $fillable['noi_dung_blocks'] = $this->processBlocks($data['blocks'], $data['block_images'] ?? []);
            } else {
                $fillable['noi_dung_blocks'] =[];
            }

            return TinTuc::create($fillable);
        });
    }

    public function update(int $id, array $data): TinTuc
    {
        $model = $this->findById($id);

        return DB::transaction(function () use ($model, $data) {
            $fillable =[
                'danh_muc_tin_tuc_id' => $data['danh_muc_tin_tuc_id'],
                'tieu_de' => $data['tieu_de'],
                'mo_ta_ngan' => $data['mo_ta_ngan'],
                'the_loai' => $data['the_loai'] ?? null,
                'trang_thai' => $data['trang_thai'],
            ];

            // Cập nhật ngày đăng nếu chuyển từ bản nháp sang công khai
            if ($model->trang_thai !== 'published' && $data['trang_thai'] === 'published') {
                $fillable['ngay_dang'] = now();
            }

            if ($model->tieu_de !== $data['tieu_de']) {
                $fillable['slug'] = $this->generateUniqueSlug($data['tieu_de'], $model->tin_tuc_id);
            }

            if (isset($data['anh_dai_dien']) && $data['anh_dai_dien'] instanceof UploadedFile) {
                $fillable['anh_dai_dien'] = FileUploadHelper::replace($data['anh_dai_dien'], $model->anh_dai_dien, 'tin_tuc/images');
            }

            // Cập nhật Blocks
            if (isset($data['blocks']) && is_array($data['blocks'])) {
                $fillable['noi_dung_blocks'] = $this->processBlocks($data['blocks'], $data['block_images'] ?? []);
            } else {
                $fillable['noi_dung_blocks'] =[];
            }

            $model->update($fillable);
            return $model->fresh();
        });
    }

    public function destroy(int $id): void
    {
        $model = $this->findById($id);
        
        // Xóa ảnh đại diện
        FileUploadHelper::delete($model->anh_dai_dien);
        
        // Xóa ảnh trong blocks
        if (is_array($model->noi_dung_blocks)) {
            foreach ($model->noi_dung_blocks as $block) {
                if (!empty($block['data']['image_url'])) FileUploadHelper::delete($block['data']['image_url']);
                if (!empty($block['data']['image_url_1'])) FileUploadHelper::delete($block['data']['image_url_1']);
                if (!empty($block['data']['image_url_2'])) FileUploadHelper::delete($block['data']['image_url_2']);
            }
        }
        
        $model->delete();
    }
}