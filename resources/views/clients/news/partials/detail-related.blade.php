<!-- Related Articles for News Detail -->
<section class="pb-8 md:pb-16">
    <div class="w-[85%] max-w-[1320px] mx-auto">

        <div>
            <h2
                class="text-sm md:text-base font-archivo font-bold text-secondary uppercase mb-6 tracking-wide"
                data-aos="fade-right"
            >
                Bài viết liên quan
            </h2>
            <div
              class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 md:gap-8"
              data-aos="fade-up"
            >
              @forelse ($relatedNews as $item)
              <article class="flex flex-col group">
                <a href="{{ route('client.news.detail', $item->slug) }}" class="aspect-[16/11] overflow-hidden mb-4">
                  <img
                    src="{{ \App\Support\AssetPath::url($item->anh_dai_dien, 'assets/images/news-01.jpg') }}"
                    alt="{{ $item->tieu_de }}"
                    class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105"
                  />
                </a>
                <p class="text-[11px] font-bold text-primary/80 uppercase tracking-widest mb-2">{{ $item->danhMuc->ten_danh_muc ?? 'Tin tức' }}</p>
                <a href="{{ route('client.news.detail', $item->slug) }}">
                  <h3 class="text-lg font-arima font-medium text-primary mb-2 leading-tight hover:text-secondary transition-colors">
                    {{ $item->tieu_de }}
                  </h3>
                </a>
                <p class="text-[12px] text-gray-500 uppercase">{{ optional($item->ngay_dang)->format('d/m/Y') ?? optional($item->created_at)->format('d/m/Y') }}</p>
              </article>
              @empty
              <p class="col-span-full text-primary/60">Chưa có bài viết liên quan.</p>
              @endforelse
            </div>
        </div>

    </div>
</section>
