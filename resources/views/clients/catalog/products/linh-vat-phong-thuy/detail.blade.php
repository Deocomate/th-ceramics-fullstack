<x-client.layouts.main title="Chi tiết {{ $product->name }}" data-page="products"
    main-class="flex-grow bg-background-secondary pb-14 md:pb-20" :hide-newsletter="true">


    <!-- Sub Breadcrumb -->
    <div class="hidden md:block w-[85%] max-w-[1320px] mx-auto py-8">
        <p class="font-bold text-primary/60 uppercase text-xs md:text-base">
            <a href="{{ route('client.home') }}" class="hover:text-secondary transition-colors">Trang chủ</a>
            <span class="mx-1">/</span>
            <a href="{{ route('client.products.linh-vat-phong-thuy.index') }}"
                class="hover:text-secondary transition-colors">Sản phẩm</a>
            <span class="mx-1">/</span>
            <span class="text-primary font-semibold border-primary uppercase">{{ $product->name }}</span>
        </p>
        <hr class="border-t border-black/10 mt-4 w-full">
    </div>

    <!-- Product Detail Section -->
    <section
        class="w-full md:w-[85%] max-w-[1320px] mx-auto grid grid-cols-1 lg:grid-cols-5 md:gap-4 lg:gap-6 xl:gap-8 pb-8 md:pb-10 lg:pb-24 pt-0 md:pt-4">
        <!-- Left: Images Gallery -->
        <x-client.catalog.shared.product-image-swiper
            :images="$product->images ?? []"
            thumb-bg="bg-gray-100 border border-transparent overflow-hidden"
            thumb-img-class="w-full h-full object-cover"
            main-bg="bg-gray-50 flex items-center justify-center"
            main-img-class="object-contain" />

        <!-- Right: Info -->
        <div class="flex flex-col lg:col-span-2 w-[85%] md:w-full mx-auto md:mt-0 pt-[18px] md:pt-0">
            <!-- SKU -->
            <div
                class="flex items-center gap-2 md:gap-4 mb-3 md:mb-8 text-[12px] md:text-[16px] order-2 md:order-1 mt-1 md:mt-0">
                <span class="text-[#656663] md:text-primary font-light md:font-normal">Mã SP:</span>
                <span class="text-[#656663] md:text-primary font-semibold">{{ $product->code }}</span>
            </div>

            <!-- Title -->
            <h1
                class="text-[20px] text-[#C76E00] md:text-2xl lg:text-[32px] font-semibold md:text-secondary uppercase leading-[30px] md:!leading-normal mb-0 md:mb-14 pb-0 md:pb-1 tracking-tight order-1 md:order-2">
                {{ $product->name }}
            </h1>

            <!-- Price -->
            <p
                class="text-[16px] text-black md:text-2xl md:text-[32px] font-semibold md:text-primary mb-4 md:mb-16 leading-[20px] md:leading-normal order-3 mt-0.5 md:mt-0">
                {{ $product->price > 0 ? number_format($product->price, 0, ',', '.') . ' đ/chiếc' : 'Liên hệ' }}
            </p>

            <!-- Separator -->
            <hr class="border-t border-black/10 md:border-black/10 mb-4 md:mb-8 w-full order-4 hidden md:block">
            <hr class="border-t border-black/10 w-full order-4 md:hidden mb-4">

            <!-- Details List -->
            @if (!empty($product->des) && is_array($product->des))
                <ul
                    class="list-disc pl-5 space-y-0 md:space-y-4 mb-[15px] md:mb-16 text-[#2E2F2A] md:text-primary font-medium text-[14px] md:text-lg lg:text-xl leading-[24px] md:leading-relaxed order-5">
                    @foreach ($product->des as $descItem)
                        <li>{{ $descItem }}</li>
                    @endforeach
                </ul>
            @endif

            <!-- Actions -->
            <div
                class="flex flex-col md:flex-row items-start md:items-center gap-6 mb-[15px] md:mb-16 order-[7] md:order-[none] w-full">
                <!-- Add to cart Form -->
                <form action="{{ route('client.cart.add') }}" method="POST"
                    class="w-full flex flex-col md:flex-row gap-6 items-start md:items-center">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->linh_vat_phong_thuy_ct_id }}">
                    <input type="hidden" name="product_type" value="linh_vat_phong_thuy_ct">

                    <div class="flex items-center gap-[16px] md:gap-4 text-[#2E2F2A] md:text-primary pl-0.5 md:pl-0">
                        <button type="button" onclick="const i=this.nextElementSibling;i.stepDown();i.dispatchEvent(new Event('input'))"
                            class="w-6 h-6 flex items-center justify-center text-[20px] md:text-xl focus:outline-none md:hover:text-secondary transition-colors">-</button>
                        <input type="number" name="quantity" value="1" min="1"
                            oninput="(function(el){const d=Math.max(1,String(el.value||'').length);el.style.width=(Math.max(3,d)+2.5)+'ch';})(this)"
                            class="h-12 min-w-12 px-2.5 text-center rounded-[2px] text-[16px] md:text-base font-normal shadow-[0px_1px_2px_rgba(0,0,0,0.05)] md:shadow-sm outline outline-1 outline-black/40 outline-offset-[-1px] md:outline-none md:border md:border-black/40 bg-transparent [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none"
                            style="width: 5.5ch;">
                        <button type="button" onclick="const i=this.previousElementSibling;i.stepUp();i.dispatchEvent(new Event('input'))"
                            class="w-6 h-6 flex items-center justify-center text-[20px] md:text-xl focus:outline-none md:hover:text-secondary transition-colors">+</button>
                    </div>

                    <button type="submit"
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
                        <p class="text-secondary font-semibold text-lg md:text-xl">Hotline:
                            {{ $globalContact->hotline ?? '0966 55 8808' }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-5">
                    <a href="{{ $globalContact->zalo_link ?? 'https://zalo.me/0966558808' }}" target="_blank"
                        class="w-[66px] h-[66px] rounded-full bg-[#EBDDD0] flex items-center justify-center text-secondary flex-shrink-0 shadow-sm border border-secondary/10 hover:scale-105 transition-transform">
                        <img src="{{ asset('assets/images/zalo.png') }}" alt="Zalo"
                            class="w-[80%] h-[80%] object-cover">
                    </a>
                    <div>
                        <p class="text-secondary font-semibold text-lg md:text-xl">Chat với chúng tôi</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <x-client.catalog.shared.journey-video :video="$journeyVideo ?? null" />

    @php
        $galleryImages =
            is_array($product->images) && count($product->images) > 0
                ? $product->images
                : [
                    'assets/images/gach-co-work-1.webp',
                    'assets/images/gach-co-work-2.jpg',
                    'assets/images/trang-tri-slide-01.webp',
                ];
    @endphp
    <section class="w-full pb-8 md:pb-16 bg-background-secondary overflow-hidden" data-aos="fade-up">
        <div class="max-w-[1920px] mx-auto mt-1">
            <h2 class="text-[20px] md:text-3xl font-semibold text-secondary text-center uppercase mb-8 md:mb-20">
                DẤU ẤN TRÊN NHỮNG CÔNG TRÌNH
            </h2>
            <div class="relative px-4 md:px-0">
                <div class="swiper projects-slider overflow-visible">
                    <div class="swiper-wrapper">
                        @foreach ($galleryImages as $galleryImg)
                            <div class="swiper-slide transition-all duration-500">
                                <div class="aspect-[3/2] md:aspect-[4/3] overflow-hidden rounded-sm shadow-xl">
                                    <img src="{{ \App\Support\AssetPath::url($galleryImg) }}" alt="Dấu ấn công trình"
                                        class="w-full h-full object-cover grayscale-[20%] hover:grayscale-0 transition-all duration-700">
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="projects-pagination mt-6 md:mt-12 flex justify-center gap-[7px] md:gap-3"></div>
                <div
                    class="hidden md:flex projects-prev absolute left-2 md:left-6 lg:left-10 top-1/2 -translate-y-1/2 z-20 w-10 h-10 md:w-12 md:h-12 bg-secondary rounded rotate-45 items-center justify-center text-white cursor-pointer hover:bg-secondary/90 shadow-lg transition-all">
                    <div class="-rotate-45"><svg class="w-6 h-6" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 19l-7-7 7-7"></path>
                        </svg></div>
                </div>
                <div
                    class="projects-next absolute right-2 md:right-6 lg:right-10 top-1/2 -translate-y-1/2 z-20 w-10 h-10 md:w-12 md:h-12 bg-secondary rounded rotate-45 hidden md:flex items-center justify-center text-white cursor-pointer hover:bg-secondary/90 shadow-lg transition-all">
                    <div class="-rotate-45"><svg class="w-6 h-6" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                            </path>
                        </svg></div>
                </div>
            </div>
        </div>
    </section>

    <!-- Product Description Section (Size Info) -->
    @if ($product->size_image || (!empty($product->size_des) && is_array($product->size_des)))
        <section id="bang-kich-thuoc" class="max-w-[1320px] mx-auto pb-8 md:pb-16 lg:pb-24">
            <div class="w-[85%] mx-auto grid grid-cols-1 md:grid-cols-2 gap-0 lg:gap-16 items-center">
                <!-- Left Image -->
                <div class="flex items-center flex-col" data-aos="fade-right">
                    <h3
                        class="text-[20px] md:text-3xl font-bold text-[#C76E00] md:text-secondary uppercase mb-5 md:mb-12 tracking-wider leading-[32px] text-center">
                        Kích thước / Ý nghĩa
                    </h3>
                    @if ($product->size_image)
                        <img src="{{ asset('storage/' . $product->size_image) }}" alt="Mô tả kích thước"
                            class="w-full max-w-[550px] object-contain">
                    @else
                        <div
                            class="w-full max-w-[550px] aspect-square bg-gray-100 flex items-center justify-center text-gray-400">
                            Chưa có ảnh kích thước</div>
                    @endif
                </div>
                <!-- Right List -->
                <div class="flex flex-col justify-center md:mt-0 mt-8" data-aos="fade-left">
                    <ul
                        class="list-disc pl-5 md:pl-16 space-y-0 md:space-y-4 text-black md:text-primary font-light md:font-medium text-[13px] md:text-[20px] leading-[28px] md:leading-relaxed">
                        @if (!empty($product->size_des) && is_array($product->size_des))
                            @foreach ($product->size_des as $item)
                                <li>{{ $item }}</li>
                            @endforeach
                        @else
                            <li>Thông số Kích thước: {{ $product->size ?? 'Đang cập nhật' }}</li>
                        @endif
                    </ul>
                </div>
            </div>
        </section>
    @endif

    <!-- CÁC VỊ TRÍ ĐẶT NGHÊ PHONG THỦY (Tĩnh) -->
    <x-client.catalog.products.linh-vat-phong-thuy.placement-guide />

    <x-client.content.shared.outstanding-value />

    <x-client.catalog.shared.recommendations :related-products="$relatedProducts" route-name="client.products.linh-vat-phong-thuy.detail"
        pk-field="linh_vat_phong_thuy_ct_id" product-type="linh_vat_phong_thuy_ct" :compare-table="true" />

    <x-client.shared.faq-cta-banner />

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Projects (Works) Swiper
                new Swiper('.projects-slider', {
                    slidesPerView: 1.2,
                    spaceBetween: 20,
                    centeredSlides: true,
                    loop: true,
                    pagination: {
                        el: '.projects-pagination',
                        clickable: true,
                    },
                    navigation: {
                        nextEl: '.projects-next',
                        prevEl: '.projects-prev',
                    },
                    breakpoints: {
                        768: {
                            slidesPerView: 2.2,
                            spaceBetween: 40
                        },
                        1024: {
                            slidesPerView: 2.8,
                            spaceBetween: 50,
                            centeredSlides: false
                        }
                    }
                });
            });
        </script>
    @endpush
</x-client.layouts.main>
