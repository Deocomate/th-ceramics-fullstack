@props([
    'config' => null,
])
<!-- Video Section -->
<section
  class="w-full max-w-[1920px] mx-auto md:pb-16 animate-fade-in-up gach-hoa-video-section"
>
  <!-- Section Title -->
  <div class="text-center mb-[25px] md:mb-16" data-aos="fade-up">
    <h2
      class="text-[20px] md:text-3xl font-semibold text-secondary uppercase gach-hoa-video-title"
    >
      Hành trình chế tác
    </h2>
  </div>
  <div class="flex">
    @php
      $videoThumbnail = $config->video_thumbnail ?? null;
      $videoThumbnailUrl = !empty($videoThumbnail)
        ? (\Illuminate\Support\Str::startsWith($videoThumbnail, 'assets/') ? asset($videoThumbnail) : asset('storage/' . $videoThumbnail))
        : asset('assets/images/gach-hoa-value.png');
      $videoUrl = $config->video_url ?? null;
      $youtubeId = !empty($videoUrl) ? \App\Support\ProductGallery::extractYoutubeId($videoUrl) : null;
      $videoEmbedUrl = $youtubeId ? \App\Support\ProductGallery::embedUrl($youtubeId) : null;
    @endphp
    <a
      href="{{ $videoThumbnailUrl }}"
      class="glightbox w-1/2 pl-[5%] py-[5%] pr-[5%] lg:pr-[5%]"
      data-aos="fade-right"
      data-gallery="hanh-trinh"
    >
      <img
        src="{{ $videoThumbnailUrl }}"
        alt="Hành trình chế tác 1"
        class="w-full h-full object-cover brightness-80 hover:brightness-100 transition-all duration-300"
      />
    </a>
    @if(!empty($youtubeId) && !empty($videoEmbedUrl))
    <div
      class="relative w-full aspect-[4/3] overflow-hidden bg-black"
      data-aos="fade-left"
      data-aos-delay="200"
      data-inline-video-shell
      data-youtube-id="{{ $youtubeId }}"
      data-embed-src="{{ $videoEmbedUrl }}"
    >
      <button
        type="button"
        data-inline-video-play
        class="absolute inset-0 z-10 w-full h-full cursor-pointer group"
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
      class="relative w-full aspect-[4/3] overflow-hidden cursor-pointer group"
      data-aos="fade-left"
      data-aos-delay="200"
    >
      <img
        src="{{ asset('assets/images/video-placeholder-02.png') }}"
        alt="Video placeholder"
        class="w-full h-full object-cover brightness-80 group-hover:brightness-[0.6] transition-all duration-300"
      />
      <div class="absolute inset-0 flex items-center justify-center">
        <div
          class="w-16 h-16 lg:w-20 lg:h-20 bg-white/30 backdrop-blur-sm rounded-full flex items-center justify-center group-hover:bg-white/50 transition-all duration-300"
        >
          <svg
            class="w-8 h-8 lg:w-10 lg:h-10 text-white ml-1"
            fill="currentColor"
            viewBox="0 0 24 24"
          >
            <path d="M8 5v14l11-7z"></path>
          </svg>
        </div>
      </div>
    </div>
    @endif
  </div>
</section>
