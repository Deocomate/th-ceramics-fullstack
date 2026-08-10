@props(['video' => null, 'hideTitle' => false])

@php
    $youtubeId = ! empty($video) ? \App\Support\ProductGallery::extractYoutubeId($video) : null;
    $journeyEmbed = $youtubeId ? \App\Support\ProductGallery::embedUrl($youtubeId) : null;
@endphp

<section class="w-full pb-[30px] md:pb-16">
    @unless ($hideTitle)
        <div class="text-center mb-8 md:mb-16" data-aos="fade-up">
            <h2 class="text-[20px] md:text-3xl font-semibold text-secondary uppercase">
                Hành trình chế tác
            </h2>
        </div>
    @endunless

    @if ($journeyEmbed && $youtubeId)
        <div
            class="relative w-full h-[480px] md:h-[640px] lg:h-[720px] overflow-hidden bg-black group"
            data-aos="fade-up"
            data-aos-delay="200"
            data-inline-video-shell
            data-youtube-id="{{ $youtubeId }}"
            data-embed-src="{{ $journeyEmbed }}"
        >
            <button
                type="button"
                data-inline-video-play
                class="absolute inset-0 z-10 w-full h-full cursor-pointer"
                aria-label="Phát video hành trình chế tác"
            >
                <img
                    src="{{ asset('assets/images/video-placeholder-02.png') }}"
                    alt="Video placeholder"
                    class="w-full h-full object-cover brightness-80 group-hover:brightness-[0.6] transition-all duration-300"
                />
                <span class="absolute inset-0 flex items-center justify-center pointer-events-none">
                    <span class="w-16 h-16 lg:w-20 lg:h-20 bg-white/30 backdrop-blur-sm rounded-full flex items-center justify-center group-hover:bg-white/50 transition-all duration-300">
                        <svg class="w-8 h-8 lg:w-10 lg:h-10 text-white ml-1" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M8 5v14l11-7z"></path>
                        </svg>
                    </span>
                </span>
            </button>
            <div data-yt-player-host class="absolute inset-0 z-20 w-full h-full bg-black hidden"></div>
        </div>
    @else
        <div
            class="relative w-full h-[480px] md:h-[640px] lg:h-[720px] overflow-hidden cursor-pointer group"
            data-aos="fade-up"
            data-aos-delay="200"
        >
            <img
                src="{{ asset('assets/images/video-placeholder-02.png') }}"
                alt="Video placeholder"
                class="w-full h-full object-cover brightness-80 group-hover:brightness-[0.6] transition-all duration-300"
            />
            <div class="absolute inset-0 flex items-center justify-center">
                <div class="w-16 h-16 lg:w-20 lg:h-20 bg-white/30 backdrop-blur-sm rounded-full flex items-center justify-center group-hover:bg-white/50 transition-all duration-300">
                    <svg class="w-8 h-8 lg:w-10 lg:h-10 text-white ml-1" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M8 5v14l11-7z"></path>
                    </svg>
                </div>
            </div>
        </div>
    @endif
</section>
