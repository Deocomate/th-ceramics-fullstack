@props(['hideNewsletter' => false])

@php
  $contactHotline = $globalContact->hotline ?? '0966 55 8808';
  $contactHotlineLink = preg_replace('/\D+/', '', $contactHotline) ?: '0966558808';
  $contactEmail = data_get($globalContact, 'email', 'gshaithanh@gmail.com');
  $contactAddress = data_get($globalContact, 'address', '18 Phố Gốm – Giang Cao, Bát Tràng, Gia Lâm, Hà Nội');
  $facebookLink = data_get($globalContact, 'facebook_link', '#');
  $youtubeLink = data_get($globalContact, 'youtube_link', '#');
  $pinterestLink = data_get($globalContact, 'pinterest_link', '#');
  $zaloLink = data_get($globalContact, 'zalo_link', 'https://zalo.me/0966558808');
@endphp

<footer class="bg-[#262723] text-white">
  <!-- Main Footer -->
  <div class="relative bg-[#262723] overflow-hidden">
    <img
      src="{{ asset('assets/images/footer-image-2.png') }}"
      alt=""
      class="hidden lg:block absolute inset-0 w-full h-full object-cover"
    />
    <div
      class="relative w-[85%] max-w-[1320px] mx-auto pt-12 lg:pt-[150px] pb-[60px] lg:whitespace-nowrap"
    >
      <div
        class="grid grid-cols-2 gap-x-2 gap-y-8 lg:grid lg:grid-cols-[200px_455px_195px_370px_200px] lg:gap-0 lg:w-[1420px] lg:justify-start lg:-translate-x-[38px]"
      >
        <div
          class="hidden lg:flex flex-col justify-between items-center gap-6 w-full lg:w-[200px] lg:pr-[45px] shrink-0"
        >
          <div>
            <img
              src="{{ asset('assets/images/logo.png') }}"
              alt="Logo"
              class="w-[88px] h-[88px]"
            />
          </div>
          <div class="mt-auto lg:pt-16">
            <a
              href="#"
              class="inline-block"
              aria-label="Đã thông báo Bộ Công Thương"
            >
              <img
                src="{{ asset('assets/images/bo-cong-thuong.png') }}"
                alt="Đã thông báo Bộ Công Thương"
                class="h-[52px] w-auto"
              />
            </a>
          </div>
        </div>

        <div
          class="col-span-2 lg:col-span-1 w-full lg:w-[455px] shrink-0 order-1 lg:order-none"
        >
          <div
            class="text-white text-[15px] leading-[26px] mb-6 tracking-[-0.1px]"
          >
            <p class="font-bold uppercase">
              CÔNG TY TNHH SẢN XUẤT VÀ THƯƠNG MẠI<br class="block lg:hidden" />
              THANH HẢI
            </p>
            <p>{{ $contactAddress }}</p>
            <p>{{ $contactHotline }}</p>
            <p>{{ $contactEmail }}</p>
          </div>

          <div class="block lg:hidden mb-8">
            <a
              href="#"
              class="inline-block"
              aria-label="Đã thông báo Bộ Công Thương"
            >
              <img
                src="{{ asset('assets/images/bo-cong-thuong.png') }}"
                alt="Đã thông báo Bộ Công Thương"
                class="h-[52px] w-auto"
              />
            </a>
          </div>

          <div class="hidden lg:flex items-center gap-[20px] mb-8">
            <a href="{{ $facebookLink }}" aria-label="Facebook" target="_blank" rel="noopener noreferrer">
              <img
                src="{{ asset('assets/images/facebook.svg') }}"
                alt="Facebook"
                class="w-[18px] h-[18px] opacity-70 hover:opacity-100 transition-opacity"
              />
            </a>
            <a href="{{ $youtubeLink }}" aria-label="YouTube" target="_blank" rel="noopener noreferrer">
              <img
                src="{{ asset('assets/images/youtube.svg') }}"
                alt="YouTube"
                class="w-[24px] h-[18px] opacity-70 hover:opacity-100 transition-opacity"
              />
            </a>
            <a href="{{ $pinterestLink }}" aria-label="Pinterest" target="_blank" rel="noopener noreferrer">
              <img
                src="{{ asset('assets/images/pinterest.svg') }}"
                alt="Pinterest"
                class="w-[18px] h-[18px] opacity-70 hover:opacity-100 transition-opacity"
              />
            </a>
          </div>
        </div>

        <div
          class="col-span-1 lg:col-span-1 w-full lg:w-[195px] min-w-0 order-2 lg:order-none"
        >
          <h3
            class="text-white text-[14px] lg:text-base font-semibold leading-[26px] tracking-[-0.1px] mb-4 lg:mb-6 whitespace-nowrap uppercase"
          >
            TỔNG QUAN
          </h3>
          <div class="leading-[30px] lg:leading-[40px] whitespace-nowrap">
            <a
              href="{{ route('client.about') }}"
              class="text-white text-[12px] lg:text-base font-normal tracking-[-0.2px] whitespace-nowrap hover:text-secondary transition-colors"
              >Về chúng tôi</a
            ><br />
            <a
              href="{{ route('client.showroom') }}"
              class="text-white text-[12px] lg:text-base font-normal tracking-[-0.2px] whitespace-nowrap hover:text-secondary transition-colors"
              >Showroom</a
            ><br />
            <a
              href="{{ route('client.factory') }}"
              class="text-white text-[12px] lg:text-base font-normal tracking-[-0.2px] whitespace-nowrap hover:text-secondary transition-colors"
              >Nhà xưởng</a
            ><br />
            <a
              href="{{ route('client.contact') }}"
              class="text-white text-[12px] lg:text-base font-normal tracking-[-0.2px] whitespace-nowrap hover:text-secondary transition-colors"
              >Liên hệ</a
            >
          </div>
        </div>

        <div
          class="col-span-1 lg:col-span-1 w-full lg:w-[200px] min-w-0 order-3 lg:order-none"
        >
          <h3
            class="text-white text-[16px] lg:text-base font-semibold leading-[26px] tracking-[-0.1px] mb-4 lg:mb-6 whitespace-nowrap uppercase"
          >
            DỊCH VỤ KHÁCH HÀNG
          </h3>
          <div class="leading-[30px] lg:leading-[40px] whitespace-nowrap">
            <a
              href="{{ route('client.customer-service.show', 'tai-khoan-cua-toi') }}"
              class="text-white text-[12px] lg:text-base font-normal tracking-[-0.2px] whitespace-nowrap hover:text-secondary transition-colors"
              >Tài khoản</a
            ><br />
            <a
              href="{{ route('client.customer-service.show', 'quy-trinh-dat-hang') }}"
              class="text-white text-[12px] lg:text-base font-normal tracking-[-0.2px] whitespace-nowrap hover:text-secondary transition-colors"
              >Quy trình đặt hàng</a
            ><br />
            <a
              href="{{ route('client.customer-service.show', 'huong-dan-thi-cong') }}"
              class="text-white text-[12px] lg:text-base font-normal tracking-[-0.2px] whitespace-nowrap hover:text-secondary transition-colors"
              >Hướng dẫn thi công</a
            ><br />
            <a
              href="{{ route('client.faq') }}"
              class="text-white text-[12px] lg:text-base font-normal tracking-[-0.2px] whitespace-nowrap hover:text-secondary transition-colors"
              >FAQ</a
            >
          </div>
        </div>

        <div
          class="col-span-2 lg:col-span-1 w-full lg:w-[370px] min-w-0 order-4 lg:order-none"
        >
          <h3
            class="text-white text-[14px] lg:text-base font-semibold leading-[26px] tracking-[-0.1px] mb-4 lg:mb-6 text-start lg:text-center lg:mr-[65px] whitespace-nowrap uppercase"
          >
            SẢN PHẨM
          </h3>
          <div class="flex gap-[40px] lg:gap-[50px]">
            <div
              class="leading-[30px] lg:leading-[40px] whitespace-nowrap flex-1"
            >
              <a
                href="{{ route('client.products.ngoi-am-duong.index') }}"
                class="text-white text-[12px] lg:text-base font-normal tracking-[-0.2px] whitespace-nowrap hover:text-secondary transition-colors"
                >Ngói âm dương</a
              ><br />
              <a
                href="{{ route('client.products.ngoi-hai-van-mieu.index') }}"
                class="text-white text-[12px] lg:text-base font-normal tracking-[-0.2px] whitespace-nowrap hover:text-secondary transition-colors"
                >Ngói hài văn miếu</a
              ><br />
              <a
                href="{{ route('client.products.phu-kien-ngoi.index') }}"
                class="text-white text-[12px] lg:text-base font-normal tracking-[-0.2px] whitespace-nowrap hover:text-secondary transition-colors"
                >Phụ kiện mái</a
              ><br />
              <a
                href="{{ route('client.products.linh-vat-phong-thuy.index') }}"
                class="text-white text-[12px] lg:text-base font-normal tracking-[-0.2px] whitespace-nowrap hover:text-secondary transition-colors"
                >Linh vật phong thủy</a
              >
            </div>
            <div
              class="leading-[30px] lg:leading-[40px] whitespace-nowrap flex-1"
            >
              <a
                href="{{ route('client.products.gach-hoa-thong-gio.index') }}"
                class="text-white text-[12px] lg:text-base font-normal tracking-[-0.2px] whitespace-nowrap hover:text-secondary transition-colors"
                >Gạch thông gió</a
              ><br />
              <a
                href="{{ route('client.products.gach-trang-tri.index') }}"
                class="text-white text-[12px] lg:text-base font-normal tracking-[-0.2px] whitespace-nowrap hover:text-secondary transition-colors"
                >Gạch trang trí</a
              ><br />
              <a
                href="{{ route('client.products.lan-can-gom-su.index') }}"
                class="text-white text-[12px] lg:text-base font-normal tracking-[-0.2px] whitespace-nowrap hover:text-secondary transition-colors"
                >Lan can gốm sứ</a
              ><br />
              <a
                href="{{ route('client.products.den-gom-su.index') }}"
                class="text-white text-[12px] lg:text-base font-normal tracking-[-0.2px] whitespace-nowrap hover:text-secondary transition-colors"
                >Đèn vườn gốm sứ</a
              >
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="relative border-t border-white/20 w-full">
      <div class="w-[85%] max-w-[1320px] mx-auto py-6">
        <div
          class="flex flex-col-reverse md:flex-row justify-between items-center gap-2 md:gap-4"
        >
          <p
            class="text-[#909090] text-[12px] lg:text-sm leading-[26px] whitespace-nowrap text-center"
          >
            Copyright &copy; 2022 . All rights reserved
          </p>
          <div
            class="text-[#909090] text-[12px] lg:text-sm leading-[26px] whitespace-nowrap flex items-center justify-center flex-wrap md:flex-nowrap"
          >
            <a
              href="{{ route('client.customer-service.show', 'chinh-sach-van-chuyen') }}"
              class="whitespace-nowrap hover:text-secondary transition-colors"
              >Chính sách vận chuyển</a
            >
            <span class="text-[14px] mx-2 lg:mx-4">|</span>
            <a
              href="{{ route('client.customer-service.show', 'chinh-sach-doi-tra') }}"
              class="whitespace-nowrap hover:text-secondary transition-colors"
              >Chính sách đổi trả</a
            >
            <span class="text-[14px] mx-2 lg:mx-4">|</span>
            <a
              href="{{ route('client.customer-service.show', 'bao-mat-thong-tin') }}"
              class="whitespace-nowrap hover:text-secondary transition-colors"
              >Bảo mật thông tin</a
            >
          </div>
        </div>
      </div>
    </div>
  </div>

  <div
    class="md:hidden fixed right-3 bottom-20 sm:right-4 sm:bottom-24 z-[60] flex flex-col items-center gap-3"
    aria-label="Hành động nhanh"
  >
    <button
      type="button"
      data-back-to-top
      aria-label="Lên đầu trang"
      class="w-12 h-12 rounded-full bg-secondary text-white shadow-lg ring-1 ring-black/10 flex items-center justify-center transition-all duration-300 active:scale-95 opacity-0 translate-y-2 pointer-events-none"
    >
      <svg
        xmlns="http://www.w3.org/2000/svg"
        viewBox="0 0 24 24"
        fill="none"
        stroke="currentColor"
        stroke-width="2"
        class="w-5 h-5"
        aria-hidden="true"
      >
        <path
          stroke-linecap="round"
          stroke-linejoin="round"
          d="M5 15l7-7 7 7"
        ></path>
      </svg>
    </button>

    <a
      href="{{ $zaloLink }}"
      target="_blank"
      rel="noopener noreferrer"
      aria-label="Hỗ trợ Zalo"
      class="w-12 h-12 rounded-full bg-[#EBDDD0] shadow-lg ring-1 ring-black/10 flex items-center justify-center transition-transform duration-300 active:scale-95"
    >
      <img
        src="{{ asset('assets/images/zalo.png') }}"
        alt="Zalo"
        class="w-8 h-8 object-contain"
      />
    </a>
  </div>
</footer>
