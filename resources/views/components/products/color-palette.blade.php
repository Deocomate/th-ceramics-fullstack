@props(['colors' => null, 'title' => 'Bảng màu'])

@php
  $colorItems = collect($colors)->filter()->values();

  if ($colorItems->isEmpty()) {
      $colorItems = collect([
          ['name' => 'Đỏ cờ', 'image' => 'assets/images/do-co.png', 'colorCode' => '#FBF9F7'],
          ['name' => 'Nâu đỏ', 'colorCode' => '#A67B5B'],
          ['name' => 'Gấm đỏ', 'colorCode' => '#B22222'],
          ['name' => 'Xanh đồng', 'colorCode' => '#5D5FEF'],
          ['name' => 'Xanh ngọc', 'colorCode' => '#2ECC71'],
          ['name' => 'Hổ phách', 'colorCode' => '#CC7A00'],
          ['name' => 'Nâu đen', 'colorCode' => '#009432'],
          ['name' => 'Sô cô la', 'colorCode' => '#D1D1E9'],
          ['name' => 'Xanh ngọc', 'colorCode' => '#00BFFF'],
          ['name' => 'Hoàng thổ', 'colorCode' => '#FFD700'],
          ['name' => 'Xanh dương', 'colorCode' => '#2980B9'],
          ['name' => 'Xanh rêu', 'colorCode' => '#4A5D23'],
          ['name' => 'Da lươn', 'colorCode' => '#B07D35'],
          ['name' => 'Ghi xám', 'colorCode' => '#7F8C8D'],
          ['name' => 'Đen nhám', 'colorCode' => '#2C3E50'],
      ]);
  }
@endphp

<section class="w-[85%] max-w-[1320px] mx-auto md:mb-16" data-aos="fade-up">
  <h2 class="text-[20px] md:text-3xl font-semibold text-center text-secondary mb-5 md:mb-12 uppercase">
    {{ $title }}
  </h2>

  <div class="grid grid-cols-5 md:grid-cols-3 lg:grid-cols-5 gap-x-[17px] gap-y-[25px] md:gap-x-28 md:gap-y-14">
    @foreach ($colorItems as $color)
      @php
        $name = data_get($color, 'name', '');
        $image = data_get($color, 'image');
        $colorCode = data_get($color, 'colorCode') ?: data_get($color, 'color_code') ?: '#FBF9F7';
      @endphp
      <div class="flex flex-col items-center gap-0 md:gap-4">
        <div class="w-full aspect-[3/2] md:aspect-[5/4] rounded-[4px] md:rounded-[22px] overflow-hidden shadow-sm" style="background-color: {{ $colorCode }}">
          @if ($image)
            <img src="{{ \App\Support\AssetPath::url($image) }}" alt="{{ $name }}" class="w-full h-full object-cover" />
          @endif
        </div>
        <span class="whitespace-nowrap text-[20px] md:text-3xl lg:text-[40px] text-primary leading-[32px] md:leading-normal" style="font-family: 'Italianno', cursive">
          {{ $name }}
        </span>
      </div>
    @endforeach
  </div>
</section>
