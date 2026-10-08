<div class="hidden xl:flex items-center 2xl:gap-10 xl:gap-6 gap-6 flex-1 justify-end mr-8"
    id="desktop-menu">
    <a href="{{ route('client.home') }}"
        class="nav-link whitespace-nowrap shrink-0 text-[#FFFAF3] font-archivo font-bold text-[16px] leading-[18px] uppercase hover:text-secondary transition-colors"
        data-path="/">trang chủ</a>
    <a href="{{ route('client.about') }}"
        class="nav-link whitespace-nowrap shrink-0 text-[#FFFAF3] font-archivo font-bold text-[16px] leading-[18px] uppercase hover:text-secondary transition-colors"
        data-path="/ve-chung-toi">về chúng tôi</a>
    <div class="relative group">
        <a href="{{ route('client.products.ngoi-am-duong.index') }}"
            class="nav-link flex items-center gap-[5px] whitespace-nowrap shrink-0 text-[#FFFAF3] font-archivo font-bold text-[16px] leading-[18px] uppercase hover:text-secondary transition-colors"
            data-path="/san-pham/">
            <span>sản phẩm</span>
            <svg viewBox="0 0 7 7" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-[7px] h-[7px] fill-current">
                <path d="M6.05225 1.87792L3.50017 3.91959L0.948088 1.87792C0.88826 1.83005 0.819587 1.79442 0.745991 1.77308C0.672395 1.75174 0.595317 1.74511 0.519158 1.75356C0.442998 1.76201 0.369249 1.78538 0.30212 1.82233C0.234992 1.85928 0.175799 1.90909 0.127921 1.96892C0.0800432 2.02875 0.0444182 2.09742 0.0230801 2.17102C0.00174206 2.24462 -0.00489129 2.32169 0.00355885 2.39785C0.012009 2.47401 0.0353771 2.54776 0.072329 2.61489C0.109281 2.68202 0.159093 2.74121 0.218921 2.78909L3.13559 5.12242C3.23905 5.20526 3.36763 5.25039 3.50017 5.25039C3.63271 5.25039 3.76129 5.20526 3.86475 5.12242L6.78142 2.78909C6.90225 2.6924 6.97972 2.55166 6.99678 2.39785C7.01385 2.24404 6.96911 2.08975 6.87242 1.96892C6.82454 1.90909 6.76535 1.85928 6.69822 1.82233C6.63109 1.78538 6.55734 1.76201 6.48118 1.75356C6.32737 1.7365 6.17308 1.78123 6.05225 1.87792Z" />
            </svg>
        </a>
        <!-- Desktop Dropdown -->
        <div
            class="absolute top-full left-0 mt-0 w-[240px] bg-white shadow-xl rounded-b opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 z-50 border-t-2 border-secondary">
            <ul class="py-2 flex flex-col">
                <li>
                    <a href="{{ route('client.products.ngoi-am-duong.index') }}"
                        class="desktop-dropdown-link block px-5 py-3 whitespace-nowrap text-[#212121] font-archivo font-bold text-[16px] leading-[18px] uppercase hover:bg-neutral-1 hover:text-secondary transition-colors border-b border-gray-100 last:border-0"
                        data-path="/san-pham/ngoi-am-duong">Ngói Âm Dương</a>
                </li>
                <li>
                    <a href="{{ route('client.products.ngoi-hai-van-mieu.index') }}"
                        class="desktop-dropdown-link block px-5 py-3 whitespace-nowrap text-[#212121] font-archivo font-bold text-[16px] leading-[18px] uppercase hover:bg-neutral-1 hover:text-secondary transition-colors border-b border-gray-100 last:border-0"
                        data-path="/san-pham/ngoi-hai-van-mieu">Ngói Hài Văn Miếu</a>
                </li>
                <li>
                    <a href="{{ route('client.products.gach-hoa-thong-gio.index') }}"
                        class="desktop-dropdown-link block px-5 py-3 whitespace-nowrap text-[#212121] font-archivo font-bold text-[16px] leading-[18px] uppercase hover:bg-neutral-1 hover:text-secondary transition-colors border-b border-gray-100 last:border-0"
                        data-path="/san-pham/gach-hoa-thong-gio">Gạch Hoa Thông Gió</a>
                </li>
                <li>
                    <a href="{{ route('client.products.phu-kien-ngoi.index') }}"
                        class="desktop-dropdown-link block px-5 py-3 whitespace-nowrap text-[#212121] font-archivo font-bold text-[16px] leading-[18px] uppercase hover:bg-neutral-1 hover:text-secondary transition-colors border-b border-gray-100 last:border-0"
                        data-path="/san-pham/phu-kien-ngoi">Phụ Kiện Ngói</a>
                </li>
                <li>
                    <a href="{{ route('client.products.gach-trang-tri.index') }}"
                        class="desktop-dropdown-link block px-5 py-3 whitespace-nowrap text-[#212121] font-archivo font-bold text-[16px] leading-[18px] uppercase hover:bg-neutral-1 hover:text-secondary transition-colors border-b border-gray-100 last:border-0"
                        data-path="/san-pham/gach-trang-tri">Gạch Trang Trí</a>
                </li>
                <li>
                    <a href="{{ route('client.products.lan-can-gom-su.index') }}"
                        class="desktop-dropdown-link block px-5 py-3 whitespace-nowrap text-[#212121] font-archivo font-bold text-[16px] leading-[18px] uppercase hover:bg-neutral-1 hover:text-secondary transition-colors border-b border-gray-100 last:border-0"
                        data-path="/san-pham/lan-can-gom-su">Lan Can Gốm Sứ</a>
                </li>
                <li>
                    <a href="{{ route('client.products.gach-co-bat-trang.index') }}"
                        class="desktop-dropdown-link block px-5 py-3 whitespace-nowrap text-[#212121] font-archivo font-bold text-[16px] leading-[18px] uppercase hover:bg-neutral-1 hover:text-secondary transition-colors border-b border-gray-100 last:border-0"
                        data-path="/san-pham/gach-co-bat-trang">Gạch Cổ Bát Tràng</a>
                </li>
                <li>
                    <a href="{{ route('client.products.linh-vat-phong-thuy.index') }}"
                        class="desktop-dropdown-link block px-5 py-3 whitespace-nowrap text-[#212121] font-archivo font-bold text-[16px] leading-[18px] uppercase hover:bg-neutral-1 hover:text-secondary transition-colors border-b border-gray-100 last:border-0"
                        data-path="/san-pham/linh-vat-phong-thuy">Linh Vật Phong Thủy</a>
                </li>
                <li>
                    <a href="{{ route('client.products.den-gom-su.index') }}"
                        class="desktop-dropdown-link block px-5 py-3 whitespace-nowrap text-[#212121] font-archivo font-bold text-[16px] leading-[18px] uppercase hover:bg-neutral-1 hover:text-secondary transition-colors border-b border-gray-100 last:border-0"
                        data-path="/san-pham/den-gom-su">Đèn Gốm Sứ</a>
                </li>
            </ul>
        </div>
    </div>

    <div class="contents xl:flex items-center 2xl:gap-10 xl:gap-6 gap-6" data-nav-trailing>
        <a href="{{ route('client.projects.index') }}"
            class="nav-link whitespace-nowrap shrink-0 text-[#FFFAF3] font-archivo font-bold text-[16px] leading-[18px] uppercase hover:text-secondary transition-colors"
            data-path="/du-an">Dự án</a>
        <a href="{{ route('client.factory') }}"
            class="nav-link whitespace-nowrap shrink-0 text-[#FFFAF3] font-archivo font-bold text-[16px] leading-[18px] uppercase hover:text-secondary transition-colors"
            data-path="/xuong-san-xuat">Xưởng sản xuất</a>
        <a href="{{ route('client.news.index') }}"
            class="nav-link whitespace-nowrap shrink-0 text-[#FFFAF3] font-archivo font-bold text-[16px] leading-[18px] uppercase hover:text-secondary transition-colors"
            data-path="/tin-tuc">Tin tức</a>
        <a href="{{ route('client.contact') }}"
            class="nav-link whitespace-nowrap shrink-0 text-[#FFFAF3] font-archivo font-bold text-[16px] leading-[18px] uppercase hover:text-secondary transition-colors"
            data-path="/lien-he">liên hệ</a>
    </div>
</div>
