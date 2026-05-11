<section class="bg-primary text-white py-8 lg:py-20">
  <div class="w-[85%] max-w-[1320px] mx-auto">
    <h2
      class="text-secondary text-center lg:text-left text-[20px] leading-[32px] lg:text-4xl font-bold uppercase mb-8 lg:mb-12"
      data-aos="fade-up"
    >
      Tại sao lựa chọn TH Ceramics
    </h2>

    <div
      class="relative w-full aspect-video mb-8 lg:mb-12 rounded-lg overflow-hidden cursor-pointer group"
      data-aos="fade-up"
      data-aos-delay="200"
      @if($trangChu?->video) data-video-url="{{ $trangChu->video }}" @endif
    >
      <img
        src="{{ asset('assets/images/video-placeholder.jpg') }}"
        alt="Video placeholder"
        class="w-full h-full object-cover brightness-50 group-hover:brightness-[0.4] transition-all duration-300"
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

    @if($trangChu && !empty($trangChu->nhung_con_so))
    <div
      class="grid grid-cols-5 gap-1 lg:gap-8"
      data-aos="fade-up"
      data-aos-delay="400"
    >
      @foreach($trangChu->nhung_con_so as $conSo)
        @php
          preg_match('/^(\d+)(.*)$/', trim($conSo['head']), $matches);
          $target = $matches[1] ?? 0;
          $suffix = $matches[2] ?? '';
        @endphp
      <div class="flex flex-col items-center lg:items-start">
        <div
          class="text-white lg:text-neutral-1 text-[16px] leading-[40px] lg:text-[42px] lg:leading-tight font-bold mb-0 lg:mb-3 text-center lg:text-left"
        >
          <span class="stat-number"
                data-target="{{ $target }}"
                @if($suffix !== '') data-suffix="{{ $suffix }}" @endif>0{{ $suffix }}</span>
        </div>
        <p
          class="text-white lg:text-neutral-1 font-light lg:font-semibold text-[11px] leading-[15px] lg:text-xl text-center lg:text-left"
        >
          {!! nl2br(e($conSo['body'])) !!}
        </p>
      </div>
      @endforeach
    </div>
    @endif

    @push('scripts')
    <script>
      (function () {
        const counters = document.querySelectorAll(".stat-number");
        if (!counters.length) return;

        const timers = new WeakMap();

        const reset = (el) => {
          if (timers.has(el)) {
            clearInterval(timers.get(el));
            timers.delete(el);
          }
          const suffix = el.getAttribute("data-suffix") || "";
          el.textContent = "0" + suffix;
        };

        const animate = (el) => {
          if (timers.has(el)) {
            clearInterval(timers.get(el));
            timers.delete(el);
          }

          const target = parseInt(el.getAttribute("data-target"), 10);
          const suffix = el.getAttribute("data-suffix") || "";
          const duration = 3000;
          const frameDuration = 1000 / 60;
          const totalFrames = Math.round(duration / frameDuration);
          let frame = 0;

          const easeOut = (t) => 1 - Math.pow(1 - t, 4);

          const id = setInterval(() => {
            frame++;
            const progress = easeOut(frame / totalFrames);
            const current = Math.round(progress * target);
            el.textContent = current + suffix;
            if (frame >= totalFrames) {
              el.textContent = target + suffix;
              clearInterval(id);
              timers.delete(el);
            }
          }, frameDuration);

          timers.set(el, id);
        };

        const observer = new IntersectionObserver(
          (entries) => {
            entries.forEach((entry) => {
              if (entry.isIntersecting) {
                animate(entry.target);
              } else {
                reset(entry.target);
              }
            });
          },
          { threshold: 0.4 },
        );

        counters.forEach((el) => observer.observe(el));
      })();
    </script>
    @endpush
  </div>
</section>
