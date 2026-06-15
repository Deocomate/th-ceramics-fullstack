<x-client.layouts.main title="FAQ - Câu hỏi thường gặp" main-class="bg-background-secondary" :hide-newsletter="true">
    @push('styles')
        <style>
            @import url("https://fonts.googleapis.com/css2?family=Arima:wght@400;500;600;700&display=swap");
            @import url("https://fonts.googleapis.com/css2?family=Aref+Ruqaa:wght@400;700&display=swap");
            @import url("https://fonts.googleapis.com/css2?family=Archivo:wght@200;300;400;500;600;700&display=swap");

            .font-arima {
                font-family: 'Arima', cursive;
            }

            .font-aref {
                font-family: 'Aref Ruqaa', serif;
            }

            /* Đường kẻ phân cách rõ nét với độ tương phản cao trên nền màu be/kem */
            .faq-category-container {
                border-top: 1px solid rgba(46, 47, 42, 0.15) !important;
            }

            .faq-accordion-item {
                border-bottom: 1px solid rgba(46, 47, 42, 0.15) !important;
            }

            /* Quy tắc CSS khoảng cách cho giao diện Máy tính & Máy tính bảng */
            @media (min-width: 768px) {
                .faq-category-container {
                    margin-top: 0 !important;
                    padding-top: 0 !important;
                }

                .faq-accordion-item {
                    padding-top: 0 !important;
                    padding-bottom: 0 !important;
                    margin-top: 0 !important;
                    margin-bottom: 0 !important;
                }

                .faq-accordion-button {
                    padding-top: 10px !important;
                    padding-bottom: 10px !important;
                    min-height: 40px !important;
                }

                .faq-accordion-content-inner {
                    padding-top: 10px !important;
                    padding-bottom: 20px !important;
                }
            }
        </style>
    @endpush

    <!-- FAQ Banner -->
    <section class="relative w-full h-[300px] md:h-[400px] flex items-end justify-center overflow-hidden pb-8 md:pb-12">
        <div class="absolute inset-0 z-0">
            @if (!empty($faqPage->banner_image))
                <img src="{{ asset('storage/' . $faqPage->banner_image) }}" alt="FAQ Banner"
                    class="w-full h-full object-cover" />
            @else
                <img src="{{ asset('assets/images/faq-banner.png') }}" alt="FAQ Banner"
                    class="w-full h-full object-cover" />
            @endif
        </div>
        <!-- Search Bar -->
        <div class="relative z-10 w-[90%] max-w-[735px]">
            <div class="relative group">
                <input type="text" placeholder="Nhập nội dung tìm kiếm..."
                    class="w-full font-extralight h-12 md:h-16 px-6 pl-16 text-base lg:text-lg rounded-full bg-white/20 backdrop-blur-md border-[2px] border-white focus:bg-white/30 focus:outline-none transition-all text-white placeholder:text-white italic shadow-2xl" />
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
                <h1 class="text-[48px] leading-10 font-aref text-primary mb-8 md:mb-12 font-bold">FAQ</h1>
                <hr class="border-t border-black/10 w-full" />
            </div>
            <nav class="flex flex-col gap-5">
                @foreach ($faqsGrouped->keys() as $category)
                    <a href="#{{ $category }}"
                        class="text-sm font-archivo font-medium text-primary uppercase leading-10 hover:text-secondary transition-colors">
                        {{ \App\Models\Faq::CATEGORIES[$category] ?? $category }}
                    </a>
                @endforeach
            </nav>
        </div>

        <!-- Content Area -->
        <div class="lg:col-span-3">
            <!-- Breadcrumb -->
            <div class="flex items-center gap-2 text-sm font-archivo font-normal text-primary leading-10 mb-8 md:mb-0">
                <a href="{{ route('client.home') }}" class="hover:text-secondary transition-colors">Trang chủ</a>
                <span>&gt;</span>
                <span>Câu hỏi thường gặp</span>
            </div>

            @foreach ($faqsGrouped as $category => $faqs)
                @php $categoryTitle = \App\Models\Faq::CATEGORIES[$category] ?? $category; @endphp
                <div id="{{ $category }}" class="mt-12 md:mt-0 scroll-mt-24">
                    <div class="flex items-center gap-5 lg:gap-[12px] mb-8 pt-10">
                        <div class="w-[2px] h-8 bg-secondary"></div>
                        <h2 class="text-[32px] leading-10 font-semibold text-primary font-arima">
                            {{ $categoryTitle }}</h2>
                    </div>
                    <div class="space-y-4 md:space-y-0 faq-category-container pt-6 md:pt-0">
                        @foreach ($faqs as $faq)
                            <div class="accordion-item faq-accordion-item py-6 md:py-0">
                                <button
                                    class="accordion-button w-full flex justify-between items-center text-left gap-4 group focus:outline-none py-2 md:py-0 faq-accordion-button transition-all">
                                    <span
                                        class="text-base font-medium leading-10 text-primary group-hover:text-secondary transition-colors font-arima">
                                        {{ $faq->question }}
                                    </span>
                                    <span
                                        class="accordion-icon flex-shrink-0 text-secondary transition-transform duration-300">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                            stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4">
                                            </path>
                                        </svg>
                                    </span>
                                </button>
                                <div class="accordion-content overflow-hidden transition-all duration-300 max-h-0">
                                    <div class="pt-4 pb-6 faq-accordion-content-inner">
                                        <p
                                            class="text-sm font-extralight leading-[25px] text-primary text-justify font-archivo">
                                            {{ $faq->answer }}
                                        </p>
                                    </div>
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
                            button.style.paddingBottom =
                            ''; // Khôi phục padding gốc của CSS (10px) khi đóng
                            icon.innerHTML =
                                '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"></path></svg>';
                        } else {
                            content.style.maxHeight = content.scrollHeight + 'px';
                            if (window.innerWidth >= 768) {
                                button.style.setProperty('padding-bottom', '0px',
                                'important'); // Loại bỏ padding-bottom của button khi mở trên máy tính/máy tính bảng
                            }
                            icon.innerHTML =
                                '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M20 12H4"></path></svg>';
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
