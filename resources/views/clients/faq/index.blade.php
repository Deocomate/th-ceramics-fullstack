<x-client.layouts.main title="FAQ - Câu hỏi thường gặp" main-class="bg-background-secondary" :hide-newsletter="true">
    @push('styles')
    <style>
      @import url("https://fonts.googleapis.com/css2?family=Arima:wght@400;600;700&display=swap");
      @import url("https://fonts.googleapis.com/css2?family=Aref+Ruqaa:wght@400;700&display=swap");
      @import url("https://fonts.googleapis.com/css2?family=Archivo:wght@300;400;600;700&display=swap");

      .font-arima {
        font-family: 'Arima', cursive;
      }

      .font-aref {
        font-family: 'Aref Ruqaa', serif;
      }
    </style>
    @endpush

    <!-- FAQ Banner -->
    <section class="relative w-full h-[300px] md:h-[400px] flex items-end justify-center overflow-hidden pb-8 md:pb-12">
      <div class="absolute inset-0 z-0">
        @if(!empty($faqPage->banner_image))
          <img src="{{ asset('storage/' . $faqPage->banner_image) }}" alt="FAQ Banner" class="w-full h-full object-cover" />
        @else
          <img src="{{ asset('assets/images/faq-banner.png') }}" alt="FAQ Banner" class="w-full h-full object-cover" />
        @endif
      </div>
      <!-- Search Bar -->
      <div class="relative z-10 w-[90%] max-w-[735px]">
        <div class="relative group">
          <input type="text" placeholder="Nhập nội dung tìm kiếm..."
            class="w-full font-extralight h-12 md:h-16 px-6 pl-16 text:base lg:text-lg rounded-full bg-white/20 backdrop-blur-md border-[2px] border-white focus:bg-white/30 focus:outline-none transition-all text-white placeholder:text-white italic shadow-2xl" />
          <div class="absolute left-5 top-1/2 -translate-y-1/2 text-white">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
          </div>
        </div>
      </div>
    </section>

    <!-- FAQ Main Content -->
    <section
      class="w-[85%] max-w-[1320px] mx-auto pt-12 pb-4 md:py-20 grid grid-cols-1 lg:grid-cols-4 gap-8 md:gap-12 lg:gap-20">

      <!-- Sidebar -->
      <div class="lg:col-span-1 lg:sticky lg:top-28 h-fit">
        <div class="mb-8">
          <h1 class="text-4xl md:text-5xl font-aref text-primary mb-8 md:mb-12 font-bold">FAQ</h1>
          <hr class="border-t border-black/10 w-full" />
        </div>

        <nav class="flex flex-col gap-5">
          @foreach($faqsGrouped->keys() as $category)
            <a href="#{{ $category }}"
              class="text-xs md:text-sm font-medium text-primary uppercase hover:text-secondary transition-colors">
              {{ \App\Models\Faq::CATEGORIES[$category] ?? $category }}
            </a>
          @endforeach
        </nav>
      </div>

      <!-- Content Area -->
      <div class="lg:col-span-3">
        <!-- Breadcrumb -->
        <div class="flex items-center gap-2 text-xs md:text-sm text-primary/60 mb-8 font-medium">
          <a href="{{ route('client.home') }}" class="hover:text-secondary transition-colors">Trang chủ</a>
          <span>&gt;</span>
          <span class="text-primary/80">Câu hỏi thường gặp</span>
        </div>

        @foreach($faqsGrouped as $category => $faqs)
          @php $categoryTitle = \App\Models\Faq::CATEGORIES[$category] ?? $category; @endphp
          <div id="{{ $category }}" class="mt-12 md:mt-20 pt-4 scroll-mt-24">
            <div class="flex items-center gap-5 mb-8 pt-10">
              <div class="w-[2px] h-8 bg-secondary"></div>
              <h2 class="text-2xl md:text-[32px] font-semibold text-primary font-arima">{{ $categoryTitle }}</h2>
            </div>
            <div class="space-y-4 border-t border-black/5 pt-6">
              @foreach($faqs as $faq)
                <div class="accordion-item border-b border-black/5 py-6">
                  <button
                    class="accordion-button w-full flex justify-between items-center text-left gap-4 group focus:outline-none">
                    <span
                      class="text-base md:text-lg font-semibold text-primary/80 group-hover:text-secondary transition-colors font-arima">
                      {{ $faq->question }}
                    </span>
                    <span class="accordion-icon flex-shrink-0 text-secondary transition-transform duration-300">
                      <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                      </svg>
                    </span>
                  </button>
                  <div class="accordion-content overflow-hidden transition-all duration-300 max-h-0">
                    <p class="text-sm/7 md:text-base/8 text-primary text-justify pt-4">
                      {{ $faq->answer }}
                    </p>
                  </div>
                </div>
              @endforeach
            </div>
          </div>
        @endforeach

      </div>

    </section>

    <x-client.shared.faq-contact />

    @push('scripts')
    <script>
      document.addEventListener('DOMContentLoaded', () => {
        // Accordion Logic
        const accordionButtons = document.querySelectorAll('.accordion-button');

        accordionButtons.forEach(button => {
          button.addEventListener('click', () => {
            const item = button.parentElement;
            const content = item.querySelector('.accordion-content');
            const icon = item.querySelector('.accordion-icon');
            const isOpen = content.style.maxHeight && content.style.maxHeight !== '0px';

            if (isOpen) {
              content.style.maxHeight = '0px';
              icon.innerHTML = '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>';
            } else {
              content.style.maxHeight = content.scrollHeight + 'px';
              icon.innerHTML = '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path></svg>';
            }
          });
        });

        // Smooth Scroll Logic
        const navLinks = document.querySelectorAll('nav a[href^="#"]');
        navLinks.forEach(link => {
          link.addEventListener('click', (e) => {
            e.preventDefault();
            const targetId = link.getAttribute('href');
            const targetElement = document.querySelector(targetId);
            if (targetElement) {
              window.scrollTo({
                top: targetElement.offsetTop - 100,
                behavior: 'smooth'
              });
            }
          });
        });

        // Highlight Nav on Scroll
        const sections = document.querySelectorAll('div[id]');

        const updateActiveNav = (id) => {
          navLinks.forEach(link => {
            link.classList.remove('text-secondary');
            link.classList.add('text-primary');
            if (link.getAttribute('href') === `#${id}`) {
              link.classList.add('text-secondary');
              link.classList.remove('text-primary');
            }
          });
        };

        const handleScrollHighlight = () => {
          let currentId = '{{ $faqsGrouped->keys()->first() ?? 'san-pham' }}';
          const scrollPosition = window.scrollY + (window.innerHeight * 0.3);

          sections.forEach(section => {
            const sectionTop = section.offsetTop;
            if (scrollPosition >= sectionTop) {
              currentId = section.getAttribute('id');
            }
          });

          if (window.scrollY < 100) {
            currentId = '{{ $faqsGrouped->keys()->first() ?? 'san-pham' }}';
          }

          updateActiveNav(currentId);
        };

        window.addEventListener('scroll', handleScrollHighlight);
        window.addEventListener('load', handleScrollHighlight);
        handleScrollHighlight();
        setTimeout(handleScrollHighlight, 500);
      });
    </script>
    @endpush
</x-client.layouts.main>