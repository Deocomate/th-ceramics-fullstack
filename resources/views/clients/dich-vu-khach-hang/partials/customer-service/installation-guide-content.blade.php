<div class="flex-1 lg:pl-12">
  <h1 class="text-[30px] lg:text-[36px] font-arima font-medium text-primary mb-5 lg:mt-[-6px] lg:mb-10">Hướng dẫn thi công</h1>

  <div class="w-full">
    @forelse($guides as $guide)
      <div class="grid grid-cols-1 md:grid-cols-2 w-full gap-0">
        @if($loop->odd)
          <div class="bg-[#D9D9D9] w-full aspect-[3/2] lg:aspect-[9/6] order-1 md:order-2">
            <img src="{{ asset('storage/' . $guide->anh) }}" alt="{{ $guide->tieu_de }}" class="w-full h-full object-cover" />
          </div>

          <div class="flex flex-col justify-center items-start px-0 lg:ml-8 lg:p-8 bg-transparent order-2 md:order-1 mt-5 lg:mt-0 {{ $loop->last ? '' : 'mb-12 lg:mb-0' }}">
            <h3 class="text-2xl lg:text-[32px] font-semibold text-primary mb-4 font-archivo leading-[32px]">{{ $guide->tieu_de }}</h3>
            @if($guide->link_youtube)
              <a href="{{ $guide->link_youtube }}" target="_blank" rel="noopener noreferrer"
                class="flex items-center justify-center lg:justify-between gap-2 px-2 py-1.5 border border-primary text-primary text-[12px] lg:text-[14px] font-extralight hover:bg-primary hover:text-white transition-all font-archivo w-[111px] h-[32px] lg:w-fit lg:h-auto lg:min-w-[140px]">
                Xem hướng dẫn
                <img src="{{ asset('assets/images/triangle.svg') }}" alt="" class="hidden lg:block w-[6px] h-[10px] rotate-180 scale-x-[-1] ml-4" />
              </a>
            @endif
          </div>
        @else
          <div class="bg-[#D9D9D9] w-full aspect-[3/2] lg:aspect-[9/6] order-1 md:order-1">
            <img src="{{ asset('storage/' . $guide->anh) }}" alt="{{ $guide->tieu_de }}" class="w-full h-full object-cover" />
          </div>

          <div class="flex flex-col justify-center items-end lg:mr-8 lg:p-8 bg-transparent text-right order-2 md:order-2 mt-5 lg:mt-0 {{ $loop->last ? '' : 'mb-12 lg:mb-0' }}">
            <h3 class="text-2xl lg:text-[32px] font-semibold text-primary mb-4 font-archivo leading-[32px]">{{ $guide->tieu_de }}</h3>
            @if($guide->link_youtube)
              <a href="{{ $guide->link_youtube }}" target="_blank" rel="noopener noreferrer"
                class="flex items-center justify-center lg:justify-between gap-2 px-2 py-1.5 border border-primary text-primary text-[12px] lg:text-[14px] font-extralight hover:bg-primary hover:text-white transition-all font-archivo w-[111px] h-[32px] lg:w-fit lg:h-auto lg:min-w-[140px]">
                Xem hướng dẫn
                <img src="{{ asset('assets/images/triangle.svg') }}" alt="" class="hidden lg:block w-[6px] h-[10px] rotate-180 scale-x-[-1] ml-4" />
              </a>
            @endif
          </div>
        @endif
      </div>
    @empty
      <div class="text-center py-16">
        <p class="text-primary/60 text-lg font-archivo">Đang cập nhật hướng dẫn thi công...</p>
      </div>
    @endforelse
  </div>
</div>
