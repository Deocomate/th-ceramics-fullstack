@php
    $images =
        is_array($product->images) && count($product->images) > 0
            ? $product->images
            : ['assets/images/gach-bat-detail-1.png'];
    $activeVariants = $product->phanLoais->where('is_delete', 0);
    $firstVariant = $activeVariants->first();
    $contactHotline = data_get($globalContact ?? null, 'hotline', '0966 55 8808');
    $zaloLink = data_get($globalContact ?? null, 'zalo_link', 'https://zalo.me/0966558808');

    $jsonLdImages = array_map(function ($img) {
        return str_contains($img, 'assets/') ? asset($img) : asset('storage/' . $img);
    }, $images);
    $jsonLdDesc = \Illuminate\Support\Str::limit(strip_tags(implode(', ', $product->des ?? [])), 300);

    // Lấy danh sách sản phẩm liên quan cho section "Có thể bạn quan tâm"
    $relatedProducts = \App\Models\LanCanGomSuCt::where('is_delete', 0)
        ->where('lan_can_gom_su_ct_id', '!=', $product->lan_can_gom_su_ct_id)
        ->with(['phanLoais' => fn ($q) => $q->where('is_delete', 0)->orderBy('price')])
        ->inRandomOrder()
        ->take(6)
        ->get();
@endphp

<x-client.layouts.main title="{{ $product->name }} - Lan Can Gốm Sứ | Gốm Sứ Thanh Hải" data-page="products"
    main-class="flex-grow bg-background-secondary pb-14 md:pb-20">
    @push('head')
        <meta name="description" content="{{ $jsonLdDesc }}">
        <script type="application/ld+json">
        {!! json_encode([
            '@context' => 'https://schema.org/',
            '@type' => 'Product',
            'name' => $product->name,
            'image' => $jsonLdImages,
            'description' => $jsonLdDesc,
            'sku' => $firstVariant ? $firstVariant->code : '',
            'brand' => ['@type' => 'Brand', 'name' => 'Gốm Sứ Thanh Hải'],
            'offers' => [
                '@type' => 'Offer',
                'url' => route('client.products.lan-can-gom-su.detail', $product->lan_can_gom_su_ct_id),
                'priceCurrency' => 'VND',
                'price' => (string) ($activeVariants->min('price') ?? '0'),
                'availability' => 'https://schema.org/InStock',
                'seller' => ['@type' => 'Organization', 'name' => 'Gốm Sứ Thanh Hải'],
            ],
        ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
        </script>
    @endpush

    <!-- Top Banner for Detail -->
    <section class="relative w-full hidden md:block">
        <div
            class="relative w-full aspect-[4/3] md:aspect-[8/6] lg:aspect-auto h-full lg:[clip-path:inset(40px_0_0_0)] lg:-mt-[40px]">
            <img src="{{ asset('assets/images/pk-banner.png') }}" alt="Lan Can Gốm Sứ" class="w-full h-full object-cover">
            <div class="absolute inset-0 flex flex-col items-center pt-[5%] md:pt-[5%] lg:pt-[5%] aos-init aos-animate"
                data-aos="fade-up" data-aos-delay="100">
                <div class="text-center text-white px-4 w-[85%] max-w-[1320px] mx-auto">
                    <h1
                        class="font-sans text-[26px] md:text-4xl lg:text-[44px] font-bold uppercase mb-2 md:mb-6 drop-shadow-md">
                        LAN CAN GỐM SỨ
                    </h1>
                    <p
                        class="font-italianno text-xl md:text-[34px] lg:text-[48px] font-light leading-none tracking-wide drop-shadow-sm text-white/95">
                        Tôn lên vẻ đẹp truyền thống, vững chãi cùng thời gian
                    </p>
                    <p
                        class="font-italianno text-xl md:text-[34px] lg:text-[48px] font-light leading-none tracking-wide drop-shadow-sm text-white/95">
                        Sản phẩm Thanh Hải: Điểm nhấn tâm linh, hoàn thiện dáng hình kiến trúc Việt
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Sub Breadcrumb -->
    <div class="hidden md:block w-[85%] max-w-[1320px] mx-auto py-8">
        <p class="font-bold text-primary/60 uppercase text-xs md:text-base">
            <a href="{{ route('client.home') }}" class="hover:text-secondary transition-colors">Trang chủ</a>
            <span class="mx-1">/</span>
            <a href="{{ route('client.products.lan-can-gom-su.index') }}"
                class="hover:text-secondary transition-colors">Sản phẩm</a>
            <span class="mx-1">/</span>
            <span class="text-primary font-semibold border-primary uppercase">{{ $product->name }}</span>
        </p>
        <hr class="border-t border-black/10 mt-4 w-full">
    </div>

    <!-- Product Detail Container -->
    <section
        class="w-full md:w-[85%] max-w-[1320px] mx-auto grid grid-cols-1 lg:grid-cols-5 md:gap-4 lg:gap-6 xl:gap-8 pb-8 md:pb-10 lg:pb-24 pt-0 md:pt-4">

        <!-- Left: Images -->
        <x-client.shared.product-image-swiper
            :images="$images"
            thumb-bg="bg-[#5C2321]"
            thumb-img-class="w-full h-full object-cover object-center mix-blend-screen opacity-90" />

        <!-- Right: Info -->
        <div class="flex flex-col lg:col-span-2 w-[85%] md:w-full mx-auto md:mt-0 pt-[18px] md:pt-0">

            <!-- SKU -->
            <div
                class="flex items-center gap-2 md:gap-4 mb-3 md:mb-8 text-[12px] md:text-[16px] order-2 md:order-1 mt-1 md:mt-0">
                <span class="text-[#656663] md:text-primary font-light md:font-normal">Mã SP:</span>
                <span id="dynamic-sku"
                    class="text-[#656663] md:text-primary font-semibold">{{ $firstVariant ? $firstVariant->code : 'Đang cập nhật' }}</span>
            </div>

            <!-- Title -->
            <h1
                class="text-[20px] text-[#C76E00] md:text-2xl lg:text-[32px] font-semibold md:text-secondary uppercase leading-[30px] md:!leading-normal mb-0 md:mb-14 pb-0 md:pb-1 tracking-tight order-1 md:order-2">
                {{ $product->name }}
            </h1>

            <!-- Price -->
            <p id="dynamic-price"
                class="text-[16px] text-black md:text-2xl md:text-[32px] font-semibold md:text-primary mb-4 md:mb-16 leading-[20px] md:leading-normal order-3 mt-0.5 md:mt-0">
                {{ $firstVariant && $firstVariant->price > 0 ? number_format($firstVariant->price, 0, ',', '.') . ' đ/m2' : 'Liên hệ' }}
            </p>

            <!-- Separator -->
            <hr class="border-t border-black/10 md:border-black/10 mb-4 md:mb-8 w-full order-4 hidden md:block">
            <hr class="border-t border-black/10 w-full order-4 md:hidden mb-4">

            <!-- Details List -->
            <ul
                class="list-disc pl-5 space-y-0 md:space-y-4 mb-[15px] md:mb-16 text-[#2E2F2A] md:text-primary font-medium text-[14px] md:text-lg lg:text-xl leading-[24px] md:leading-relaxed order-5">
                @if (!empty($product->des))
                    @foreach ($product->des as $line)
                        <li>{{ $line }}</li>
                    @endforeach
                @else
                    <li>Vẻ đẹp truyền thống vượt thời gian</li>
                    <li>Sản phẩm thủ công chất lượng cao</li>
                    <li>Phù hợp cho công trình kiến trúc cổ</li>
                @endif
            </ul>

            <!-- Variants Section -->
            @if ($activeVariants->isNotEmpty())
                <div class="flex flex-wrap gap-3.5 md:gap-4 mb-6 md:mb-16 w-full xl:w-[95%] order-[6] md:order-[none]">
                    @foreach ($activeVariants as $variant)
                        <button
                            class="variant-btn flex-1 md:w-auto md:min-w-[200px] border {{ $loop->first ? 'border-black/50 bg-black/5' : 'border-black/20' }} text-primary uppercase text-[10px] md:text-[13px] font-medium md:py-3 py-2 px-1 text-center hover:border-black/50 hover:bg-black/5 transition-all outline-none flex items-center justify-center leading-tight"
                            data-variant-id="{{ $variant->phan_loai_lan_can_gom_su_ct_id }}"
                            data-sku="{{ $variant->code }}" data-price="{{ $variant->price }}"
                            data-price-formatted="{{ $variant->price > 0 ? number_format($variant->price, 0, ',', '.') . ' đ/m2' : 'Liên hệ' }}">
                            {{ $variant->name }}
                        </button>
                    @endforeach
                </div>
            @endif

            <!-- Actions -->
            <div
                class="flex flex-col md:flex-row items-start md:items-center gap-6 mb-[15px] md:mb-16 order-[7] md:order-[none] w-full">
                <!-- Quantity -->
                <div class="flex items-center gap-[16px] md:gap-4 text-[#2E2F2A] md:text-primary pl-0.5 md:pl-0">
                    <button type="button" onclick="updateQty(-1)"
                        class="w-6 h-6 flex items-center justify-center text-[20px] md:text-xl focus:outline-none md:hover:text-secondary transition-colors">-</button>
                    <div id="quantity-display"
                        class="w-12 h-12 flex items-center justify-center rounded-full text-[16px] md:text-base font-normal shadow-[0px_1px_2px_rgba(0,0,0,0.05)] md:shadow-sm outline outline-1 outline-black/40 outline-offset-[-1px] md:outline-none md:border md:border-black/40 bg-transparent">
                        1
                    </div>
                    <button type="button" onclick="updateQty(1)"
                        class="w-6 h-6 flex items-center justify-center text-[20px] md:text-xl focus:outline-none md:hover:text-secondary transition-colors">+</button>
                </div>

                <!-- Add to cart Form -->
                <form id="add-to-cart-form" method="POST" action="{{ route('client.cart.add') }}"
                    class="w-full md:w-auto m-0 p-0">
                    @csrf
                    <input type="hidden" name="product_type" value="lan_can_gom_su_ct">
                    <input type="hidden" name="product_id" value="{{ $product->lan_can_gom_su_ct_id }}">
                    <input type="hidden" name="variant_id" id="selected-variant-id"
                        value="{{ $firstVariant ? $firstVariant->phan_loai_lan_can_gom_su_ct_id : '' }}">
                    <input type="hidden" name="qty" id="cart-quantity" value="1">

                    <button type="button" onclick="submitCart()"
                        class="w-full md:w-auto bg-[#C16A00] hover:bg-secondary text-[#EFE4DE] px-8 py-4 font-semibold md:transition-colors md:shadow-md rounded-[2px] flex items-center justify-center text-[14px] md:text-sm tracking-[0.28px] md:tracking-normal md:ml-4">
                        THÊM VÀO GIỎ HÀNG
                    </button>
                </form>
            </div>

            <!-- Contacts -->
            <div class="hidden md:flex flex-col gap-5 mt-2 order-[8] md:order-[none]">
                <div class="flex items-center gap-5">
                    <div
                        class="w-[66px] h-[66px] rounded-full bg-[#EBDDD0] flex items-center justify-center text-secondary flex-shrink-0 shadow-sm border border-secondary/10">
                        <img src="{{ asset('assets/images/phone-call.svg') }}" alt="Phone Call" class="w-6 h-6">
                    </div>
                    <div>
                        <p class="text-base text-secondary">Đặt hàng ngay</p>
                        <p class="text-secondary font-semibold text-lg md:text-xl">
                            Hotline: {{ $contactHotline }}
                        </p>
                    </div>
                </div>
                <a href="{{ $zaloLink }}" target="_blank"
                    class="flex items-center gap-5 hover:opacity-80 transition-opacity">
                    <div
                        class="w-[66px] h-[66px] rounded-full bg-[#EBDDD0] flex items-center justify-center text-secondary flex-shrink-0 shadow-sm border border-secondary/10">
                        <img src="{{ asset('assets/images/zalo.png') }}" alt="Zalo"
                            class="w-[80%] h-[80%] object-cover">
                    </div>
                    <div>
                        <p class="text-secondary font-semibold text-lg md:text-xl">
                            Chat với chúng tôi
                        </p>
                    </div>
                </a>
            </div>
        </div>
    </section>

    <!-- Product Description Section -->
    <section class="w-[85%] max-w-[1320px] mx-auto pb-16 lg:pb-24 pt-0 md:pt-4">
        <!-- Section Title -->
        <div class="text-center mb-8 md:mb-16 aos-init aos-animate" data-aos="fade-up">
            <h3 class="text-[20px] md:text-3xl font-semibold text-secondary uppercase drop-shadow-sm">
                Mô tả sản phẩm
            </h3>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-10 lg:gap-16 items-center">
            <!-- Left Image -->
            <div class="flex items-center justify-center aos-init aos-animate" data-aos="fade-right">
                <img src="{{ $product->size_image ? asset('storage/' . $product->size_image) : asset('assets/images/gach-bat-size-1.png') }}"
                    alt="Mô tả kích thước" class="w-full max-w-[550px] object-contain">
            </div>
            <!-- Right List -->
            <div class="flex flex-col justify-center aos-init aos-animate" data-aos="fade-left">
                <ul
                    class="list-disc pl-5 md:pl-16 space-y-3 md:space-y-4 text-primary font-medium text-[15px] md:text-[20px] leading-relaxed">
                    @if (!empty($product->size_des))
                        @foreach ($product->size_des as $desc)
                            <li>{{ $desc }}</li>
                        @endforeach
                    @else
                        <li>Đang cập nhật thông tin kích thước kỹ thuật.</li>
                    @endif
                </ul>
            </div>
        </div>
    </section>

    <!-- DẤU ẤN TRÊN NHỮNG CÔNG TRÌNH SECTION -->
    <x-client.shared.works-simple />

    <!-- Giá Trị Vượt Trội -->
    <x-client.shared.outstanding-value />

    <!-- Hành Trình Chế Tác -->
    <x-client.shared.journey-video :video="null" />

    <!-- CÓ THỂ BẠN QUAN TÂM -->
    <x-client.shared.recommendations :related-products="$relatedProducts" route-name="client.products.lan-can-gom-su.detail"
        pk-field="lan_can_gom_su_ct_id" product-type="lan_can_gom_su_ct" :compare-table="true" />

    <!-- FAQ Section -->
    <x-client.shared.faq-cta-banner />

    @push('scripts')
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                // Khởi tạo Swiper Image Gallery
                var thumbSwiper = new Swiper(".product-thumb-swiper", {
                    spaceBetween: 20,
                    slidesPerView: 'auto',
                    freeMode: true,
                    watchSlidesProgress: true,
                });
                new Swiper(".product-main-swiper", {
                    slidesPerView: 1,
                    spaceBetween: 0,
                    pagination: {
                        el: ".product-main-pagination",
                        clickable: true,
                    },
                    thumbs: {
                        swiper: thumbSwiper,
                    },
                });

                // Xử lý nút Biến thể / Phân loại
                const variantBtns = document.querySelectorAll('.variant-btn');
                const skuEl = document.getElementById('dynamic-sku');
                const priceEl = document.getElementById('dynamic-price');
                const variantIdInput = document.getElementById('selected-variant-id');

                variantBtns.forEach(btn => {
                    btn.addEventListener('click', () => {
                        variantBtns.forEach(b => {
                            b.classList.remove('border-black/50', 'bg-black/5');
                            b.classList.add('border-black/20');
                        });
                        btn.classList.remove('border-black/20');
                        btn.classList.add('border-black/50', 'bg-black/5');

                        skuEl.textContent = btn.dataset.sku;
                        priceEl.textContent = btn.dataset.priceFormatted;
                        variantIdInput.value = btn.dataset.variantId;
                    });
                });
            });

            // Xử lý Thay đổi Số lượng
            function updateQty(delta) {
                const display = document.getElementById('quantity-display');
                const input = document.getElementById('cart-quantity');
                let val = parseInt(input.value) + delta;
                if (val < 1) val = 1;
                display.textContent = val;
                input.value = val;
            }

            // Xử lý Thêm Vào Giỏ Hàng (AJAX POST)
            function submitCart() {
                const form = document.getElementById('add-to-cart-form');
                const formData = new FormData(form);
                const data = Object.fromEntries(formData.entries());

                fetch(form.action, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': data._token
                        },
                        body: JSON.stringify({
                            product_type: data.product_type,
                            product_id: parseInt(data.product_id),
                            variant_id: parseInt(data.variant_id) || null,
                            qty: parseInt(data.qty)
                        })
                    })
                    .then(res => res.json())
                    .then(resData => {
                        if (resData.status === 'success') {
                            alert('Đã thêm vào giỏ hàng!');
                            // Note: Nếu có header cart auto update, trigger custom event ở đây
                        } else {
                            alert(resData.message || 'Có lỗi xảy ra.');
                        }
                    })
                    .catch(() => alert('Lỗi kết nối. Vui lòng thử lại.'));
            }
        </script>
    @endpush
</x-client.layouts.main>
