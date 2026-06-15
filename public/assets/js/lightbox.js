let lightboxSwiperInstance = null;

function updateCounterAndCaption(swiperInstance, images) {
    const counter = document.getElementById('global-lightbox-counter');
    const caption = document.getElementById('global-lightbox-caption');
    const index = swiperInstance.activeIndex;
    const total = images.length;

    if (counter) {
        counter.textContent = `${index + 1} / ${total}`;
    }
    if (caption) {
        const currentImage = images[index];
        caption.textContent = currentImage ? currentImage.desc : '';
        if (currentImage && currentImage.desc) {
            caption.parentElement.classList.remove('hidden');
        } else {
            caption.parentElement.classList.add('hidden');
        }
    }
}

function openLightbox(swiperEl, clickedImg) {
    const lightbox = document.getElementById('global-lightbox');
    if (!lightbox) return;

    // 1. Gather slides from the target swiper (excluding Swiper duplicates)
    const slides = Array.from(swiperEl.querySelectorAll('.swiper-slide:not(.swiper-slide-duplicate)'));
    const images = slides.map(slide => {
        const img = slide.querySelector('img');
        const textEl = slide.querySelector('p, .caption, [data-lightbox-desc]');
        return {
            src: img ? (img.getAttribute('src') || img.src) : '',
            alt: img ? (img.getAttribute('alt') || '') : '',
            desc: textEl ? textEl.textContent.trim() : (img ? (img.getAttribute('alt') || '') : '')
        };
    }).filter(item => item.src);

    if (images.length === 0) return;

    // 2. Determine index of the clicked slide
    const clickedSlide = clickedImg.closest('.swiper-slide');
    let clickedIndex = 0;
    
    if (clickedSlide && clickedSlide.hasAttribute('data-swiper-slide-index')) {
        clickedIndex = parseInt(clickedSlide.getAttribute('data-swiper-slide-index'), 10);
    } else if (clickedSlide) {
        clickedIndex = slides.indexOf(clickedSlide);
    }
    
    if (clickedIndex < 0 || clickedIndex >= images.length) {
        const clickedSrc = clickedImg.getAttribute('src') || clickedImg.src;
        clickedIndex = images.findIndex(item => item.src === clickedSrc);
        if (clickedIndex < 0) clickedIndex = 0;
    }

    // 3. Inject slide markup into the global lightbox Swiper wrapper
    const wrapper = lightbox.querySelector('.global-lightbox-swiper .swiper-wrapper');
    if (wrapper) {
        wrapper.innerHTML = images.map(img => `
            <div class="swiper-slide flex items-center justify-center p-4 md:p-8 select-none h-full w-full">
                <div class="relative max-w-full max-h-[72vh] md:max-h-[82vh] flex items-center justify-center">
                    <img src="${img.src}" alt="${img.alt}" class="max-w-[95vw] max-h-[70vh] md:max-h-[80vh] object-contain shadow-2xl rounded border border-white/10 bg-black/40">
                </div>
            </div>
        `).join('');
    }

    // 4. Reveal the lightbox
    lightbox.classList.remove('opacity-0', 'pointer-events-none');
    lightbox.classList.add('opacity-100', 'pointer-events-auto');
    document.body.classList.add('overflow-hidden');

    // 5. Initialize or update Swiper instance
    if (lightboxSwiperInstance) {
        lightboxSwiperInstance.destroy(true, true);
        lightboxSwiperInstance = null;
    }

    if (typeof window.Swiper !== 'undefined') {
        lightboxSwiperInstance = new window.Swiper('.global-lightbox-swiper', {
            slidesPerView: 1,
            spaceBetween: 24,
            centeredSlides: true,
            grabCursor: true,
            navigation: {
                nextEl: '.global-lightbox-next',
                prevEl: '.global-lightbox-prev',
            },
            on: {
                init() {
                    updateCounterAndCaption(this, images);
                },
                slideChange() {
                    updateCounterAndCaption(this, images);
                }
            }
        });
        lightboxSwiperInstance.slideTo(clickedIndex, 0);
    }
}

function closeLightbox() {
    const lightbox = document.getElementById('global-lightbox');
    if (!lightbox) return;

    lightbox.classList.add('opacity-0', 'pointer-events-none');
    lightbox.classList.remove('opacity-100', 'pointer-events-auto');
    document.body.classList.remove('overflow-hidden');

    // Wait for the opacity fade out animation to finish before destroying Swiper
    setTimeout(() => {
        if (lightboxSwiperInstance) {
            lightboxSwiperInstance.destroy(true, true);
            lightboxSwiperInstance = null;
        }
        const wrapper = lightbox.querySelector('.global-lightbox-swiper .swiper-wrapper');
        if (wrapper) wrapper.innerHTML = '';
    }, 300);
}

const initLightbox = () => {
    // 1. Delegate click events on Swiper slides
    document.addEventListener('click', (event) => {
        const slide = event.target.closest('.swiper-slide');
        if (!slide) return;

        // Skip logo/partner sliders
        const swiperEl = slide.closest('.swiper');
        if (!swiperEl) return;
        if (swiperEl.classList.contains('partner-swiper')) return;

        const img = slide.querySelector('img');
        if (!img) return;

        // Handle link checks: skip actual navigation links
        const link = event.target.closest('a') || slide.closest('a') || img.closest('a');
        if (link) {
            const href = link.getAttribute('href') || '';
            const isImgHref = /\.(jpg|jpeg|png|gif|webp|svg)(\?.*)?$/i.test(href);
            const isDummyHref = href.startsWith('#') || href.startsWith('javascript:');
            const isLightboxLink = link.classList.contains('glightbox');
            
            if (!isImgHref && !isDummyHref && !isLightboxLink) {
                // Let normal page navigation happen
                return;
            }
        }

        // Prevent default and open the global lightbox
        event.preventDefault();
        openLightbox(swiperEl, img);
    });

    // 2. Bind close events
    const closeBtn = document.getElementById('global-lightbox-close');
    if (closeBtn) {
        closeBtn.addEventListener('click', (e) => {
            e.preventDefault();
            closeLightbox();
        });
    }

    const lightbox = document.getElementById('global-lightbox');
    if (lightbox) {
        lightbox.addEventListener('click', (event) => {
            // Close if clicking outside the image container
            if (event.target === lightbox || event.target.classList.contains('swiper-slide')) {
                closeLightbox();
            }
        });
    }

    // 3. Bind keyboard events
    document.addEventListener('keydown', (event) => {
        const lightboxEl = document.getElementById('global-lightbox');
        if (!lightboxEl || lightboxEl.classList.contains('opacity-0')) return;

        if (event.key === 'Escape') {
            closeLightbox();
        } else if (event.key === 'ArrowRight' || event.key === 'Right') {
            if (lightboxSwiperInstance && typeof lightboxSwiperInstance.slideNext === 'function') {
                lightboxSwiperInstance.slideNext();
            }
        } else if (event.key === 'ArrowLeft' || event.key === 'Left') {
            if (lightboxSwiperInstance && typeof lightboxSwiperInstance.slidePrev === 'function') {
                lightboxSwiperInstance.slidePrev();
            }
        }
    });
};

export { initLightbox };
