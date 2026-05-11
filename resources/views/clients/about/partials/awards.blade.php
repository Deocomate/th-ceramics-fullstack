@php
  $awards = collect($about->gs_giai_thuong ?? [])->filter(fn ($item) => is_array($item))->values();
@endphp

<!-- Section 6: Awards / Giải thưởng & Thành tựu -->
<div class="mt-8 md:mt-24 mb-16 w-full">
  <h3
    class="text-[20px] leading-[24px] md:text-4xl font-bold text-center text-[#C76E00] mb-5 md:mb-10 uppercase tracking-normal"
    data-aos="fade-up"
  >
    GIẢI THƯỞNG & THÀNH TỰU
  </h3>
  @if ($awards->isNotEmpty())
  <div class="grid grid-cols-1 md:grid-cols-3 gap-6 md:gap-10">
    @foreach ($awards as $award)
    <article class="flex flex-col items-center text-center gap-4">
      <div class="w-full aspect-[3/4] overflow-hidden shadow-lg">
        <img
          src="{{ \App\Support\AssetPath::url(data_get($award, 'image'), 'assets/images/award-01.jpg') }}"
          alt="{{ data_get($award, 'head', 'Giải thưởng') }}"
          class="w-full h-full object-cover"
        />
      </div>
      <h4 class="text-xl md:text-2xl font-bold text-textPrimary">{{ data_get($award, 'head', '') }}</h4>
      <p class="text-sm md:text-base text-textPrimary/80">{{ data_get($award, 'body', '') }}</p>
    </article>
    @endforeach
  </div>
  @else
  <x-home-awards />
  @endif
</div>
