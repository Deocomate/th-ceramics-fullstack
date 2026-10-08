<?php

use App\Domains\Catalog\Models\Product;
use App\Domains\Catalog\PublicIdAllocator;
use App\Domains\Identity\Models\User;
use App\Models\DenGomSu;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    DenGomSu::query()->create([
        'thumbnail_main' => 'assets/images/den-gom-banner.png',
        'video' => null,
        'image1' => 'assets/images/den-gom-01.png',
        'image2' => 'assets/images/den-gom-02.png',
        'title2' => 'ĐÈN GỐM',
        'image3' => 'assets/images/den-gom-bg.png',
        'title3' => 'ĐÈN SỨ',
        'image4' => 'assets/images/den-gom-01.png',
    ]);
});

test('den gom pages can read the canonical product catalog', function () {
    $product = createDenGomSuProduct(['name' => 'Đèn từ schema mới'], [
        ['code' => 'UNIFIED-DEN-001', 'price' => 280000],
    ]);

    $this->get(route('client.products.den-gom-su.index'))
        ->assertOk()->assertSee('Đèn từ schema mới');
    $this->get(route('client.products.den-gom-su.detail', $product->public_id))
        ->assertOk()->assertSee('UNIFIED-DEN-001');
});

function createDenGomSuProduct(array $overrides = [], array $variants = []): Product
{
    $product = Product::query()->create(array_merge([
        'type_key' => 'den_vuon_gom_su_ct',
        'name' => 'Đèn test',
        'category_type' => 'den_gom',
        'color' => 'Tự chọn',
        'des' => ['Mô tả sản phẩm'],
        'size' => 'H500 x D200 mm',
        'size_image' => null,
        'size_des' => ['Kích thước test'],
        'is_delete' => 0,
        'priority' => 0,
    ], $overrides));

    $product->media()->create([
        'kind' => 'image',
        'path' => 'assets/images/den-gom-01.png',
        'sort_order' => 0,
        'is_cover' => true,
    ]);

    app(PublicIdAllocator::class)->product($product);

    foreach ($variants as $index => $variant) {
        $sku = $variant['code'] ?? ('DGS-'.$product->public_id.'-'.($index + 1));
        $v = $product->variants()->create([
            'name' => $product->name.' - Phân loại '.($index + 1),
            'sku' => $sku,
            'price' => $variant['price'] ?? (100000 + ($index * 10000)),
            'is_default' => false,
            'is_delete' => (bool) ($variant['is_delete'] ?? false),
        ]);
        app(PublicIdAllocator::class)->variant($v->setRelation('product', $product));
    }

    return $product->refresh();
}

function fakePngUpload(string $name): UploadedFile
{
    return UploadedFile::fake()->image($name, 1, 1);
}

test('client page renders separate dynamic grids for den gom and den su', function () {
    createDenGomSuProduct(['name' => 'Sản phẩm Đèn Gốm', 'category_type' => 'den_gom'], [
        ['code' => 'GOM-001', 'price' => 350000],
    ]);
    createDenGomSuProduct(['name' => 'Sản phẩm Đèn Sứ', 'category_type' => 'den_su'], [
        ['code' => 'SU-001', 'price' => 450000],
    ]);

    $this->get(route('client.products.den-gom-su.index'))
        ->assertOk()
        ->assertSee('Sản phẩm Đèn Gốm')
        ->assertSee('Sản phẩm Đèn Sứ')
        ->assertSee('GOM-001')
        ->assertSee('SU-001')
        ->assertSee('aria-label="Pagination"', false);
});

test('gom pagination does not change den su page', function () {
    foreach (range(1, 9) as $index) {
        createDenGomSuProduct(['name' => sprintf('Đèn Gốm %02d', $index), 'category_type' => 'den_gom'], [
            ['code' => sprintf('GOM-%02d', $index), 'price' => 300000 + $index],
        ]);
        createDenGomSuProduct(['name' => sprintf('Đèn Sứ %02d', $index), 'category_type' => 'den_su'], [
            ['code' => sprintf('SU-%02d', $index), 'price' => 400000 + $index],
        ]);
    }

    $this->get(route('client.products.den-gom-su.index', ['gom_page' => 2]))
        ->assertOk()
        ->assertSee('Đèn Gốm 01')
        ->assertSee('Đèn Sứ 09')
        ->assertDontSee('Đèn Sứ 01');
});

test('su pagination does not change den gom page', function () {
    foreach (range(1, 9) as $index) {
        createDenGomSuProduct(['name' => sprintf('Đèn Gốm %02d', $index), 'category_type' => 'den_gom'], [
            ['code' => sprintf('GOM-%02d', $index), 'price' => 300000 + $index],
        ]);
        createDenGomSuProduct(['name' => sprintf('Đèn Sứ %02d', $index), 'category_type' => 'den_su'], [
            ['code' => sprintf('SU-%02d', $index), 'price' => 400000 + $index],
        ]);
    }

    $this->get(route('client.products.den-gom-su.index', ['su_page' => 2]))
        ->assertOk()
        ->assertSee('Đèn Sứ 01')
        ->assertSee('Đèn Gốm 09')
        ->assertDontSee('Đèn Gốm 01');
});

test('client page sorts by min active variant price and shows contact without variants', function () {
    createDenGomSuProduct(['name' => 'Đèn Giá Cao', 'category_type' => 'den_gom'], [
        ['code' => 'HIGH-001', 'price' => 900000],
        ['code' => 'HIGH-002', 'price' => 950000],
    ]);
    createDenGomSuProduct(['name' => 'Đèn Giá Thấp', 'category_type' => 'den_gom'], [
        ['code' => 'LOW-001', 'price' => 200000],
    ]);
    createDenGomSuProduct(['name' => 'Đèn Chưa Có Giá', 'category_type' => 'den_su']);

    $this->get(route('client.products.den-gom-su.index', ['sort' => 'price_asc']))
        ->assertOk()
        ->assertSeeInOrder(['Đèn Giá Thấp', 'Đèn Giá Cao'])
        ->assertSee('Từ 200.000 đ')
        ->assertSee('Liên hệ');
});

test('detail page resolves active den vuon product and tracks history', function () {
    $product = createDenGomSuProduct(['name' => 'Đèn Chi Tiết', 'category_type' => 'den_gom'], [
        ['code' => 'DETAIL-LOW', 'price' => 123000],
        ['code' => 'DETAIL-HIGH', 'price' => 456000],
    ]);

    $this->get(route('client.products.den-gom-su.detail', $product->public_id))
        ->assertOk()
        ->assertSee('Đèn Chi Tiết')
        ->assertSee('DETAIL-LOW')
        ->assertSee('Từ 123.000 đ');

    expect(session('th_recent_products.0.type'))->toBe('den_vuon_gom_su_ct');
    expect(session('th_recent_products.0.id'))->toBe($product->public_id);
});

test('hidden den vuon product returns not found', function () {
    $product = createDenGomSuProduct(['is_delete' => 1], [
        ['code' => 'HIDDEN-001', 'price' => 123000],
    ]);

    $this->get(route('client.products.den-gom-su.detail', $product->public_id))
        ->assertNotFound();
});

test('cart accepts only active variants belonging to den vuon product', function () {
    $product = createDenGomSuProduct(['name' => 'Đèn Có Giỏ'], [
        ['code' => 'CART-001', 'price' => 222000],
        ['code' => 'CART-HIDDEN', 'price' => 333000, 'is_delete' => 1],
    ]);
    $activeVariant = $product->variants()->where('sku', 'CART-001')->firstOrFail();
    $hiddenVariant = $product->variants()->where('sku', 'CART-HIDDEN')->firstOrFail();

    $this->postJson(route('client.cart.add'), [
        'product_type' => 'den_vuon_gom_su_ct',
        'product_id' => $product->public_id,
        'variant_id' => $activeVariant->public_id,
        'qty' => 2,
    ])->assertSuccessful()
        ->assertJsonPath('status', 'success');

    $this->postJson(route('client.cart.add'), [
        'product_type' => 'den_vuon_gom_su_ct',
        'product_id' => $product->public_id,
        'variant_id' => $hiddenVariant->public_id,
        'qty' => 1,
    ])->assertUnprocessable()
        ->assertJsonPath('status', 'error');
});

test('admin can store update and preview den vuon category type', function () {
    Storage::fake('public');
    $this->actingAs(User::factory()->create());

    $this->post(route('admin.den-vuon-gom-su-ct.store'), [
        'name' => 'Admin Đèn Sứ',
        'category_type' => 'den_su',
        'size' => 'H300',
        'des' => ['Mô tả admin'],
        'size_des' => ['Size admin'],
        'images' => [fakePngUpload('den-su.png')],
    ])->assertRedirect(route('admin.den-vuon-gom-su-ct.index'));

    $product = Product::query()->where('type_key', 'den_vuon_gom_su_ct')->where('name', 'Admin Đèn Sứ')->firstOrFail();
    expect($product->category_type)->toBe('den_su');

    $this->put(route('admin.den-vuon-gom-su-ct.update', $product->public_id), [
        'name' => 'Admin Đèn Gốm',
        'category_type' => 'den_gom',
        'size' => 'H400',
        'des' => ['Mô tả mới'],
        'size_des' => ['Size mới'],
    ])->assertRedirect();

    expect($product->fresh()->category_type)->toBe('den_gom');

    $this->get(route('admin.den-vuon-gom-su-ct.edit', $product->public_id))
        ->assertOk()
        ->assertSee(route('client.products.den-gom-su.detail', $product->public_id));
});
