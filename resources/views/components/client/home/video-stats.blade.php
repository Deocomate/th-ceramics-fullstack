@props([
    'trangChu' => null,
])

@php
    $homeYoutubeId = ! empty($trangChu?->video)
        ? \App\Support\ProductGallery::extractYoutubeId($trangChu->video)
        : null;
    $homeEmbed = $homeYoutubeId ? \App\Support\ProductGallery::embedUrl($homeYoutubeId) : null;
@endphp

<!-- Why choose us -->
<section class="bg-primary text-white py-8 lg:py-20">
    <div class="w-[85%] max-w-[1320px] mx-auto">
        <h2 class="text-secondary text-center lg:text-left text-[20px] leading-[32px] lg:text-[36px] lg:font-extrabold lg:leading-[39px] uppercase mb-8 lg:mb-10"
            data-aos="fade-up">
            Tại sao lựa chọn TH Ceramics
        </h2>

        @if ($homeYoutubeId && $homeEmbed)
            <div
                class="relative w-full aspect-video mb-8 lg:mb-10 rounded-lg lg:rounded-none overflow-hidden bg-black group"
                data-aos="fade-up"
                data-aos-delay="200"
                data-inline-video-shell
                data-youtube-id="{{ $homeYoutubeId }}"
                data-embed-src="{{ $homeEmbed }}"
            >
                <button
                    type="button"
                    data-inline-video-play
                    class="absolute inset-0 z-10 w-full h-full cursor-pointer"
                    aria-label="Phát video TH Ceramics"
                >
                    <img
                        src="{{ asset('assets/images/video-placeholder.jpg') }}"
                        alt="Video placeholder"
                        class="w-full h-full object-cover brightness-50 group-hover:brightness-[0.4] transition-all duration-300"
                    />
                    <span class="absolute inset-0 flex items-center justify-center pointer-events-none">
                        <span class="lg:hidden w-16 h-16 bg-white/30 backdrop-blur-sm rounded-full flex items-center justify-center group-hover:bg-white/50 transition-all duration-300">
                            <svg class="w-8 h-8 text-white ml-1" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path d="M8 5v14l11-7z"></path>
                            </svg>
                        </span>
                        <img
                            src="{{ asset('assets/images/play-video-button.svg') }}"
                            alt=""
                            class="hidden lg:block w-[110px] h-[108px] transition-transform duration-300 group-hover:scale-110"
                        />
                    </span>
                </button>
                <div data-yt-player-host class="absolute inset-0 z-20 w-full h-full bg-black hidden"></div>
            </div>
        @else
            <div
                class="relative w-full aspect-video mb-8 lg:mb-10 rounded-lg lg:rounded-none overflow-hidden cursor-pointer group"
                data-aos="fade-up"
                data-aos-delay="200"
            >
                <img
                    src="{{ asset('assets/images/video-placeholder.jpg') }}"
                    alt="Video placeholder"
                    class="w-full h-full object-cover brightness-50 group-hover:brightness-[0.4] transition-all duration-300"
                />
                <div class="absolute inset-0 flex items-center justify-center">
                    <div class="lg:hidden w-16 h-16 bg-white/30 backdrop-blur-sm rounded-full flex items-center justify-center group-hover:bg-white/50 transition-all duration-300">
                        <svg class="w-8 h-8 text-white ml-1" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M8 5v14l11-7z"></path>
                        </svg>
                    </div>
                    <img
                        src="{{ asset('assets/images/play-video-button.svg') }}"
                        alt="Play video"
                        class="hidden lg:block w-[110px] h-[108px] transition-transform duration-300 group-hover:scale-110"
                    />
                </div>
            </div>
        @endif

        @if ($trangChu && !empty($trangChu->nhung_con_so))
            <div class="grid grid-cols-5 gap-1 lg:gap-8" data-aos="fade-up" data-aos-delay="400">
                @foreach ($trangChu->nhung_con_so as $conSo)
                    <div class="flex flex-col items-center lg:items-start">
                        <div
                            class="text-white lg:text-[#EFE4DE] text-[16px] leading-[40px] lg:text-[42px] lg:font-semibold lg:leading-[40px] mb-0 text-center lg:text-left">
                            <span>{{ trim($conSo['head']) }}</span>
                        </div>
                        <p
                            class="text-white lg:text-[#EFE4DE] font-light lg:font-semibold text-[11px] leading-[15px] lg:text-[20px] lg:leading-[27px] text-center lg:text-left">
                            {!! nl2br(e($conSo['body'])) !!}
                        </p>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</section>
