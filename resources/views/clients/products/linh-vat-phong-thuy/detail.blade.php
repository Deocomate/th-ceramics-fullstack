<x-layouts.client title="Chi tiết {{ $product->name }}" data-page="products"
    main-class="flex-grow bg-background-secondary pb-14 md:pb-20" :hide-newsletter="true">
    @push('styles')
        <style>
            @import url("https://fonts.googleapis.com/css2?family=Italianno&display=swap");
            @import url("https://fonts.googleapis.com/css2?family=Charm:wght@400;700&family=Italianno&display=swap");

            /* Custom scrollbar for comparison table */
            .custom-recommend-scrollbar::-webkit-scrollbar {
                height: 6px;
            }

            .custom-recommend-scrollbar::-webkit-scrollbar-track {
                background: #f1f1f1;
                border-radius: 4px;
            }

            .custom-recommend-scrollbar::-webkit-scrollbar-thumb {
                background: #C76E00;
                border-radius: 4px;
            }

            .custom-recommend-scrollbar::-webkit-scrollbar-thumb:hover {
                background: #A35A00;
            }
        </style>
    @endpush

    <!-- Top Banner for Detail -->
    <section class="hidden md:flex relative w-full">
        <div
            class="relative w-full aspect-[4/3] md:aspect-[8/6] lg:aspect-auto lg:h-[695px] lg:[clip-path:inset(40px_0_0_0)] lg:-mt-[40px]">
            @php $detailBanner = (!empty($product->images) && is_array($product->images)) ? $product->images[0] : null; @endphp
            <img src="{{ $detailBanner ? asset('storage/' . $detailBanner) : asset('assets/images/pk-banner.png') }}"
                alt="{{ $product->name }}" class="w-full h-full object-cover">

            <div class="absolute inset-0 flex flex-col items-center pt-[5%] md:pt-[5%] lg:pt-[5%]" data-aos="fade-up"
                data-aos-delay="100">
                <div class="text-center text-white px-4 w-[85%] max-w-[1320px] mx-auto">
                    <h1
                        class="font-sans text-[26px] md:text-4xl lg:text-[44px] font-bold uppercase mb-2 md:mb-6 drop-shadow-md">
                        LINH VẬT PHONG THỦY
                    </h1>
                    <p
                        class="font-italianno text-xl md:text-[34px] lg:text-[48px] font-light leading-none tracking-wide drop-shadow-sm text-white/95">
                        Tâm linh hội tụ, vượng khí an gia
                    </p>
                    <p
                        class="font-italianno text-xl md:text-[34px] lg:text-[48px] font-light leading-none tracking-wide drop-shadow-sm text-white/95">
                        {{ $product->name }}
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
        <div class="flex flex-col md:gap-5 lg:col-span-3">
            <!-- Main Image Swiper -->
            <div
                class="w-full aspect-square bg-white md:shadow-lg relative overflow-hidden group swiper product-main-swiper">
                <div class="swiper-wrapper">
                    @if (!empty($product->images) && is_array($product->images))
                        @foreach ($product->images as $img)
                            <div class="swiper-slide w-full h-full bg-gray-50 flex items-center justify-center">
                                <img src="{{ asset('storage/' . $img) }}" alt="{{ $product->name }}"
                                    class="w-full h-full object-contain transition-transform duration-500 group-hover:scale-110">
                            </div>
                        @endforeach
                    @else
                        <div class="swiper-slide w-full h-full bg-gray-50 flex items-center justify-center">
                            <img src="{{ asset('assets/images/ngoi-01.jpg') }}" class="w-full h-full object-contain">
                        </div>
                    @endif
                </div>
            </div>

            <!-- Mobile Dots Pagination -->
            <div class="md:hidden flex justify-center mt-5">
                <div class="product-main-pagination flex justify-center gap-[7px]"></div>
            </div>

            <!-- Thumbnails (Desktop only) -->
            <div class="hidden md:block w-full mt-2 overflow-hidden swiper product-thumb-swiper">
                <div class="swiper-wrapper">
                    @if (!empty($product->images) && is_array($product->images))
                        @foreach ($product->images as $img)
                            <div
                                class="swiper-slide aspect-square bg-gray-100 cursor-pointer shadow-sm hover:opacity-80 transition-opacity border border-transparent overflow-hidden">
                                <img src="{{ asset('storage/' . $img) }}" class="w-full h-full object-cover">
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>

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
                {{ $product->price > 0 ? number_format($product->price, 0, ',', '.') . ' đ' : 'Liên hệ' }}
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
                        <button type="button" onclick="this.nextElementSibling.stepDown()"
                            class="w-6 h-6 flex items-center justify-center text-[20px] md:text-xl focus:outline-none md:hover:text-secondary transition-colors">-</button>
                        <input type="number" name="quantity" value="1" min="1"
                            class="w-12 h-12 flex items-center justify-center text-center rounded-full text-[16px] md:text-base font-normal shadow-[0px_1px_2px_rgba(0,0,0,0.05)] md:shadow-sm outline outline-1 outline-black/40 outline-offset-[-1px] md:outline-none md:border md:border-black/40 bg-transparent"
                            style="-moz-appearance: textfield;">
                        <button type="button" onclick="this.previousElementSibling.stepUp()"
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

    <!-- Product Description Section (Size Info) -->
    @if ($product->size_image || (!empty($product->size_des) && is_array($product->size_des)))
        <section class="max-w-[1320px] mx-auto pb-8 md:pb-16 lg:pb-24">
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
    <section class="max-w-[1320px] w-[85%] mx-auto pb-4 md:pb-16 lg:pb-24">
        <h2 class="text-[20px] md:text-3xl font-semibold text-[#C76E00] md:text-secondary uppercase text-center mb-5 md:mb-16 leading-[32px] md:leading-normal"
            data-aos="fade-up">
            CÁC VỊ TRÍ ĐẶT NGHÊ PHONG THỦY
        </h2>
        <div class="flex flex-nowrap md:grid md:grid-cols-2 gap-4 md:gap-x-16 lg:gap-x-24 md:gap-y-12 lg:gap-y-16 xl:w-[70%] mx-auto overflow-x-auto lg:overflow-visible snap-x snap-mandatory pb-4 md:pb-0"
            style="scrollbar-width: none">
            <div class="w-[85vw] md:w-auto shrink-0 snap-center bg-white p-8 md:p-14 shadow-[0px_1px_2px_rgba(0,0,0,0.05)] md:shadow-sm flex flex-col items-center text-center transition-transform hover:-translate-y-1 duration-300"
                data-aos="fade-up" data-aos-delay="100">
                <img src="{{ asset('assets/images/gate-1.svg') }}" alt="Cổng ra vào" class="h-16 md:h-24 mb-6">
                <div class="flex flex-col items-center">
                    <h3
                        class="text-[18px] md:text-[21px] font-bold text-[#2E2F2A] md:text-primary mb-4 leading-[27px] md:leading-normal">
                        Cổng ra vào</h3>
                    <p
                        class="text-[#2E2F2A] md:text-primary text-[13px] md:text-[15px] leading-[21.13px] md:leading-relaxed text-left md:text-start">
                        Cổng làng, cổng đình, cổng nhà. Đây là vị trí phổ biến nhất. Một cặp Nghê thường được đặt hai
                        bên cổng để kiểm soát tâm linh, xua đuổi tà khí và ngăn chặn những điều xấu xâm nhập vào bên
                        trong.</p>
                </div>
            </div>
            <div class="w-[85vw] md:w-auto shrink-0 snap-center bg-white p-8 md:p-14 shadow-[0px_1px_2px_rgba(0,0,0,0.05)] md:shadow-sm flex flex-col items-center text-center transition-transform hover:-translate-y-1 duration-300"
                data-aos="fade-up" data-aos-delay="200">
                <img src="{{ asset('assets/images/gate-2.svg') }}" alt="Trước cửa điện thờ, từ đường"
                    class="h-16 md:h-24 mb-6">
                <div class="flex flex-col items-center">
                    <h3
                        class="text-[18px] md:text-[21px] font-bold text-[#2E2F2A] md:text-primary mb-4 leading-[27px] md:leading-normal">
                        Trước cửa điện thờ, từ đường</h3>
                    <p
                        class="text-[#2E2F2A] md:text-primary text-[13px] md:text-[15px] leading-[21.13px] md:leading-relaxed text-left md:text-start">
                        Nhà thờ họ. Nghê đặt tại đây đóng vai trò như "vị hộ thần" bảo vệ sự tôn nghiêm của không gian
                        thờ cúng và lòng thành kính của hậu thế đối với tổ tiên.</p>
                </div>
            </div>
            <div class="w-[85vw] md:w-auto shrink-0 snap-center bg-white p-8 md:p-14 shadow-[0px_1px_2px_rgba(0,0,0,0.05)] md:shadow-sm flex flex-col items-center text-center transition-transform hover:-translate-y-1 duration-300"
                data-aos="fade-up" data-aos-delay="100">
                <img src="{{ asset('assets/images/roof.svg') }}" alt="Trên trụ cổng hoặc bờ nóc mái"
                    class="h-16 md:h-24 mb-6">
                <div class="flex flex-col items-center">
                    <h3
                        class="text-[18px] md:text-[21px] font-bold text-[#2E2F2A] md:text-primary mb-4 leading-[27px] md:leading-normal">
                        Trên trụ cổng hoặc bờ nóc mái</h3>
                    <p
                        class="text-[#2E2F2A] md:text-primary text-[13px] md:text-[15px] leading-[21.13px] md:leading-relaxed text-left md:text-start">
                        Bạn sẽ thường thấy những tượng Nghê nhỏ được đặt trên đỉnh trụ cổng hoặc đậu trên các góc mái
                        đình, chùa. Vị trí trên cao giúp Nghê quan sát rộng, trấn giữ phương hướng và tạo điểm nhấn uy
                        nghiêm cho kiến trúc.</p>
                </div>
            </div>
            <div class="w-[85vw] md:w-auto shrink-0 snap-center bg-white p-8 md:p-14 shadow-[0px_1px_2px_rgba(0,0,0,0.05)] md:shadow-sm flex flex-col items-center text-center transition-transform hover:-translate-y-1 duration-300"
                data-aos="fade-up" data-aos-delay="200">
                <img src="{{ asset('assets/images/furniture.svg') }}" alt="Trong không gian nội thất"
                    class="h-16 md:h-24 mb-6">
                <div class="flex flex-col items-center">
                    <h3
                        class="text-[18px] md:text-[21px] font-bold text-[#2E2F2A] md:text-primary mb-4 leading-[27px] md:leading-normal">
                        Trong không gian nội thất</h3>
                    <p
                        class="text-[#2E2F2A] md:text-primary text-[13px] md:text-[15px] leading-[21.13px] md:leading-relaxed text-left md:text-start">
                        Phòng khách, bàn làm việc. Những phiên bản Nghê cỡ nhỏ thường được dùng làm vật phẩm phong thủy
                        để trấn trạch, hóa giải sát khí và cầu bình an ngay trong ngôi nhà hoặc nơi làm việc.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- DẤU ẤN TRÊN NHỮNG CÔNG TRÌNH SECTION (Gallery Slider) -->
    @php
        // Gộp ảnh sản phẩm hoặc dùng ảnh tĩnh nếu ít ảnh
        $galleryImages =
            is_array($product->images) && count($product->images) > 0
                ? $product->images
                : [
                    'assets/images/gach-co-work-1.jpg',
                    'assets/images/gach-co-work-2.jpg',
                    'assets/images/trang-tri-slide-01.jpg',
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
                <!-- Navigation Buttons -->
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

    <!-- HÀNH TRÌNH CHẾ TÁC VIDEO -->
    <div class="hidden md:block">
        <x-products.journey-video :hide-title="false" />
    </div>

    <!-- BẢNG SO SÁNH / CÓ THỂ BẠN QUAN TÂM -->
    @if ($relatedProducts->isNotEmpty())
        <section class="w-full pb-0 md:pb-16 bg-background-secondary relative" data-aos="fade-up">
            <div class="max-w-[1320px] w-[85%] mx-auto relative z-10">
                <h2 class="text-[20px] md:text-3xl font-semibold text-secondary text-center uppercase mb-5 md:mb-16">
                    CÓ THỂ BẠN QUAN TÂM
                </h2>
                <div class="relative">
                    <div class="overflow-x-auto pb-6 custom-recommend-scrollbar mobile-scroll-visible"
                        data-scroll-indicator-init="true">
                        <div class="min-w-[900px] md:min-w-[1000px] w-max">
                            <!-- Product Images Row -->
                            <div class="flex gap-0 md:gap-10 mb-4 md:mb-8">
                                <div
                                    class="hidden md:block w-[140px] shrink-0 sticky left-0 bg-background-secondary z-10">
                                </div>
                                <div class="flex-grow flex gap-4 md:gap-10">
                                    @foreach ($relatedProducts->take(5) as $related)
                                        @php
                                            $relatedImg =
                                                !empty($related->images) && is_array($related->images)
                                                    ? $related->images[0]
                                                    : null;
                                        @endphp
                                        <div class="w-[175px] md:w-[220px] shrink-0 flex flex-col items-start">
                                            <a href="{{ route('client.products.linh-vat-phong-thuy.detail', $related->linh_vat_phong_thuy_ct_id) }}"
                                                class="w-full group">
                                                <div
                                                    class="product-card relative aspect-square w-full mb-2 md:mb-4 bg-white shadow-sm rounded-sm overflow-hidden">
                                                    <img src="{{ $relatedImg ? asset('storage/' . $relatedImg) : asset('assets/images/ngoi-01.jpg') }}"
                                                        alt="{{ $related->name }}"
                                                        class="object-cover w-full h-full mix-blend-multiply">
                                                    <div class="product-overlay">
                                                        <img src="{{ asset('assets/images/eye.svg') }}"
                                                            alt="Xem">
                                                        <span>Xem chi tiết</span>
                                                    </div>
                                                </div>
                                            </a>
                                            <a href="{{ route('client.products.linh-vat-phong-thuy.detail', $related->linh_vat_phong_thuy_ct_id) }}"
                                                class="text-[#004B8D] hover:text-secondary transition-colors">
                                                <h3
                                                    class="font-bold text-[12px] md:text-base leading-snug h-6 md:h-12 overflow-hidden mb-0 md:mb-2 uppercase">
                                                    {{ $related->name }}
                                                </h3>
                                            </a>
                                            <form action="{{ route('client.cart.add') }}" method="POST"
                                                class="mt-2">
                                                @csrf
                                                <input type="hidden" name="product_id"
                                                    value="{{ $related->linh_vat_phong_thuy_ct_id }}">
                                                <input type="hidden" name="product_type"
                                                    value="linh_vat_phong_thuy_ct">
                                                <input type="hidden" name="quantity" value="1">
                                                <button type="submit"
                                                    class="border border-secondary text-secondary text-[9px] md:text-[13px] font-bold py-1 md:py-2 px-4 md:px-6 rounded-full hover:bg-secondary hover:text-white transition-all whitespace-nowrap">
                                                    Thêm vào giỏ
                                                </button>
                                            </form>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Comparison Table Attributes -->
                            <div class="flex flex-col border-t border-black/10">
                                <!-- Price Row -->
                                <div class="flex gap-0 md:gap-10 group mobile-compare-row py-3">
                                    <div
                                        class="hidden md:flex w-[140px] shrink-0 text-base font-bold text-primary items-center sticky left-0 bg-background-secondary z-10">
                                        <span class="compare-cell-text">Giá</span>
                                    </div>
                                    <div class="flex-grow flex md:gap-10 items-center">
                                        <div
                                            class="md:hidden w-0 h-full shrink-0 sticky left-0 z-20 overflow-visible font-bold text-[12px]">
                                            Giá:</div>
                                        @foreach ($relatedProducts->take(5) as $related)
                                            <div
                                                class="w-[175px] md:w-[220px] shrink-0 text-[12px] md:text-base text-[#C76E00] font-semibold mr-4 md:mr-auto pl-8 md:pl-0">
                                                {{ $related->price > 0 ? number_format($related->price, 0, ',', '.') . ' đ' : 'Liên hệ' }}
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                <!-- Color Row -->
                                <div
                                    class="flex gap-0 md:gap-10 border-t border-black/10 group mobile-compare-row py-3">
                                    <div
                                        class="hidden md:flex w-[140px] shrink-0 text-base font-bold text-primary items-center sticky left-0 bg-background-secondary z-10">
                                        <span class="compare-cell-text">Màu sắc</span>
                                    </div>
                                    <div class="flex-grow flex md:gap-10 items-center">
                                        <div
                                            class="md:hidden w-0 h-full shrink-0 sticky left-0 z-20 overflow-visible font-bold text-[12px]">
                                            Màu:</div>
                                        @foreach ($relatedProducts->take(5) as $related)
                                            <div
                                                class="w-[175px] md:w-[220px] shrink-0 text-[12px] md:text-base text-primary/80 mr-4 md:mr-auto pl-8 md:pl-0">
                                                {{ $related->color ?? 'N/A' }}
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                <!-- Size Row -->
                                <div class="flex md:gap-10 border-y border-black/10 group mobile-compare-row py-3">
                                    <div
                                        class="hidden md:flex w-[140px] shrink-0 text-base font-bold text-primary items-center sticky left-0 bg-background-secondary z-10">
                                        <span class="compare-cell-text">Kích thước</span>
                                    </div>
                                    <div class="flex-grow flex md:gap-10 items-center">
                                        <div
                                            class="md:hidden w-0 h-full shrink-0 sticky left-0 z-20 overflow-visible font-bold text-[12px]">
                                            Size:</div>
                                        @foreach ($relatedProducts->take(5) as $related)
                                            <div
                                                class="w-[175px] md:w-[220px] shrink-0 text-[12px] md:text-base text-primary/80 leading-relaxed mr-4 md:mr-auto pl-8 md:pl-0">
                                                {{ $related->size ?? 'Liên hệ' }}
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif

    <x-products.faq2 />

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Main Product Gallery Swiper
                const thumbSwiper = new Swiper('.product-thumb-swiper', {
                    spaceBetween: 20,
                    slidesPerView: 'auto',
                    freeMode: true,
                    watchSlidesProgress: true,
                    breakpoints: {
                        768: {
                            slidesPerView: 4
                        },
                        1024: {
                            slidesPerView: 5
                        }
                    }
                });

                const mainSwiper = new Swiper('.product-main-swiper', {
                    spaceBetween: 10,
                    pagination: {
                        el: '.product-main-pagination',
                        clickable: true,
                    },
                    thumbs: {
                        swiper: thumbSwiper
                    },
                    effect: 'fade',
                    fadeEffect: {
                        crossFade: true
                    }
                });

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
                            spaceBetween: 60,
                            centeredSlides: false
                        }
                    }
                });
            });
        </script>
    @endpush
</x-layouts.client>
