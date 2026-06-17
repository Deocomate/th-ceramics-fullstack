@props(['hideNewsletter' => false])

@php
    $contactHotline = $globalContact->hotline ?? '0966 55 8808';
    $contactHotlineLink = preg_replace('/\D+/', '', $contactHotline) ?: '0966558808';
    $contactEmail = data_get($globalContact, 'email', 'gshaithanh@gmail.com');
    $contactAddress = data_get($globalContact, 'address', '18 Phố Gốm – Giang Cao, Bát Tràng,<br> Gia Lâm, Hà Nội');
    $facebookLink = data_get($globalContact, 'facebook_link', '#');
    $youtubeLink = data_get($globalContact, 'youtube_link', '#');
    $pinterestLink = data_get($globalContact, 'pinterest_link', '#');
    $zaloLink = data_get($globalContact, 'zalo_link', 'https://zalo.me/0966558808');
@endphp

<footer class="bg-[#262723] text-white font-archivo">
    <!-- Main Footer -->
    <div class="relative bg-[#262723] overflow-hidden">
        <div class="hidden md:block absolute inset-0"
            style="
        background-image: url('{{ asset('assets/images/footer-image-3.png') }}');
        background-size: auto 448px;
        background-position: top center;
        background-repeat: repeat-x;
      "
            aria-hidden="true"></div>
        <div class="absolute inset-0 bg-black/10 md:bg-black/20" aria-hidden="true"></div>

        <div
            class="relative w-full max-w-[1320px] mx-auto px-6 sm:px-8 xl:px-0 pt-12 md:pt-16 lg:pt-20 xl:pt-[126px] pb-10 md:pb-12 xl:pb-[33px]">
            <div
                class="grid grid-cols-1 gap-y-9 sm:grid-cols-2 sm:gap-x-10 lg:grid-cols-[160px_minmax(250px,1fr)_minmax(140px,0.65fr)_minmax(210px,0.85fr)] lg:gap-x-8 lg:gap-y-10 xl:grid-cols-[160px_minmax(280px,1.05fr)_minmax(145px,0.65fr)_minmax(340px,1.25fr)_minmax(220px,0.9fr)] xl:gap-x-9 2xl:gap-x-12 items-start">
                <div class="flex flex-col items-start sm:items-center gap-6 md:gap-10 lg:gap-[44px] shrink-0">
                    <div>
                        <img src="{{ asset('assets/images/logo.png') }}" alt="Logo"
                            class="w-[88px] h-[88px] object-contain" />
                    </div>
                    <div>
                        <a href="#" class="inline-block shrink-0" aria-label="Đã thông báo Bộ Công Thương">
                            <img src="{{ asset('assets/images/bo-cong-thuong.png') }}" alt="Đã thông báo Bộ Công Thương"
                                class="h-[46px] sm:h-[52px] w-auto max-w-none object-contain shrink-0" />
                        </a>
                    </div>
                </div>

                <div class="min-w-0 sm:col-span-1 md:col-span-1">
                    <div class="text-white text-[15px] leading-[26px] mb-7 lg:mb-[27px]">
                        <p class="font-bold uppercase leading-[24px] mb-4">
                            <span class="block">CÔNG TY TNHH SẢN XUẤT</span>
                            <span class="block">VÀ THƯƠNG MẠI THANH HẢI</span>
                        </p>
                        <p class="font-medium break-words">{!! $contactAddress !!}</p>
                        <p class="font-medium break-words">{{ $contactHotline }}</p>
                        <p class="font-medium break-words">{{ $contactEmail }}</p>
                    </div>

                    <div class="flex items-center gap-5">
                        <a href="{{ $facebookLink }}" aria-label="Facebook" target="_blank" rel="noopener noreferrer">
                            <img src="{{ asset('assets/images/facebook.svg') }}" alt="Facebook"
                                class="w-[18px] h-[18px] opacity-70 hover:opacity-100 transition-opacity" />
                        </a>
                        <a href="{{ $youtubeLink }}" aria-label="YouTube" target="_blank" rel="noopener noreferrer">
                            <img src="{{ asset('assets/images/youtube.svg') }}" alt="YouTube"
                                class="w-[24px] h-[18px] opacity-70 hover:opacity-100 transition-opacity" />
                        </a>
                        <a href="{{ $pinterestLink }}" aria-label="Pinterest" target="_blank"
                            rel="noopener noreferrer">
                            <img src="{{ asset('assets/images/pinterest.svg') }}" alt="Pinterest"
                                class="w-[18px] h-[18px] opacity-70 hover:opacity-100 transition-opacity" />
                        </a>
                    </div>
                </div>

                <div class="min-w-0">
                    <h3 class="text-white text-base font-semibold leading-[26px] mb-4 uppercase break-words">
                        TỔNG QUAN
                    </h3>
                    <div class="flex flex-col gap-3 lg:gap-[14px] text-[15px] lg:text-base leading-[26px]">
                        <a href="{{ route('client.about') }}"
                            class="text-white font-normal break-words hover:text-secondary transition-colors">Về chúng
                            tôi</a>
                        <a href="{{ route('client.showroom') }}"
                            class="text-white font-normal break-words hover:text-secondary transition-colors">Showroom</a>
                        <a href="{{ route('client.factory') }}"
                            class="text-white font-normal break-words hover:text-secondary transition-colors">Nhà
                            xưởng</a>
                        <a href="{{ route('client.contact') }}"
                            class="text-white font-normal break-words hover:text-secondary transition-colors">Liên
                            hệ</a>
                    </div>
                </div>

                <div
                    class="min-w-0 sm:col-span-2 lg:order-last lg:col-span-3 lg:col-start-2 xl:order-none xl:col-span-1 xl:col-start-auto">
                    <h3
                        class="text-white text-base font-semibold leading-[26px] mb-4 lg:text-center uppercase break-words">
                        SẢN PHẨM
                    </h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-3 lg:gap-x-[50px]">
                        <div class="flex flex-col gap-3 lg:gap-[14px] text-[15px] lg:text-base leading-[26px] min-w-0">
                            <a href="{{ route('client.products.ngoi-am-duong.index') }}"
                                class="text-white font-normal break-words hover:text-secondary transition-colors">Ngói
                                âm dương</a>
                            <a href="{{ route('client.products.ngoi-hai-van-mieu.index') }}"
                                class="text-white font-normal break-words hover:text-secondary transition-colors">Ngói
                                hài văn miếu</a>
                            <a href="{{ route('client.products.phu-kien-ngoi.index') }}"
                                class="text-white font-normal break-words hover:text-secondary transition-colors">Phụ
                                kiện mái</a>
                            <a href="{{ route('client.products.linh-vat-phong-thuy.index') }}"
                                class="text-white font-normal break-words hover:text-secondary transition-colors">Linh
                                vật phong thủy</a>
                        </div>
                        <div class="flex flex-col gap-3 lg:gap-[14px] text-[15px] lg:text-base leading-[26px] min-w-0">
                            <a href="{{ route('client.products.gach-hoa-thong-gio.index') }}"
                                class="text-white font-normal break-words hover:text-secondary transition-colors">Gạch
                                thông gió</a>
                            <a href="{{ route('client.products.gach-trang-tri.index') }}"
                                class="text-white font-normal break-words hover:text-secondary transition-colors">Gạch
                                trang trí</a>
                            <a href="{{ route('client.products.lan-can-gom-su.index') }}"
                                class="text-white font-normal break-words hover:text-secondary transition-colors">Lan
                                can gốm sứ</a>
                            <a href="{{ route('client.products.den-gom-su.index') }}"
                                class="text-white font-normal break-words hover:text-secondary transition-colors">Đèn
                                vườn gốm sứ</a>
                        </div>
                    </div>
                </div>

                <div class="min-w-0">
                    <h3 class="text-white text-base font-semibold leading-[26px] mb-4 uppercase whitespace-nowrap">
                        DỊCH VỤ KHÁCH HÀNG
                    </h3>
                    <div class="flex flex-col gap-3 lg:gap-[14px] text-[15px] lg:text-base leading-[26px]">
                        <a href="{{ route('client.customer-service.show', 'tai-khoan-cua-toi') }}"
                            class="text-white font-normal break-words hover:text-secondary transition-colors">Tài
                            khoản</a>
                        <a href="{{ route('client.customer-service.show', 'quy-trinh-dat-hang') }}"
                            class="text-white font-normal break-words hover:text-secondary transition-colors">Quy trình
                            đặt hàng</a>
                        <a href="{{ route('client.customer-service.show', 'huong-dan-thi-cong') }}"
                            class="text-white font-normal break-words hover:text-secondary transition-colors">Hướng dẫn
                            thi công</a>
                        <a href="{{ route('client.faq') }}"
                            class="text-white font-normal break-words hover:text-secondary transition-colors">FAQ</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="relative border-t border-white/20 w-full">
            <div class="w-full max-w-[1320px] mx-auto px-6 sm:px-8 xl:px-0 py-5 xl:py-[14px]">
                <div class="flex flex-col-reverse md:flex-row justify-between items-center gap-3 md:gap-6">
                    <p class="text-[#909090] text-sm leading-[26px] text-center md:text-left font-normal"
                        style="font-family: Inter, Archivo, sans-serif;">
                        Copyright &copy; 2022 . All rights reserved
                    </p>
                    <div class="text-[#909090] text-sm leading-[26px] flex items-center justify-center flex-wrap gap-y-1 font-normal text-center md:text-right"
                        style="font-family: Inter, Archivo, sans-serif;">
                        <a href="{{ route('client.customer-service.show', 'chinh-sach-van-chuyen') }}"
                            class="hover:text-secondary transition-colors">Chính sách vận chuyển</a>
                        <span class="text-sm mx-3 lg:mx-5" aria-hidden="true">|</span>
                        <a href="{{ route('client.customer-service.show', 'chinh-sach-doi-tra') }}"
                            class="hover:text-secondary transition-colors">Chính sách đổi trả</a>
                        <span class="text-sm mx-3 lg:mx-5" aria-hidden="true">|</span>
                        <a href="{{ route('client.customer-service.show', 'bao-mat-thong-tin') }}"
                            class="hover:text-secondary transition-colors">Bảo mật thông tin</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="fixed right-3 bottom-20 sm:right-4 sm:bottom-24 md:right-8 md:bottom-8 z-[60] flex flex-col items-center gap-3"
        aria-label="Hành động nhanh">
        <button type="button" data-back-to-top aria-label="Lên đầu trang"
            class="w-12 h-12 rounded-full bg-secondary text-white shadow-lg ring-1 ring-black/10 flex items-center justify-center transition-all duration-300 active:scale-95 md:hover:scale-105 opacity-0 translate-y-2 pointer-events-none">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                stroke-width="2" class="w-5 h-5" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7"></path>
            </svg>
        </button>

        <a href="{{ $zaloLink }}" target="_blank" rel="noopener noreferrer" aria-label="Hỗ trợ Zalo"
            class="md:hidden w-12 h-12 rounded-full bg-[#EBDDD0] shadow-lg ring-1 ring-black/10 flex items-center justify-center transition-transform duration-300 active:scale-95">
            <img src="{{ asset('assets/images/zalo.png') }}" alt="Zalo" class="w-8 h-8 object-contain" />
        </a>
    </div>
</footer>
