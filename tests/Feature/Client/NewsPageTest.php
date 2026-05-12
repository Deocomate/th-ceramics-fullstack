<?php

use App\Models\DanhMucTinTuc;
use App\Models\NgoiAmDuongCt;
use App\Models\TinTuc;

function createNewsArticle(DanhMucTinTuc $category, array $overrides = []): TinTuc
{
    static $counter = 1;

    return TinTuc::query()->create(array_merge([
        'danh_muc_tin_tuc_id' => $category->danh_muc_tin_tuc_id,
        'tieu_de' => 'Bai viet '.$counter,
        'slug' => 'bai-viet-'.$counter++,
        'anh_dai_dien' => 'assets/images/news-01.jpg',
        'mo_ta_ngan' => 'Mo ta ngan cho bai viet.',
        'trang_thai' => 'published',
        'ngay_dang' => now()->subDays($counter),
    ], $overrides));
}

test('news index groups newest articles by visible categories', function () {
    $visibleCategory = DanhMucTinTuc::query()->create([
        'ten_danh_muc' => 'Cam nang xay dung',
        'is_delete' => false,
    ]);
    $emptyCategory = DanhMucTinTuc::query()->create([
        'ten_danh_muc' => 'Danh muc rong',
        'is_delete' => false,
    ]);
    $deletedCategory = DanhMucTinTuc::query()->create([
        'ten_danh_muc' => 'Danh muc da xoa',
        'is_delete' => true,
    ]);

    createNewsArticle($visibleCategory, [
        'tieu_de' => 'Bai moi nhat',
        'slug' => 'bai-moi-nhat',
        'ngay_dang' => now(),
    ]);
    createNewsArticle($visibleCategory, [
        'tieu_de' => 'Bai moi thu hai',
        'slug' => 'bai-moi-thu-hai',
        'ngay_dang' => now()->subDay(),
    ]);
    createNewsArticle($visibleCategory, [
        'tieu_de' => 'Bai cu thu ba',
        'slug' => 'bai-cu-thu-ba',
        'ngay_dang' => now()->subDays(2),
    ]);
    createNewsArticle($deletedCategory, [
        'tieu_de' => 'Bai trong danh muc da xoa',
        'slug' => 'bai-trong-danh-muc-da-xoa',
    ]);

    $response = $this->get(route('client.news.index'));

    $response
        ->assertOk()
        ->assertSee('Cam nang xay dung')
        ->assertSee('Bai moi nhat')
        ->assertSee('Bai moi thu hai')
        ->assertDontSee('Bai cu thu ba')
        ->assertDontSee($emptyCategory->ten_danh_muc)
        ->assertDontSee($deletedCategory->ten_danh_muc)
        ->assertDontSee('Tin tức mới nhất')
        ->assertDontSee('Tất cả');
});

test('news category page paginates only articles in selected category', function () {
    $category = DanhMucTinTuc::query()->create([
        'ten_danh_muc' => 'Cong trinh du an',
        'is_delete' => false,
    ]);
    $otherCategory = DanhMucTinTuc::query()->create([
        'ten_danh_muc' => 'Cam nang',
        'is_delete' => false,
    ]);

    createNewsArticle($category, [
        'tieu_de' => 'Bai trong category',
        'slug' => 'bai-trong-category',
    ]);
    createNewsArticle($otherCategory, [
        'tieu_de' => 'Bai category khac',
        'slug' => 'bai-category-khac',
    ]);

    $response = $this->get(route('client.news.index', ['category' => $category->danh_muc_tin_tuc_id]));

    $response
        ->assertOk()
        ->assertSee('Cong trinh du an')
        ->assertSee('Bai trong category')
        ->assertDontSee('Bai category khac');
});

test('deleted news category returns 404', function () {
    $category = DanhMucTinTuc::query()->create([
        'ten_danh_muc' => 'Hidden category',
        'is_delete' => true,
    ]);

    $this->get(route('client.news.index', ['category' => $category->danh_muc_tin_tuc_id]))
        ->assertNotFound();
});

test('visited article appears in recent article history without duplicates', function () {
    $category = DanhMucTinTuc::query()->create([
        'ten_danh_muc' => 'Tin tuc',
        'is_delete' => false,
    ]);
    $article = createNewsArticle($category, [
        'tieu_de' => 'Bai da xem',
        'slug' => 'bai-da-xem',
    ]);

    $this->get(route('client.news.detail', $article->slug))->assertOk();
    $this->get(route('client.news.detail', $article->slug))->assertOk();

    $this->assertSame([$article->tin_tuc_id], session('th_recent_news'));

    $this->get(route('client.news.index'))
        ->assertOk()
        ->assertSee('Bài viết đã xem liên quan')
        ->assertSee('Bai da xem');
});

test('visited product appears in recent product history without duplicates', function () {
    $product = NgoiAmDuongCt::query()->create([
        'code' => 'NAD-001',
        'name' => 'Ngoi am duong test',
        'images' => ['assets/images/ngoi-01.jpg'],
        'price' => 862000,
        'des' => ['Mo ta'],
        'is_delete' => false,
    ]);

    $this->get(route('client.products.ngoi-am-duong.detail', $product->ngoi_am_duong_ct_id))->assertOk();
    $this->get(route('client.products.ngoi-am-duong.detail', $product->ngoi_am_duong_ct_id))->assertOk();

    $this->assertSame([
        ['type' => 'ngoi_am_duong_ct', 'id' => $product->ngoi_am_duong_ct_id],
    ], session('th_recent_products'));

    $this->get(route('client.news.index'))
        ->assertOk()
        ->assertSee('Đã xem gần đây')
        ->assertSee('Ngoi am duong test');
});
