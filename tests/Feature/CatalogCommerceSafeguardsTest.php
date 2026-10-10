<?php

use App\Domains\Catalog\Domain\ProductTypeRegistry;
use App\Domains\Catalog\Infrastructure\Models\ProductDisplayOption;
use App\Domains\Catalog\Infrastructure\ProductWriter;
use App\Domains\Commerce\Application\CouponService;
use App\Domains\Commerce\Infrastructure\Coupons\EloquentCouponRepository;
use App\Domains\Commerce\Infrastructure\Models\Coupon;
use App\Domains\Commerce\Infrastructure\Models\Order;
use App\Domains\Identity\Infrastructure\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

test('admin can delete a ngoi am duong colour option and its image', function () {
    Storage::fake('public');
    Storage::disk('public')->put('ngoi_am_duong_ct/colors/red.webp', 'x');
    $option = ProductDisplayOption::create([
        'type_key' => 'ngoi_am_duong_ct',
        'legacy_id' => 7,
        'name' => 'Đỏ',
        'image' => 'ngoi_am_duong_ct/colors/red.webp',
    ]);

    $this->actingAs(User::factory()->create())
        ->delete(route('admin.mau-sac-ngoi-am-duong-ct.destroy', $option->public_id))
        ->assertRedirect();

    expect(ProductDisplayOption::query()->whereKey($option->id)->exists())->toBeFalse();
    Storage::disk('public')->assertMissing('ngoi_am_duong_ct/colors/red.webp');
});

test('gallery delete leaves files of other products untouched', function () {
    Storage::fake('public');
    Storage::disk('public')->put('ngoi_am_duong_ct/images/other.png', 'x');
    $writer = app(ProductWriter::class);
    $product = $writer->create('ngoi_am_duong_ct', ['name' => 'Ngói A', 'code' => 'NAD-OWN-A', 'images' => ['ngoi_am_duong_ct/images/own.png']]);
    $other = $writer->create('ngoi_am_duong_ct', ['name' => 'Ngói B', 'code' => 'NAD-OWN-B', 'images' => ['ngoi_am_duong_ct/images/other.png']]);

    $this->actingAs(User::factory()->create())
        ->deleteJson(route('admin.ngoi-am-duong-ct.image.destroy', $product->public_id), [
            'image_path' => 'ngoi_am_duong_ct/images/other.png',
        ])->assertSuccessful();

    Storage::disk('public')->assertExists('ngoi_am_duong_ct/images/other.png');
    expect($other->fresh()->images)->toBe(['ngoi_am_duong_ct/images/other.png']);
});

test('a rejected product update keeps the existing size image', function () {
    Storage::fake('public');
    Storage::disk('public')->put('ngoi_am_duong_ct/sizes/old.webp', 'x');
    $writer = app(ProductWriter::class);
    $writer->create('ngoi_am_duong_ct', ['name' => 'Ngói có mã', 'code' => 'NAD-TAKEN']);
    $product = $writer->create('ngoi_am_duong_ct', [
        'name' => 'Ngói đang sửa',
        'code' => 'NAD-EDIT',
        'size_image' => 'ngoi_am_duong_ct/sizes/old.webp',
    ]);

    $this->actingAs(User::factory()->create())
        ->put(route('admin.ngoi-am-duong-ct.update', $product->public_id), [
            'name' => 'Ngói đang sửa',
            'code' => 'NAD-TAKEN',
            'size_image' => UploadedFile::fake()->image('new.png', 10, 10),
        ])->assertSessionHasErrors('code');

    Storage::disk('public')->assertExists('ngoi_am_duong_ct/sizes/old.webp');
    expect($product->fresh()->size_image)->toBe('ngoi_am_duong_ct/sizes/old.webp')
        ->and(Storage::disk('public')->allFiles('ngoi_am_duong_ct/sizes'))->toBe(['ngoi_am_duong_ct/sizes/old.webp']);
});

test('updating a cart row that no longer exists returns a handled error', function () {
    $this->postJson(route('client.cart.update'), ['row_id' => 'missing-row', 'qty' => 2])
        ->assertNotFound()
        ->assertJsonPath('status', 'error');
});

test('checkout sends the customer back to the cart when a price changed', function () {
    Mail::fake();
    $user = User::factory()->create(['email_verified_at' => now()]);
    $product = app(ProductWriter::class)->create('linh_vat_phong_thuy_ct', [
        'name' => 'Linh vật đổi giá',
        'code' => 'LV-PRICE-001',
        'price' => 100000,
    ]);

    $this->actingAs($user)->postJson(route('client.cart.add'), [
        'product_type' => 'linh_vat_phong_thuy_ct',
        'product_id' => $product->public_id,
        'qty' => 1,
    ])->assertSuccessful();

    app(ProductWriter::class)->update($product, ['price' => 150000]);

    $checkout = [
        'customer_name' => 'Nguyen Van A',
        'phone' => '0909123456',
        'address' => 'Ha Noi',
        'payment_method' => 'cod',
    ];

    $this->post(route('client.cart.checkout.process'), $checkout)
        ->assertRedirect(route('client.cart.index'))
        ->assertSessionHas('error');
    expect(Order::query()->count())->toBe(0);

    $this->post(route('client.cart.checkout.process'), $checkout)->assertRedirect(route('client.home'));
    expect(Order::query()->firstOrFail()->total_amount)->toBe(150000);
});

test('checkout drops a product that was hidden after it was added to the cart', function () {
    $user = User::factory()->create(['email_verified_at' => now()]);
    $product = app(ProductWriter::class)->create('linh_vat_phong_thuy_ct', [
        'name' => 'Linh vật bị ẩn',
        'code' => 'LV-HIDE-001',
        'price' => 100000,
    ]);

    $this->actingAs($user)->postJson(route('client.cart.add'), [
        'product_type' => 'linh_vat_phong_thuy_ct',
        'product_id' => $product->public_id,
        'qty' => 1,
    ])->assertSuccessful();

    app(ProductWriter::class)->softDelete($product);

    $this->post(route('client.cart.checkout.process'), [
        'customer_name' => 'Nguyen Van A',
        'phone' => '0909123456',
        'address' => 'Ha Noi',
        'payment_method' => 'cod',
    ])->assertRedirect(route('client.cart.index'));

    expect(Order::query()->count())->toBe(0);
    $this->getJson(route('client.cart.mini'))->assertJsonPath('cart_count', 0);
});

test('a variant-required product without variants can be added with its default price', function () {
    $product = app(ProductWriter::class)->create('lan_can_gom_su_ct', [
        'name' => 'Lan can chưa có phân loại',
        'code' => 'LC-DEFAULT-001',
        'price' => 320000,
    ]);

    $this->postJson(route('client.cart.add'), [
        'product_type' => 'lan_can_gom_su_ct',
        'product_id' => $product->public_id,
        'qty' => 1,
    ])->assertSuccessful()->assertJsonPath('cart_total', 320000);
});

test('coupons stored with section keys are converted to cart product types', function () {
    $coupon = Coupon::query()->create([
        'title' => 'Lan can',
        'code' => 'LANCAN',
        'discount_type' => 'fixed',
        'discount_value' => 5000,
        'applicable_product_types' => ['lan_can_gom_xu', 'den_gom_su', 'den_vuon_gom_su_ct', 'gach_trang_tri_ct'],
        'start_date' => now()->subDay(),
        'is_active' => true,
        'is_delete' => false,
    ]);

    (require database_path('migrations/2026_10_10_000000_normalize_coupon_product_type_keys.php'))->up();

    expect($coupon->fresh()->applicable_product_types)
        ->toBe(['lan_can_gom_su_ct', 'den_vuon_gom_su_ct', 'gach_trang_tri_ct']);
});

test('the coupon form offers exactly the registered catalog product types', function () {
    expect(array_keys(app(CouponService::class)->productTypes()))->toBe(ProductTypeRegistry::keys());

    $this->actingAs(User::factory()->create())
        ->post(route('admin.coupons.store'), [
            'title' => 'Sai loại',
            'code' => 'BADTYPE',
            'discount_type' => 'fixed',
            'discount_value' => 1000,
            'applicable_product_types' => ['lan_can_gom_xu'],
            'start_date' => now()->toDateString(),
        ])->assertSessionHasErrors('applicable_product_types.0');
});

test('display codes follow the rules declared for each product type', function () {
    $writer = app(ProductWriter::class);
    $balustrade = $writer->create('lan_can_gom_su_ct', ['name' => 'Lan can', 'code' => 'LC-BASE', 'price' => 1000]);
    $lamp = $writer->create('den_vuon_gom_su_ct', ['name' => 'Đèn', 'code' => 'DV-BASE', 'price' => 1000, 'category_type' => 'den_vuon']);
    $ridge = $writer->create('phu_kien_ngoi_ct', ['name' => 'Bờ nóc', 'category_type' => 'chu_van']);
    $tile = $writer->create('gach_trang_tri_ct', ['name' => 'Gạch', 'code' => 'GTT-1', 'price' => 1000]);

    expect($balustrade->display_code)->toBe('LC-BASE')
        ->and($lamp->display_code)->toBe('DV-BASE')
        ->and($ridge->display_code)->toBe('PKN-CV'.$ridge->public_id)
        ->and($tile->display_code)->toBe('GTT-1')
        ->and($balustrade->display_price)->toBe('Giá: 1.000 đ/m²')
        ->and($lamp->display_price)->toBe('Từ 1.000 đ')
        ->and($ridge->display_price)->toBe('Giá: Liên hệ')
        ->and($tile->display_price)->toBe('1.000 đ');
});

test('a coupon cannot be claimed past its usage limit', function () {
    Coupon::query()->create([
        'title' => 'Một lượt',
        'code' => 'ONCE',
        'discount_type' => 'fixed',
        'discount_value' => 1000,
        'usage_limit' => 1,
        'used_count' => 0,
        'start_date' => now()->subDay(),
        'is_active' => true,
        'is_delete' => false,
    ]);
    $coupons = app(EloquentCouponRepository::class);

    $coupons->incrementUsage('ONCE');

    expect(fn () => $coupons->incrementUsage('ONCE'))->toThrow(ValidationException::class)
        ->and(Coupon::query()->firstOrFail()->used_count)->toBe(1);
});
