@props([
    'trangChu' => null,
    'ngoiAmDuongs' => null,
    'ngoiHais' => null,
    'gachHoas' => null,
    'about' => null,
    'factory' => null,
    'showroomImages' => null,
    'showroomContent' => null,
    'news' => null,
    'article' => null,
    'articles' => null,
    'relatedArticles' => null,
    'historyArticles' => null,
    'projects' => null,
    'project' => null,
    'relatedProjects' => null,
    'categories' => null,
    'selectedCategory' => null,
    'currentCategory' => null,
    'config' => null,
    'products' => null,
    'relatedProducts' => null,
    'product' => null,
    'colors' => null,
    'dinhMuc' => null,
    'giaTriVuotTroi' => null,
    'parentConfig' => null,
    'pageLabel' => null,
    'indexRouteName' => null,
    'categoryType' => null,
    'categoryLabel' => null,
    'denGomProducts' => null,
    'denSuProducts' => null,
    'featuredProducts' => null,
    'collectionProducts' => null,
    'ngheProducts' => null,
    'linhVatProducts' => null,
    'bgImage' => null,
    'activeOrder' => false,
    'activeAccount' => false,
    'activeCatalog' => false,
    'activeGuide' => false,
    'activeProcess' => false,
    'activePrivacy' => false,
    'activeReturn' => false,
    'activeShipping' => false,
    'image' => null,
    'label1' => null,
    'rate1' => null,
    'label2' => null,
    'rate2' => null,
    'sectionId' => null,
    'sectionClass' => null,
    'sectionTitle' => null,
    'desktopLinkHref' => null,
    'detailRouteName' => null,
    'wrapperClass' => null,
    'titleClass' => null,
    'title' => null,
    'subtitle' => null,
    'description' => null,
    'items' => null,
    'orders' => collect(),
    'counts' => [
        'all' => 0,
        'pending_payment' => 0,
        'processing' => 0,
        'shipping' => 0,
        'completed' => 0,
        'canceled' => 0,
        'returned' => 0,
    ],
])
<div class="flex-1 lg:pl-12">
  <h1 class="text-[30px] lg:text-[36px] leading-[36px] font-arima text-primary mb-[30px] lg:mb-10 mt-[-6px]">Trạng thái đơn hàng</h1>

  <div class="mb-8">
    <h3 class="text-base font-bold text-primary mb-8 lg:mb-6">Đơn hàng của tôi</h3>

    <!-- Tab Menu -->
    <div class="w-full justify-between flex border-b border-[#D4D4D4] overflow-x-auto scrollbar-hide text-primary font-semibold lg:font-bold">
      <button type="button" class="px-5 lg:px-6 py-2 text-base border-b-2 border-secondary text-secondary whitespace-nowrap tab-btn active" data-filter="all">
        <div class="text-base mb-1">{{ $counts['all'] }}</div>
        Tất cả
      </button>
      <button type="button" class="px-5 lg:px-6 py-2 text-base border-b-2 border-transparent hover:text-secondary whitespace-nowrap transition-colors tab-btn" data-filter="pending_payment">
        <div class="text-base mb-1">{{ $counts['pending_payment'] }}</div>
        Chờ thanh toán
      </button>
      <button type="button" class="px-5 lg:px-6 py-2 text-base border-b-2 border-transparent hover:text-secondary whitespace-nowrap transition-colors tab-btn" data-filter="processing">
        <div class="text-base mb-1">{{ $counts['processing'] }}</div>
        Đang xử lý
      </button>
      <button type="button" class="px-5 lg:px-6 py-2 text-base border-b-2 border-transparent hover:text-secondary whitespace-nowrap transition-colors tab-btn" data-filter="shipping">
        <div class="text-base mb-1">{{ $counts['shipping'] }}</div>
        Đang giao
      </button>
      <button type="button" class="px-5 lg:px-6 py-2 text-base border-b-2 border-transparent hover:text-secondary whitespace-nowrap transition-colors tab-btn" data-filter="completed">
        <div class="text-base mb-1">{{ $counts['completed'] }}</div>
        Hoàn tất
      </button>
      <button type="button" class="px-5 lg:px-6 py-2 text-base border-b-2 border-transparent hover:text-secondary whitespace-nowrap transition-colors tab-btn" data-filter="canceled">
        <div class="text-base mb-1">{{ $counts['canceled'] }}</div>
        Bị hủy
      </button>
      <button type="button" class="px-5 lg:px-6 py-2 text-base border-b-2 border-transparent hover:text-secondary whitespace-nowrap transition-colors tab-btn" data-filter="returned">
        <div class="text-base mb-1">{{ $counts['returned'] }}</div>
        Đổi trả
      </button>
    </div>
  </div>

  <!-- Order Cards Container -->
  <div class="space-y-[30px] lg:space-y-10" id="order-cards-container">
    @forelse($orders as $order)
    @php
        $badgeClass = match ($order->status) {
            'pending_payment' => 'bg-yellow-100 text-yellow-800',
            'processing' => 'bg-[#CAE5FF] text-[#2F80ED]',
            'shipping' => 'bg-[#E2FBE9] text-[#27AE60]',
            'completed' => 'bg-[#FDEEDC] text-[#E67E22]',
            'canceled' => 'bg-[#FBCFCF] text-[#EB5757]',
            'returned' => 'bg-purple-100 text-purple-800',
            default => 'bg-gray-100 text-gray-800',
        };
    @endphp
    <div class="bg-white lg:bg-[#FEF9F5] border-0 lg:border lg:border-gray-300 rounded-md shadow lg:shadow-lg px-[15px] pt-[15px] pb-6 lg:p-8 order-card" data-status="{{ $order->status }}">
      {{-- Header --}}
      <div class="flex justify-between items-center mb-4 pb-4 border-b border-gray-300">
        <div class="flex items-center gap-3">
          <span class="text-secondary font-bold text-sm lg:text-base">{{ $order->order_code }}</span>
          <span class="text-[10px] lg:text-xs font-bold px-3 py-1 rounded-full uppercase {{ $badgeClass }}">
            {{ \App\Models\Order::statusLabel($order->status) }}
          </span>
        </div>
        <div class="text-primary/60 text-[12px] lg:text-sm font-semibold">{{ $order->created_at->format('d/m/Y - H:i') }}</div>
      </div>

      {{-- Items --}}
      <div class="space-y-6">
        @foreach($order->items as $item)
        <div class="flex gap-4 lg:gap-6">
          <div class="w-20 h-20 lg:w-28 lg:h-28 border border-gray-300 rounded-md overflow-hidden flex-shrink-0 bg-white p-2">
            <img src="{{ asset('assets/images/ngoi-van-mieu-detail-2.png') }}" alt="{{ $item->product_name }}" class="w-full h-full object-contain">
          </div>
          <div class="flex-1 flex justify-between items-end py-1 min-w-0">
            <div class="space-y-1 min-w-0">
              <h4 class="text-sm lg:text-xl font-bold text-primary whitespace-nowrap">{{ $item->product_name }}</h4>
              @if($item->sku)<p class="text-xs lg:text-[13px] text-primary/40 uppercase">MSP: {{ $item->sku }}</p>@endif
              @if($item->variant_name)<p class="text-xs lg:text-[13px] text-primary/40">Phân loại: {{ $item->variant_name }}</p>@endif
              <p class="text-xs lg:text-[13px] text-primary/40">x{{ $item->quantity }}</p>
            </div>
            <div class="text-right flex-shrink-0 whitespace-nowrap ml-4">
                <p class="text-base lg:text-[14px] text-primary">{{ number_format($item->price, 0, ',', '.') }} đ</p>
            </div>
          </div>
        </div>
        @endforeach
      </div>

      {{-- Footer --}}
      <div class="mt-4 pt-4 lg:border-t border-gray-300 flex flex-col lg:block gap-2 lg:gap-0">
        <span class="order-1 lg:hidden text-sm font-semibold text-primary">Số lượng: {{ $order->items->sum('quantity') }}</span>
        <div class="order-2 flex justify-between lg:justify-end mb-2 lg:mb-4">
            <span class="text-secondary font-archivo font-semibold text-sm lg:hidden">Tổng tiền:</span>
            <p class="text-sm lg:text-base font-bold font-archivo text-primary">
                <span class="text-secondary font-archivo font-medium hidden lg:inline">Tổng tiền:</span> {{ number_format($order->total_amount, 0, ',', '.') }} đ
            </p>
        </div>
        <div class="order-3 flex flex-col lg:flex-row lg:items-center justify-between gap-3 lg:gap-6">
            <span class="hidden lg:block text-sm lg:text-base text-primary font-bold">Số lượng: {{ $order->items->sum('quantity') }}</span>
            <div class="flex items-center gap-2 lg:gap-4">
                @if(in_array($order->status, ['processing', 'pending_payment']))
                    <a href="tel:19006750" class="w-1/2 lg:w-48 py-2 border border-secondary text-secondary text-sm lg:text-base font-bold rounded-md hover:bg-secondary/5 transition-colors text-center">
                        Hủy đơn hàng
                    </a>
                @endif
                <a href="tel:19006750" class="w-1/2 lg:w-48 py-2 bg-secondary text-white text-sm lg:text-base font-bold rounded-md hover:opacity-90 transition-opacity text-center">
                    Liên hệ
                </a>
            </div>
        </div>
      </div>
    </div>

    @empty
    <div class="text-center py-16">
        @if(auth()->check())
            <p class="text-primary/60 text-base font-archivo mb-4">Bạn chưa có đơn hàng nào.</p>
            <a href="{{ route('client.home') }}" class="inline-block px-8 py-3 bg-secondary text-white text-sm font-bold uppercase rounded-md hover:opacity-90 transition-opacity">
                Tiếp tục mua sắm
            </a>
        @else
            <p class="text-primary/60 text-base font-archivo mb-4">Vui lòng đăng nhập để xem đơn hàng của bạn.</p>
            <a href="{{ route('client.auth.login') }}" class="inline-block px-8 py-3 bg-secondary text-white text-sm font-bold uppercase rounded-md hover:opacity-90 transition-opacity">
                Đăng nhập
            </a>
        @endif
    </div>
    @endforelse
  </div>

  <!-- Pagination (placeholder for future pagination) -->
  <div class="mt-8 lg:mt-12 flex items-center justify-between gap-8">
    <button class="text-primary hover:text-secondary transition-colors" aria-label="Previous page">
      <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M19 12H5M5 12L12 19M5 12L12 5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
      </svg>
    </button>

    <div class="flex items-center gap-6 text-base font-bold">
      <span class="text-secondary border-b-2 border-secondary pb-0.5">1</span>
    </div>

    <button class="text-secondary hover:opacity-80 transition-opacity" aria-label="Next page">
      <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M5 12H19M19 12L12 5M19 12L12 19" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
      </svg>
    </button>
  </div>

  <script>
  document.addEventListener('DOMContentLoaded', () => {
      const tabBtns = document.querySelectorAll('.tab-btn');
      const cards = document.querySelectorAll('.order-card');

      tabBtns.forEach(btn => {
          btn.addEventListener('click', () => {
              const filter = btn.dataset.filter;

              tabBtns.forEach(b => {
                  b.classList.remove('border-secondary', 'text-secondary', 'active');
                  b.classList.add('border-transparent');
              });
              btn.classList.add('border-secondary', 'text-secondary', 'active');
              btn.classList.remove('border-transparent');

              cards.forEach(card => {
                  if (filter === 'all' || card.dataset.status === filter) {
                      card.style.display = '';
                  } else {
                      card.style.display = 'none';
                  }
              });
          });
      });
  });
  </script>
</div>
