<?php

use App\Domains\Content\Infrastructure\Models\Project;
use App\Domains\Content\Infrastructure\Models\ProjectCategory;

test('project listing uses custom pagination and preserves category query string', function () {
    $category = ProjectCategory::query()->create([
        'ten_danh_muc' => 'Gốm Việt',
        'is_delete' => 0,
    ]);

    for ($i = 1; $i <= 9; $i++) {
        Project::query()->create([
            'ten_du_an' => 'Dự án phân trang '.str_pad((string) $i, 2, '0', STR_PAD_LEFT),
            'dia_diem' => 'Hà Nội',
            'san_pham' => 'Gốm sứ',
            'nam' => 2026,
            'images' => ['assets/images/factory-01.webp'],
            'danh_muc_du_an_id' => $category->danh_muc_du_an_id,
            'slug' => 'du-an-phan-trang-'.$i,
        ]);
    }

    $this->get(route('client.projects.index', ['category' => 'gom-viet']))
        ->assertOk()
        ->assertSee('aria-label="Pagination"', false)
        ->assertSee('category=gom-viet', false)
        ->assertSee('page=2', false);
});
