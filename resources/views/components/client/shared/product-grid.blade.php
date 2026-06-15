@props(['products' => collect(), 'category' => null, 'routeName' => null, 'pkField' => null, 'productType' => null])

@php
    use App\Support\ClientProductType;

    $resolvedProductType = $productType ?: ClientProductType::fromDetailRoute($routeName);
@endphp

<section class="w-[85%] max-w-[1320px] mx-auto pb-[43px] md:pb-16 animate-fade-in-up">
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-x-[16px] gap-y-[30px] sm:gap-x-6 sm:gap-y-14"
        data-aos="fade-up" data-aos-delay="200">
        @forelse ($products as $product)
            @php
                $productId = ClientProductType::resolveProductId($product, $resolvedProductType, $pkField);
                $detailUrl =
                    $routeName && $productId && \Illuminate\Support\Facades\Route::has($routeName)
                        ? route($routeName, $productId)
                        : '#';
                $firstImage = collect($product->images ?? [])->first();
                $imageUrl = \App\Support\AssetPath::url($firstImage, 'assets/images/ngoi-01.jpg');
                $price = (float) ($product->price ?? 0);
                $priceText = $product->display_price ?? ($price > 0 ? 'Giá: ' . number_format($price, 0, ',', '.') . 'đ' : 'Giá: Liên hệ');
                $codeVal = $product->display_code ?? $product->code;
                $codeText = 'MSP: ' . ($codeVal ?: 'Đang cập nhật');
            @endphp
            <x-client.shared.product-card href="{{ $detailUrl }}" image="{{ $imageUrl }}"
                title="{{ $product->name ?? '' }}" code="{{ $codeText }}"
                price="{{ $priceText }}" :show-overlay="true"
                :product-type="$resolvedProductType" :product-id="$productId"
                :product="$product" :pk-field="$pkField" />
        @empty
            <div class="col-span-full text-center py-12 text-gray-500">Chưa có sản phẩm nào.</div>
        @endforelse
    </div>

    @if ($products && method_exists($products, 'lastPage'))
        @php
            $currentPage = $products->currentPage();
            $lastPage = $products->lastPage();
            $windowStart = max(2, $currentPage - 1);
            $windowEnd = min($lastPage - 1, $currentPage + 1);
            $pages = [1];

            if ($windowStart > 2) {
                $pages[] = '...';
            }

            for ($page = $windowStart; $page <= $windowEnd; $page++) {
                $pages[] = $page;
            }

            if ($windowEnd < $lastPage - 1) {
                $pages[] = '...';
            }

            if ($lastPage > 1) {
                $pages[] = $lastPage;
            }
        @endphp
        <nav class="flex items-center justify-between gap-6 mt-[40px] md:mt-16 text-textPrimary font-bold text-[17px]"
            aria-label="Pagination">
            @if ($products->onFirstPage())
                <span
                    class="w-10 h-10 border-2 border-black/10 rounded-full flex items-center justify-center text-black/20 cursor-not-allowed"
                    aria-disabled="true">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7">
                        </path>
                    </svg>
                </span>
            @else
                <a href="{{ $products->previousPageUrl() }}"
                    class="w-10 h-10 border-2 border-black/20 rounded-full flex items-center justify-center text-black/40 hover:border-black hover:text-black transition-all cursor-pointer"
                    rel="prev">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7">
                        </path>
                    </svg>
                </a>
            @endif

            <div class="flex items-center gap-5">
                @foreach ($pages as $page)
                    @if (is_string($page))
                        <span class="text-black/40 tracking-widest px-1">{{ $page }}</span>
                    @elseif ($page == $currentPage)
                        <span class="text-black border-b-[3px] border-black pb-[2px] px-1"
                            aria-current="page">{{ $page }}</span>
                    @else
                        <a href="{{ $products->url($page) }}"
                            class="text-black/40 hover:text-black transition-colors px-1">{{ $page }}</a>
                    @endif
                @endforeach
            </div>

            @if ($products->hasMorePages())
                <a href="{{ $products->nextPageUrl() }}"
                    class="w-10 h-10 border-2 border-black/20 rounded-full flex items-center justify-center text-black/40 hover:border-black hover:text-black transition-all cursor-pointer"
                    rel="next">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
            @else
                <span
                    class="w-10 h-10 border-2 border-black/10 rounded-full flex items-center justify-center text-black/20 cursor-not-allowed"
                    aria-disabled="true">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </span>
            @endif
        </nav>
    @endif
</section>
