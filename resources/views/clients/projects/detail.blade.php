<x-client.layouts.main title="Chi tiết dự án" data-page="project-detail-2" main-class="bg-[#F5EDE8] overflow-x-hidden">

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/glightbox/dist/css/glightbox.min.css" />
@endpush

<!-- ======================= HERO BANNER ======================= -->
<section class="relative w-full h-[300px] md:h-[380px] flex items-center justify-center overflow-hidden">
  <!-- Background image -->
  <div class="absolute inset-0 z-0">
    <img src="{{ asset('storage/' . ($project->images[0] ?? 'assets/images/factory-01.jpg')) }}"
         alt="{{ $project->ten_du_an }}" class="w-full h-full object-cover"
         onerror="this.src='{{ asset('assets/images/factory-01.jpg') }}'" />
    <div class="absolute inset-0 bg-primary/60"></div>
  </div>

  <!-- Text -->
  <div class="relative z-10 text-center text-white px-4 pt-0 md:pt-12" data-aos="fade-up">
    <p class="text-[11px] md:text-xs font-bold uppercase tracking-widest text-white/70 mb-3">
      Dự Án Nổi Bật
    </p>
    <h1 class="text-2xl md:text-[36px] lg:text-[48px] font-arima font-medium leading-tight drop-shadow-lg mb-4">
      {{ $project->ten_du_an }}
    </h1>
    <!-- Breadcrumb -->
    <p class="text-[11px] md:text-sm text-white/80 drop-shadow-md">
      <a href="{{ route('client.home') }}" class="hover:text-secondary transition-colors">Trang chủ</a>
      <svg class="w-2.5 h-2.5 inline-block mx-2 fill-current opacity-70" viewBox="0 0 35 35"
        xmlns="http://www.w3.org/2000/svg">
        <path
          d="M11.5797 31.4214C11.1695 31.0111 10.9391 30.4548 10.9391 29.8747C10.9391 29.2946 11.1695 28.7383 11.5797 28.3281L22.4078 17.5L11.5797 6.67184C11.1937 6.25726 10.9836 5.70915 10.9936 5.14283C11.0036 4.5765 11.2328 4.03612 11.6331 3.63539C12.0334 3.23465 12.5735 3.0048 13.1399 2.99421C13.7062 2.98361 14.2545 3.1931 14.6695 3.57858L27.046 15.9516C27.4561 16.3618 27.6865 16.9182 27.6865 17.4983C27.6865 18.0783 27.4561 18.6347 27.046 19.0449L14.6729 31.4214C14.2627 31.8315 13.7064 32.0619 13.1263 32.0619C12.5462 32.0619 11.9899 31.8315 11.5797 31.4214Z"
          fill="currentColor" />
      </svg>
      <a href="{{ route('client.projects.index') }}" class="hover:text-secondary transition-colors">Dự án</a>
      <svg class="w-2.5 h-2.5 inline-block mx-2 fill-current opacity-70" viewBox="0 0 35 35"
        xmlns="http://www.w3.org/2000/svg">
        <path
          d="M11.5797 31.4214C11.1695 31.0111 10.9391 30.4548 10.9391 29.8747C10.9391 29.2946 11.1695 28.7383 11.5797 28.3281L22.4078 17.5L11.5797 6.67184C11.1937 6.25726 10.9836 5.70915 10.9936 5.14283C11.0036 4.5765 11.2328 4.03612 11.6331 3.63539C12.0334 3.23465 12.5735 3.0048 13.1399 2.99421C13.7062 2.98361 14.2545 3.1931 14.6695 3.57858L27.046 15.9516C27.4561 16.3618 27.6865 16.9182 27.6865 17.4983C27.6865 18.0783 27.4561 18.6347 27.046 19.0449L14.6729 31.4214C14.2627 31.8315 13.7064 32.0619 13.1263 32.0619C12.5462 32.0619 11.9899 31.8315 11.5797 31.4214Z"
          fill="currentColor" />
      </svg>
      <span class="text-white/60">{{ $project->ten_du_an }}</span>
    </p>
  </div>
</section>

<!-- ======================= PROJECT META ======================= -->
<section class="bg-white border-b border-neutral-200">
  <div class="w-[85%] max-w-[1320px] mx-auto py-6 flex flex-wrap gap-x-10 gap-y-3 items-center" data-aos="fade-up">
    <div class="flex items-center gap-3">
      <span class="text-[11px] font-bold text-primary/50 uppercase tracking-widest">Địa điểm</span>
      <span class="w-px h-4 bg-primary/20"></span>
      <span class="text-sm font-arima text-primary font-medium">{{ $project->dia_diem }}</span>
    </div>
    @if($project->nam)
    <div class="flex items-center gap-3">
      <span class="text-[11px] font-bold text-primary/50 uppercase tracking-widest">Năm</span>
      <span class="w-px h-4 bg-primary/20"></span>
      <span class="text-sm font-arima text-primary font-medium">{{ $project->nam }}</span>
    </div>
    @endif
    <div class="flex items-center gap-3">
      <span class="text-[11px] font-bold text-primary/50 uppercase tracking-widest">Sản phẩm</span>
      <span class="w-px h-4 bg-primary/20"></span>
      <span class="text-sm font-arima text-primary font-medium">{{ $project->san_pham }}</span>
    </div>
  </div>
</section>

<!-- ======================= PHOTO GALLERY GRID ======================= -->
<section class="py-14 lg:py-20 bg-[#F5EDE8]">
  <div class="w-[90%] max-w-[1400px] mx-auto">

    <!-- Section heading -->
    <div class="mb-10 lg:mb-14 text-center" data-aos="fade-up">
      <p class="text-[11px] font-bold text-primary/50 uppercase tracking-widest mb-3">Bộ sưu tập hình ảnh</p>
      <h2 class="text-[26px] md:text-[34px] font-arima font-medium text-primary">Không gian đa chiều</h2>
    </div>

    @php $images = $project->images ?? []; $count = count($images); @endphp

    @if($count >= 3)
    <!-- Row 1: 3 cols (2+1) -->
    <div class="hidden md:grid grid-cols-3 gap-3 mb-3" data-aos="fade-up">
      <a href="{{ asset('storage/' . $images[0]) }}" class="glightbox col-span-2 aspect-[16/9] overflow-hidden block group"
        data-gallery="project-gallery">
        <img src="{{ asset('storage/' . $images[0]) }}" alt="{{ $project->ten_du_an }} — ảnh 1"
          onerror="this.onerror=null;this.src='{{ asset('assets/images/factory-01.jpg') }}'"
          class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105" />
      </a>
      <a href="{{ asset('storage/' . $images[1]) }}" class="glightbox aspect-[16/9] overflow-hidden block group"
        data-gallery="project-gallery">
        <img src="{{ asset('storage/' . $images[1]) }}" alt="{{ $project->ten_du_an }} — ảnh 2"
          onerror="this.onerror=null;this.src='{{ asset('assets/images/factory-01.jpg') }}'"
          class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105" />
      </a>
    </div>

    @if($count >= 7)
    <!-- Row 2: 4 equal cols (images[2] through images[5]) -->
    <div class="hidden md:grid grid-cols-4 gap-3 mb-3" data-aos="fade-up" data-aos-delay="80">
      @for($i = 2; $i <= 5; $i++)
      <a href="{{ asset('storage/' . $images[$i]) }}" class="glightbox aspect-square overflow-hidden block group"
        data-gallery="project-gallery">
        <img src="{{ asset('storage/' . $images[$i]) }}" alt="{{ $project->ten_du_an }} — ảnh {{ $i+1 }}"
          onerror="this.onerror=null;this.src='{{ asset('assets/images/factory-01.jpg') }}'"
          class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105" />
      </a>
      @endfor
    </div>

    @if($count >= 8)
    <!-- Row 3: 3 cols reversed (1+2) -->
    <div class="hidden md:grid grid-cols-3 gap-3" data-aos="fade-up" data-aos-delay="160">
      <a href="{{ asset('storage/' . $images[6]) }}" class="glightbox aspect-[16/9] overflow-hidden block group"
        data-gallery="project-gallery">
        <img src="{{ asset('storage/' . $images[6]) }}" alt="{{ $project->ten_du_an }} — ảnh 7"
          onerror="this.onerror=null;this.src='{{ asset('assets/images/factory-01.jpg') }}'"
          class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105" />
      </a>
      <a href="{{ asset('storage/' . ($images[7] ?? $images[0])) }}" class="glightbox col-span-2 aspect-[16/9] overflow-hidden block group"
        data-gallery="project-gallery">
        <img src="{{ asset('storage/' . ($images[7] ?? $images[0])) }}" alt="{{ $project->ten_du_an }} — ảnh 8"
          onerror="this.onerror=null;this.src='{{ asset('assets/images/factory-01.jpg') }}'"
          class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105" />
      </a>
    </div>
    @endif
    @else
    <!-- 3-6 images: show remaining in 3-col grid after Row 1 -->
    <div class="hidden md:grid grid-cols-3 gap-3" data-aos="fade-up" data-aos-delay="80">
      @for($i = 2; $i < $count; $i++)
      <a href="{{ asset('storage/' . $images[$i]) }}" class="glightbox aspect-[16/9] overflow-hidden block group"
        data-gallery="project-gallery">
        <img src="{{ asset('storage/' . $images[$i]) }}" alt="{{ $project->ten_du_an }} — ảnh {{ $i+1 }}"
          onerror="this.onerror=null;this.src='{{ asset('assets/images/factory-01.jpg') }}'"
          class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105" />
      </a>
      @endfor
    </div>
    @endif

    @else
    <!-- Simple grid for 1-2 images -->
    <div class="hidden md:grid grid-cols-2 gap-3" data-aos="fade-up">
      @foreach($images as $img)
      <a href="{{ asset('storage/' . $img) }}" class="glightbox aspect-[16/9] overflow-hidden block group"
        data-gallery="project-gallery">
        <img src="{{ asset('storage/' . $img) }}" alt="{{ $project->ten_du_an }}"
          onerror="this.onerror=null;this.src='{{ asset('assets/images/factory-01.jpg') }}'"
          class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105" />
      </a>
      @endforeach
    </div>
    @endif

    <!-- Mobile: Swiper carousel -->
    <div class="md:hidden" data-aos="fade-up">
      <div class="swiper detail2-gallery-swiper relative">
        <div class="swiper-wrapper pb-10">
          @foreach($images as $img)
          <div class="swiper-slide h-[260px]">
            <a href="{{ asset('storage/' . $img) }}" class="glightbox h-full w-full block"
              data-gallery="project-gallery-mobile">
              <img src="{{ asset('storage/' . $img) }}" alt="{{ $project->ten_du_an }}"
                onerror="this.onerror=null;this.src='{{ asset('assets/images/factory-01.jpg') }}'"
                class="w-full h-full object-cover" />
            </a>
          </div>
          @endforeach
        </div>
        <div class="swiper-pagination detail2-pagination flex justify-center !bottom-0 !mt-2"></div>
      </div>
    </div>

  </div>
</section>

<!-- ======================= RELATED PROJECTS ======================= -->
@if($relatedProjects->isNotEmpty())
<section class="py-14 lg:py-20 bg-white">
  <div class="w-[90%] max-w-[1400px] mx-auto">
    <h2 class="text-[26px] md:text-[34px] font-arima font-medium text-primary text-center mb-10">Dự án tương tự</h2>
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
      @foreach($relatedProjects as $related)
      <a href="{{ route('client.projects.detail', $related->slug) }}"
         class="group block overflow-hidden shadow-md hover:shadow-lg transition-shadow bg-white">
        <div class="aspect-[4/3] overflow-hidden">
          <img src="{{ asset('storage/' . ($related->images[0] ?? 'assets/images/factory-01.jpg')) }}"
               alt="{{ $related->ten_du_an }}"
               onerror="this.src='{{ asset('assets/images/factory-01.jpg') }}'"
               class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700" />
        </div>
        <div class="p-3 text-center">
          <h3 class="text-sm font-archivo font-extrabold text-primary group-hover:text-secondary transition-colors">
            {{ \Illuminate\Support\Str::upper($related->ten_du_an) }}
          </h3>
        </div>
      </a>
      @endforeach
    </div>
  </div>
</section>
@endif

<!-- ======================= CTA ======================= -->
<section class="py-16 lg:py-24 bg-primary text-white">
  <div class="w-[85%] max-w-[1000px] mx-auto text-center">
    <h2 class="text-[30px] lg:text-[40px] font-arima font-medium mb-10 lg:mb-16" data-aos="fade-up">
      Bạn có dự án tương tự?
    </h2>
    <p class="text-[15px] opacity-90 mb-10 lg:mb-16 max-w-2xl mx-auto font-light leading-relaxed" data-aos="fade-up"
      data-aos-delay="100">
      Hãy liên hệ với chúng tôi để tư vấn về các giải pháp gốm sứ cao cấp
      cho công trình của bạn.
    </p>
    <div class="flex flex-col md:flex-row gap-4 justify-center" data-aos="fade-up" data-aos-delay="200">
      <a href="{{ route('client.contact') }}"
        class="px-10 py-4 bg-white text-primary font-bold rounded-lg hover:bg-neutral-1 transition-all">
        Liên Hệ Tư Vấn
      </a>
      <a href="{{ route('client.customer-service.show', 'quy-trinh-dat-hang') }}"
        class="px-10 py-4 border-2 border-white text-white font-bold rounded-lg hover:bg-white hover:text-primary transition-all">
        Xem Quy Trình
      </a>
    </div>
  </div>
</section>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/glightbox/dist/js/glightbox.min.js"></script>
<script>
  if (typeof GLightbox !== "undefined") {
    document.querySelectorAll(".glightbox").forEach((anchor) => {
      const image = anchor.querySelector("img");
      if (image) {
        anchor.setAttribute("href", image.currentSrc || image.src);
      }
    });
    GLightbox({
      touchNavigation: true,
      loop: true,
      autoplayVideos: true,
    });
  }

  if (typeof Swiper !== "undefined") {
    new Swiper(".detail2-gallery-swiper", {
      slidesPerView: 1.15,
      spaceBetween: 12,
      pagination: {
        el: ".detail2-pagination",
        clickable: true,
      },
      breakpoints: {
        480: {
          slidesPerView: 1.6,
          spaceBetween: 16,
        },
      },
    });
  }
</script>
@endpush

</x-client.layouts.main>
