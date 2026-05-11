@php
  $material = collect($about->nt_ngon_ngu ?? [])->first() ?? [];
  $materialTitle = data_get($material, 'head', 'Ngôn ngữ của vật liệu');
  $materialBody = data_get($material, 'body', 'Chúng tôi hiểu rằng đất chính là cốt lõi làm nên hồn của mỗi sản phẩm.');
  $materialImage = \App\Support\AssetPath::url(data_get($material, 'image'), 'assets/images/about-02.jpg');
@endphp

<!-- Craft Section 2: Ngôn ngữ của vật liệu -->
<div
  class="flex flex-col md:flex-row items-center gap-8 md:gap-36"
  data-aos="fade-up"
>
  <div class="w-full md:w-1/2 lg:ml-20 flex flex-col">
    <h3
      class="text-[20px] md:text-4xl font-bold text-textPrimary mb-4 md:mb-6 text-center md:text-left leading-[32px] md:leading-normal"
    >
      {{ $materialTitle }}
    </h3>
    <p
      class="text-textPrimary leading-[28px] text-justify md:text-left md:mb-4 font-medium tracking-[0.48px] md:tracking-wide lg:max-w-md"
    >
      {{ $materialBody }}
    </p>
  </div>
  <div class="w-full md:w-1/2">
    <div
      class="aspect-[1/1] w-full max-w-[334px] md:max-w-none mx-auto relative overflow-hidden shadow-lg"
    >
      <img
        src="{{ $materialImage }}"
        alt="Ngôn ngữ của vật liệu"
        class="w-full h-full object-cover transform hover:scale-105 transition-transform duration-700"
      />
    </div>
  </div>
</div>
