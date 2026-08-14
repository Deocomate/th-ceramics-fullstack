@props(['colors' => null, 'title' => 'Bảng màu'])

@php
  $colorItems = collect($colors)->filter()->values();
@endphp

@if ($colorItems->isNotEmpty())
<section class="w-[85%] max-w-[1320px] mx-auto mb-10 pb-6 md:mb-16 md:pb-0" data-aos="fade-up">
  <h2 class="text-[20px] md:text-3xl font-semibold text-center text-secondary mb-5 md:mb-12 uppercase">
    {{ $title }}
  </h2>

  <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-x-6 gap-y-6 md:gap-x-28 md:gap-y-14">
    @foreach ($colorItems as $color)
      @php
        $name = data_get($color, 'name', '');
        $image = data_get($color, 'image');
        $colorCode = data_get($color, 'colorCode') ?: data_get($color, 'color_code') ?: '#FBF9F7';
      @endphp
      <div class="flex flex-col items-center gap-2 md:gap-4 min-w-0">
        <div class="w-full aspect-[3/2] md:aspect-[5/4] rounded-[4px] md:rounded-[22px] overflow-hidden shadow-sm" style="background-color: {{ $colorCode }}">
          @if ($image)
            <img src="{{ \App\Support\AssetPath::url($image) }}" alt="{{ $name }}" class="w-full h-full object-cover" />
          @endif
        </div>
        <span class="w-full text-center px-1 text-[20px] md:text-3xl lg:text-[40px] text-primary leading-[28px] md:leading-normal md:whitespace-nowrap" style="font-family: 'Italianno', cursive">
          {{ $name }}
        </span>
      </div>
    @endforeach
  </div>
</section>
@endif
