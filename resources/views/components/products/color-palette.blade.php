<section class="w-[85%] max-w-[1320px] mx-auto md:mb-16" data-aos="fade-up">
  <h2 class="text-[20px] md:text-3xl font-semibold text-center text-secondary mb-5 md:mb-12 uppercase">
    {{ isset($title) ? $title : 'Bảng màu' }}
  </h2>

  <div class="grid grid-cols-5 md:grid-cols-3 lg:grid-cols-5 gap-x-[17px] gap-y-[25px] md:gap-x-28 md:gap-y-14">
    @isset($colors)
      @foreach ($colors as $color)
      <div class="flex flex-col items-center gap-0 md:gap-4">
        <div class="w-full aspect-[3/2] md:aspect-[5/4] rounded-[4px] md:rounded-[22px] overflow-hidden shadow-sm {{ isset($color['colorCode']) ? 'bg-[' . $color['colorCode'] . ']' : 'bg-[#FBF9F7]' }}">
          @isset($color['image'])
          <img src="{{ $color['image'] }}" alt="{{ $color['name'] }}" class="w-full h-full object-cover" />
          @endisset
        </div>
        <span class="whitespace-nowrap text-[20px] md:text-3xl lg:text-[40px] text-primary leading-[32px] md:leading-normal" style="font-family: 'Italianno', cursive">
          {{ $color['name'] }}
        </span>
      </div>
      @endforeach
    @else
      <div class="flex flex-col items-center gap-0 md:gap-4">
        <div class="w-full aspect-[3/2] md:aspect-[5/4] rounded-[4px] md:rounded-[22px] overflow-hidden shadow-sm bg-[#FBF9F7]">
          <img src="{{ asset('assets/images/do-co.png') }}" alt="Đỏ cờ" class="w-full h-full object-cover" />
        </div>
        <span class="whitespace-nowrap text-[20px] md:text-3xl lg:text-[40px] text-primary leading-[32px] md:leading-normal" style="font-family: 'Italianno', cursive">Đỏ cờ</span>
      </div>

      <div class="flex flex-col items-center gap-0 md:gap-4">
        <div class="w-full aspect-[3/2] md:aspect-[5/4] rounded-[4px] md:rounded-[22px] shadow-sm bg-[#A67B5B]"></div>
        <span class="whitespace-nowrap text-[20px] md:text-3xl lg:text-[40px] text-primary leading-[32px] md:leading-normal" style="font-family: 'Italianno', cursive">Nâu đỏ</span>
      </div>

      <div class="flex flex-col items-center gap-0 md:gap-4">
        <div class="w-full aspect-[3/2] md:aspect-[5/4] rounded-[4px] md:rounded-[22px] shadow-sm bg-[#B22222]"></div>
        <span class="whitespace-nowrap text-[20px] md:text-3xl lg:text-[40px] text-primary leading-[32px] md:leading-normal" style="font-family: 'Italianno', cursive">Gấm đỏ</span>
      </div>

      <div class="flex flex-col items-center gap-0 md:gap-4">
        <div class="w-full aspect-[3/2] md:aspect-[5/4] rounded-[4px] md:rounded-[22px] shadow-sm bg-[#5D5FEF]"></div>
        <span class="whitespace-nowrap text-[20px] md:text-3xl lg:text-[40px] text-primary leading-[32px] md:leading-normal" style="font-family: 'Italianno', cursive">Xanh đồng</span>
      </div>

      <div class="flex flex-col items-center gap-0 md:gap-4">
        <div class="w-full aspect-[3/2] md:aspect-[5/4] rounded-[4px] md:rounded-[22px] shadow-sm bg-[#2ECC71]"></div>
        <span class="whitespace-nowrap text-[20px] md:text-3xl lg:text-[40px] text-primary leading-[32px] md:leading-normal" style="font-family: 'Italianno', cursive">Xanh ngọc</span>
      </div>

      <div class="flex flex-col items-center gap-0 md:gap-4">
        <div class="w-full aspect-[3/2] md:aspect-[5/4] rounded-[4px] md:rounded-[22px] shadow-sm bg-[#CC7A00]"></div>
        <span class="whitespace-nowrap text-[20px] md:text-3xl lg:text-[40px] text-primary leading-[32px] md:leading-normal" style="font-family: 'Italianno', cursive">Hổ phách</span>
      </div>

      <div class="flex flex-col items-center gap-0 md:gap-4">
        <div class="w-full aspect-[3/2] md:aspect-[5/4] rounded-[4px] md:rounded-[22px] shadow-sm bg-[#009432]"></div>
        <span class="whitespace-nowrap text-[20px] md:text-3xl lg:text-[40px] text-primary leading-[32px] md:leading-normal" style="font-family: 'Italianno', cursive">Nâu đen</span>
      </div>

      <div class="flex flex-col items-center gap-0 md:gap-4">
        <div class="w-full aspect-[3/2] md:aspect-[5/4] rounded-[4px] md:rounded-[22px] shadow-sm bg-[#D1D1E9]"></div>
        <span class="whitespace-nowrap text-[20px] md:text-3xl lg:text-[40px] text-primary leading-[32px] md:leading-normal" style="font-family: 'Italianno', cursive">Sô cô la</span>
      </div>

      <div class="flex flex-col items-center gap-0 md:gap-4">
        <div class="w-full aspect-[3/2] md:aspect-[5/4] rounded-[4px] md:rounded-[22px] shadow-sm bg-[#00BFFF]"></div>
        <span class="whitespace-nowrap text-[20px] md:text-3xl lg:text-[40px] text-primary leading-[32px] md:leading-normal" style="font-family: 'Italianno', cursive">Xanh ngọc</span>
      </div>

      <div class="flex flex-col items-center gap-0 md:gap-4">
        <div class="w-full aspect-[3/2] md:aspect-[5/4] rounded-[4px] md:rounded-[22px] shadow-sm bg-[#FFD700]"></div>
        <span class="whitespace-nowrap text-[20px] md:text-3xl lg:text-[40px] text-primary leading-[32px] md:leading-normal" style="font-family: 'Italianno', cursive">Hoàng thổ</span>
      </div>

      <div class="flex flex-col items-center gap-0 md:gap-4">
        <div class="w-full aspect-[3/2] md:aspect-[5/4] rounded-[4px] md:rounded-[22px] shadow-sm bg-[#2980B9]"></div>
        <span class="whitespace-nowrap text-[20px] md:text-3xl lg:text-[40px] text-primary leading-[32px] md:leading-normal" style="font-family: 'Italianno', cursive">Xanh dương</span>
      </div>

      <div class="flex flex-col items-center gap-0 md:gap-4">
        <div class="w-full aspect-[3/2] md:aspect-[5/4] rounded-[4px] md:rounded-[22px] shadow-sm bg-[#4A5D23]"></div>
        <span class="whitespace-nowrap text-[20px] md:text-3xl lg:text-[40px] text-primary leading-[32px] md:leading-normal" style="font-family: 'Italianno', cursive">Xanh rêu</span>
      </div>

      <div class="flex flex-col items-center gap-0 md:gap-4">
        <div class="w-full aspect-[3/2] md:aspect-[5/4] rounded-[4px] md:rounded-[22px] shadow-sm bg-[#B07D35]"></div>
        <span class="whitespace-nowrap text-[20px] md:text-3xl lg:text-[40px] text-primary leading-[32px] md:leading-normal" style="font-family: 'Italianno', cursive">Da lươn</span>
      </div>

      <div class="flex flex-col items-center gap-0 md:gap-4">
        <div class="w-full aspect-[3/2] md:aspect-[5/4] rounded-[4px] md:rounded-[22px] shadow-sm bg-[#7F8C8D]"></div>
        <span class="whitespace-nowrap text-[20px] md:text-3xl lg:text-[40px] text-primary leading-[32px] md:leading-normal" style="font-family: 'Italianno', cursive">Ghi xám</span>
      </div>

      <div class="flex flex-col items-center gap-0 md:gap-4">
        <div class="w-full aspect-[3/2] md:aspect-[5/4] rounded-[4px] md:rounded-[22px] shadow-sm bg-[#2C3E50]"></div>
        <span class="whitespace-nowrap text-[20px] md:text-3xl lg:text-[40px] text-primary leading-[32px] md:leading-normal" style="font-family: 'Italianno', cursive">Đen nhám</span>
      </div>
    @endisset
  </div>
</section>