<!-- Ưu điểm vượt trội Section -->
<section class="md:pb-16 bg-background-secondary overflow-hidden md:pt-4">
  <div class="w-[85%] max-w-[1320px] mx-auto">
    <h2
      class="text-secondary text-[20px] md:text-3xl font-semibold uppercase text-center md:mb-32 mt-1"
      data-aos="fade-up"
    >
      Ưu điểm vượt trội
    </h2>

    @php
      $galleryImages = $config->anh->pluck('image')->toArray();
      $cardImages = array_pad(array_slice($galleryImages, 0, 4), 4, null);
    @endphp

    <!-- Mobile Swiper (Chỉ hiển thị trên mobile) -->
    <div class="md:hidden">
      <div class="swiper advantage-swiper">
        <div class="swiper-wrapper">
          @foreach($cardImages as $galleryImage)
          <div class="swiper-slide h-auto">
            @include('clients.products.den-gom-su.partials.advantage-card', ['bgImage' => $galleryImage])
          </div>
          @endforeach
        </div>
      </div>
      <!-- Dots -->
      <div
        class="advantage-pagination flex justify-center items-center gap-[3px] w-full mt-6"
      ></div>
    </div>

    <!-- Desktop Grid (ẩn trên mobile, hiển thị trên md) -->
    <div
      class="hidden md:grid md:grid-cols-2 lg:grid-cols-4 gap-8 lg:gap-6 items-start"
    >
      @foreach($cardImages as $galleryImage)
      @include('clients.products.den-gom-su.partials.advantage-card', ['bgImage' => $galleryImage])
      @endforeach
    </div>
  </div>
</section>

@push('scripts')
<script>
  document.addEventListener("DOMContentLoaded", () => {
    new Swiper(".advantage-swiper", {
      slidesPerView: 1,
      spaceBetween: 20,
      loop: false,
      pagination: {
        el: ".advantage-pagination",
        clickable: true,
        renderBullet: function (index, className) {
          return (
            '<span class="' +
            className +
            ' rounded-full bg-secondary/30 transition-all cursor-pointer inline-block"></span>'
          );
        },
      },
    });
  });
</script>
@endpush
