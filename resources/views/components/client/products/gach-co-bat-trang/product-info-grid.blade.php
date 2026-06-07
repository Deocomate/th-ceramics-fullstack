@props([
  'title' => '',
  'subtitle' => '',
  'description' => '',
  'colors' => [],
  'wrapperClass' => '',
  'titleClass' => '',
  'products' => [],
])

@php
  $colors = collect($colors)->filter()->values();
  if ($colors->isEmpty()) {
      $colors = collect(['#A98467', '#B22222', '#5D5FEF']);
  }
@endphp

<div class="{{ $wrapperClass }}">
  <h3
    class="text-[24px] leading-[36px] tracking-[0.75px] md:text-3xl md:leading-tight md:tracking-wide lg:text-4xl font-bold text-secondary mb-[10px] md:mb-9 {{ $titleClass }}"
  >
    {{ $title }}
  </h3>
  <p
    class="font-italianno text-[28px] lg:text-[36px] text-primary mb-[15px] md:mb-12 leading-none"
  >
    {{ $subtitle }}
  </p>

  <div class="flex gap-7 mb-[20px] md:mb-12 items-center justify-center">
    @foreach($colors->take(3) as $color)
      <div
        style="background-color: {{ $color }}"
        class="{{ $loop->iteration === 2 ? 'w-[70px] h-[90px] md:w-[134px] md:h-[144px]' : 'w-[70px] h-[90px] md:w-[120px] md:h-[129px]' }} rounded-2xl shadow-sm"
      ></div>
    @endforeach
  </div>

  @if(!empty($description))
    <p
      class="text-[12px] leading-[21.13px] md:text-[15px] md:leading-relaxed text-primary italic font-thin mb-[15px] md:mb-12 max-w-2xl"
    >
      {{ $description }}
    </p>
  @endif

  <table class="w-full text-primary spec-table">
    <thead>
      <tr
        class="border-b border-black/10 text-[14px] leading-[24px] md:text-[20px] md:leading-normal font-bold"
      >
        <th class="font-bold">
          Kích thước<br />(mm)
        </th>
        <th class="font-bold">
          Định mức<br />(viên/m2)
        </th>
        <th class="font-bold">
          Cân nặng<br />(kg)
        </th>
        <th class="py-[10px] md:py-4 text-center font-bold">Giá<br />(VND)</th>
      </tr>
    </thead>
    <tbody
      class="text-[14px] leading-[24px] md:text-[20px] md:leading-normal text-primary font-light"
    >
      @foreach($products as $product)
      <tr class="hover:bg-black/5 transition-colors cursor-pointer group"
        onclick="window.location.href = '{{ route('client.products.gach-co-bat-trang.detail', $product->gach_co_bat_trang_ct_id) }}'"
      >
        <td>{{ $product->size ?: '—' }}</td>
        <td>{{ $product->dinh_muc ?: '—' }}</td>
        <td>{{ $product->weight ?: '—' }}</td>
        <td>{{ $product->price > 0 ? number_format($product->price) . 'đ' : 'Liên hệ' }}</td>
      </tr>
      @endforeach
    </tbody>
  </table>
</div>
