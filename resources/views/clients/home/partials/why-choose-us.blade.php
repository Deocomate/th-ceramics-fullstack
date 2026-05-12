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
      @if($trangChu?->video) data-video-url="{{ $trangChu->video }}" role="button" tabindex="0" @endif
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

      (function () {
        const videoTrigger = document.querySelector("[data-video-url]");
        const videoModal = document.getElementById("youtube-video-modal");
        const iframe = document.getElementById("youtube-iframe");
        const closeBtn = document.getElementById("close-video-modal");

        if (!videoTrigger || !videoModal || !iframe || !closeBtn) return;

        const getYoutubeId = (url) => {
          if (!url) return null;

          try {
            const parsedUrl = new URL(url, window.location.origin);
            const host = parsedUrl.hostname.replace(/^www\./, "");

            if (host === "youtu.be") {
              const id = parsedUrl.pathname.split("/").filter(Boolean)[0];
              return id && id.length === 11 ? id : null;
            }

            if (host === "youtube.com" || host === "m.youtube.com") {
              const watchId = parsedUrl.searchParams.get("v");
              if (watchId && watchId.length === 11) return watchId;

              const pathParts = parsedUrl.pathname.split("/").filter(Boolean);
              const embedIndex = pathParts.findIndex((part) =>
                ["embed", "shorts", "v"].includes(part),
              );
              const embedId = embedIndex >= 0 ? pathParts[embedIndex + 1] : null;
              return embedId && embedId.length === 11 ? embedId : null;
            }
          } catch (error) {
            const match = url.match(
              /^.*(youtu\.be\/|v\/|u\/\w\/|embed\/|shorts\/|watch\?v=|&v=)([^#&?]*).*/,
            );
            return match && match[2].length === 11 ? match[2] : null;
          }

          return null;
        };

        const openModal = () => {
          const videoId = getYoutubeId(videoTrigger.getAttribute("data-video-url"));
          if (!videoId) return;

          iframe.src = `https://www.youtube.com/embed/${videoId}?autoplay=1&rel=0`;
          videoModal.classList.remove("hidden");
          videoModal.classList.add("flex");
          document.body.style.overflow = "hidden";

          window.requestAnimationFrame(() => {
            videoModal.classList.remove("opacity-0");
          });
        };

        const closeModal = () => {
          videoModal.classList.add("opacity-0");

          window.setTimeout(() => {
            videoModal.classList.add("hidden");
            videoModal.classList.remove("flex");
            iframe.src = "";
            document.body.style.overflow = "";
          }, 300);
        };

        videoTrigger.addEventListener("click", openModal);
        videoTrigger.addEventListener("keydown", (event) => {
          if (event.key === "Enter" || event.key === " ") {
            event.preventDefault();
            openModal();
          }
        });
        closeBtn.addEventListener("click", closeModal);

        videoModal.addEventListener("click", (event) => {
          if (event.target === videoModal) {
            closeModal();
          }
        });

        document.addEventListener("keydown", (event) => {
          if (event.key === "Escape" && !videoModal.classList.contains("hidden")) {
            closeModal();
          }
        });
      })();
    </script>
    @endpush
  </div>

  <div
    id="youtube-video-modal"
    class="fixed inset-0 z-[100] hidden items-center justify-center bg-black/90 p-4 opacity-0 transition-opacity duration-300"
    aria-hidden="true"
  >
    <button
      id="close-video-modal"
      type="button"
      class="absolute right-4 top-4 z-10 p-2 text-white transition-colors hover:text-secondary"
      aria-label="Đóng video"
    >
      <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
      </svg>
    </button>
    <div class="relative w-full max-w-5xl aspect-video overflow-hidden rounded-lg shadow-2xl">
      <iframe
        id="youtube-iframe"
        class="w-full h-full"
        src=""
        title="TH Ceramics video"
        frameborder="0"
        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
        allowfullscreen
      ></iframe>
    </div>
  </div>
</section>
