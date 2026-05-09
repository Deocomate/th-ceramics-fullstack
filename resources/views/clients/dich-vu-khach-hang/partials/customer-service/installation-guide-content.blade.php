<div class="flex-1 lg:pl-12">
  <h1 class="text-[30px] lg:text-[36px] font-arima font-medium text-primary mb-5 lg:mt-[-6px] lg:mb-10">Hướng dẫn thi công</h1>

  <div class="grid grid-cols-1 md:grid-cols-2 w-full gap-y-0">
    @forelse($guides as $guide)
      <div class="grid grid-cols-1 md:grid-cols-2 gap-0 w-full mb-12 md:mb-0">

        <div class="bg-[#D9D9D9] w-full aspect-[3/2] lg:aspect-[9/6] {{ $loop->even ? 'md:order-2' : 'md:order-1' }}">
          <img src="{{ asset('storage/' . $guide->anh) }}" alt="{{ $guide->tieu_de }}" class="w-full h-full object-cover" />
        </div>

        <div class="flex flex-col justify-center px-0 mt-5 md:mt-0 {{ $loop->even ? 'md:order-1 items-start md:mr-8 lg:p-8' : 'md:order-2 items-end md:ml-8 lg:p-8 text-right' }}">
          <h3 class="text-2xl lg:text-[32px] font-semibold text-primary mb-4 font-archivo leading-[32px]">{{ $guide->tieu_de }}</h3>
          @if($guide->link_youtube)
            <a href="{{ $guide->link_youtube }}" target="_blank" rel="noopener noreferrer"
              class="flex items-center justify-center lg:justify-between gap-2 px-2 py-1.5 border border-primary text-primary text-[12px] lg:text-[14px] font-extralight hover:bg-primary hover:text-white transition-all font-archivo w-[111px] h-[32px] lg:w-fit lg:h-auto lg:min-w-[140px]">
              Xem hướng dẫn
              <img src="{{ asset('assets/images/triangle.svg') }}" alt="" class="hidden lg:block w-[6px] h-[10px] rotate-180 scale-x-[-1] ml-4" />
            </a>
          @endif
        </div>

      </div>
    @empty
      <div class="col-span-1 md:col-span-2 text-center py-16">
        <p class="text-primary/60 text-lg font-archivo">Đang cập nhật hướng dẫn thi công...</p>
      </div>
    @endforelse
  </div>
</div>
