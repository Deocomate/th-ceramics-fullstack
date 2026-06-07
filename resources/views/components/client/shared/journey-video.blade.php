@props(['video' => null, 'hideTitle' => false])

<section class="w-full pb-[30px] md:pb-16"> <!-- Đã xóa max-w-[1920px] mx-auto để full-width 100% -->
    @unless ($hideTitle)
        <div class="text-center mb-8 md:mb-16" data-aos="fade-up">
            <h2 class="text-[20px] md:text-3xl font-semibold text-secondary uppercase">
                Hành trình chế tác
            </h2>
        </div>
    @endunless

    @if ($video)
        <!-- Nếu có link video, dùng thẻ <a> để kích hoạt popup GLightbox -->
        <a href="{{ $video }}" class="glightbox-video block relative w-full aspect-video overflow-hidden group"
            data-aos="fade-up" data-aos-delay="200">
        @else
            <!-- Nếu không có link video, chỉ hiển thị khối div tĩnh -->
            <div class="relative w-full aspect-video overflow-hidden cursor-pointer group" data-aos="fade-up"
                data-aos-delay="200">
    @endif

    <img src="{{ asset('assets/images/video-placeholder-02.png') }}" alt="Video placeholder"
        class="w-full h-full object-cover brightness-80 group-hover:brightness-[0.6] transition-all duration-300" />

    <div class="absolute inset-0 flex items-center justify-center">
        <div
            class="w-16 h-16 lg:w-20 lg:h-20 bg-white/30 backdrop-blur-sm rounded-full flex items-center justify-center group-hover:bg-white/50 transition-all duration-300">
            <svg class="w-8 h-8 lg:w-10 lg:h-10 text-white ml-1" fill="currentColor" viewBox="0 0 24 24">
                <path d="M8 5v14l11-7z"></path>
            </svg>
        </div>
    </div>

    @if ($video)
        </a>
    @else
        </div>
    @endif
</section>

<!-- Script khởi tạo riêng cho Video để tránh xung đột với thư viện ảnh -->
@push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            if (typeof GLightbox !== "undefined") {
                GLightbox({
                    selector: '.glightbox-video',
                    touchNavigation: true,
                    loop: false,
                    autoplayVideos: true,
                });
            }
        });
    </script>
@endpush
