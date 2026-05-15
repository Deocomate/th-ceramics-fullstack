<?php

namespace App\Services;

use App\Helpers\FileUploadHelper;
use App\Models\PageFactory;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;

class FactoryPageService
{
    public function getFirstRecord(): PageFactory
    {
        return PageFactory::query()->firstOrFail();
    }

    public function update(array $data): PageFactory
    {
        $model = $this->getFirstRecord();

        return DB::transaction(function () use ($model, $data) {
            $singleImages = ['hero_banner_desktop', 'hero_banner_mobile', 'process_bottom_image'];

            foreach ($singleImages as $field) {
                if (isset($data[$field]) && $data[$field] instanceof UploadedFile) {
                    $data[$field] = FileUploadHelper::replace($data[$field], $model->{$field}, 'pages/factory');
                } else {
                    unset($data[$field]);
                }
            }

            $galleries = ['gallery_1', 'gallery_2', 'process_slider', 'material_slider'];

            foreach ($galleries as $gallery) {
                $existing = $model->{$gallery} ?? [];

                if (! empty($data["delete_{$gallery}"])) {
                    foreach ($data["delete_{$gallery}"] as $index) {
                        $index = (int) $index;
                        if (isset($existing[$index])) {
                            FileUploadHelper::delete($existing[$index]);
                            unset($existing[$index]);
                        }
                    }
                    unset($data["delete_{$gallery}"]);
                }

                if (! empty($data["new_{$gallery}"]) && is_array($data["new_{$gallery}"])) {
                    foreach ($data["new_{$gallery}"] as $file) {
                        if ($file instanceof UploadedFile) {
                            $existing[] = FileUploadHelper::upload($file, "pages/factory/{$gallery}");
                        }
                    }
                    unset($data["new_{$gallery}"]);
                }

                $data[$gallery] = array_values($existing);
            }

            foreach (['intro_description', 'process_description', 'process_bottom_desc'] as $field) {
                if (array_key_exists($field, $data)) {
                    $data[$field] = $this->cleanBlocks($data[$field]);
                }
            }

            $fillable = array_intersect_key($data, array_flip($model->getFillable()));
            $model->update($fillable);

            return $model->fresh();
        });
    }

    private function cleanBlocks(mixed $blocks): array
    {
        if (! is_array($blocks)) {
            return [];
        }

        $cleaned = [];

        foreach ($blocks as $block) {
            if (! is_array($block)) {
                continue;
            }

            $type = $block['type'] ?? null;

            if ($type === 'paragraph') {
                $content = trim((string) ($block['content'] ?? ''));

                if ($content !== '') {
                    $cleaned[] = [
                        'type' => 'paragraph',
                        'content' => $content,
                    ];
                }

                continue;
            }

            if ($type !== 'list') {
                continue;
            }

            $items = [];

            foreach (($block['items'] ?? []) as $item) {
                if (! is_array($item)) {
                    continue;
                }

                $title = trim((string) ($item['title'] ?? ''));
                $content = trim((string) ($item['content'] ?? ''));

                if ($title === '' && $content === '') {
                    continue;
                }

                $items[] = [
                    'title' => $title,
                    'content' => $content,
                ];
            }

            if ($items !== []) {
                $cleaned[] = [
                    'type' => 'list',
                    'items' => $items,
                ];
            }
        }

        return $cleaned;
    }
}
