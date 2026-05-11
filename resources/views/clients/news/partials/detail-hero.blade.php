<section class="w-full flex flex-col-reverse lg:flex-row min-h-[500px] md:h-[60vh] lg:h-[70vh] bg-neutral-2 overflow-hidden">
    <!-- Left Half: Image -->
    <div class="w-full lg:w-1/2 h-[400px] lg:h-full relative overflow-hidden" data-aos="fade-right">
        <img
            src="{{ \App\Support\AssetPath::url($article->anh_dai_dien ?? null, 'assets/images/news-01.jpg') }}"
            alt="{{ $article->tieu_de ?? 'News Detail' }}"
            class="w-full h-full object-cover"
        >
    </div>

    <!-- Right Half: Text Content -->
    <div
        class="w-full lg:w-1/2 flex items-center justify-center p-8 md:p-16 lg:p-24 bg-white"
        data-aos="fade-left"
    >
        <div class="max-w-md flex flex-col items-start gap-4">
            <h1 class="max-w-sm text-3xl md:text-5xl xl:text-4xl font-arima text-primary leading-tight tracking-wide">
                {{ $article->tieu_de ?? '' }}
            </h1>
            <p class="text-base text-primary opacity-80 leading-relaxed text-left md:text-justify">
                {{ $article->mo_ta_ngan ?? '' }}
            </p>
        </div>
    </div>
</section>
