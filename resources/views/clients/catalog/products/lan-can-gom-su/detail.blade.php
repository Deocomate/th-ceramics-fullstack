@php
    $images =
        is_array($product->images) && count($product->images) > 0
            ? $product->images
            : ['assets/images/gach-bat-detail-1.png'];
    $activeVariants = $product->phanLoais->where('is_delete', 0)->sortBy('price');
    $firstVariant = $activeVariants->first();
    $variants = $activeVariants
        ->map(fn ($variant) => [
            'name' => $variant->name,
            'variantId' => $variant->phan_loai_lan_can_gom_su_ct_id,
            'sku' => $variant->code,
            'price' => (float) $variant->price,
            'priceFormatted' => $variant->price > 0 ? number_format((float) $variant->price, 0, ',', '.') . ' đ/chiếc' : 'Liên hệ',
        ])
        ->values();

    $productPrice = (float) ($firstVariant?->price ?? 0);
    $priceLabel = $productPrice > 0 ? number_format($productPrice, 0, ',', '.') . ' đ/chiếc' : 'Liên hệ';
    $productSku = $firstVariant?->code ?? 'Đang cập nhật';

    $jsonLdImages = \App\Domains\Catalog\Infrastructure\ProductGallery::normalize($images)
        ->where('type', 'image')
        ->map(fn ($item) => \App\Support\AssetPath::url($item['path'] ?? null))
        ->filter()
        ->values()
        ->all();
    $jsonLdDesc = \Illuminate\Support\Str::limit(strip_tags(implode(', ', $product->des ?? [])), 300);
@endphp

<x-client.layouts.main title="{{ $product->name }} - Lan Can Gốm Sứ | Gốm Sứ Thanh Hải" data-page="products"
    main-class="flex-grow bg-background-secondary pb-14 md:pb-20">
    @push('head')
        <meta name="description" content="{{ $jsonLdDesc }}">
        <script type="application/ld+json">
        {!! \Illuminate\Support\Js::encode([
            '@context' => 'https://schema.org/',
            '@type' => 'Product',
            'name' => $product->name,
            'image' => $jsonLdImages,
            'description' => $jsonLdDesc,
            'sku' => $firstVariant ? $firstVariant->code : '',
            'brand' => ['@type' => 'Brand', 'name' => 'Gốm Sứ Thanh Hải'],
            'offers' => [
                '@type' => 'Offer',
                'url' => route('client.products.lan-can-gom-su.detail', $product->lan_can_gom_su_ct_id),
                'priceCurrency' => 'VND',
                'price' => (string) ($activeVariants->min('price') ?? '0'),
                'availability' => 'https://schema.org/InStock',
                'seller' => ['@type' => 'Organization', 'name' => 'Gốm Sứ Thanh Hải'],
            ],
        ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
        </script>
    @endpush

    <!-- Sub Breadcrumb -->
    <div class="hidden md:block w-[85%] max-w-[1320px] mx-auto py-8">
        <x-client.shared.breadcrumb
            current-label="{{ $product->name }}"
            parent-label="Sản phẩm"
            parent-href="{{ route('client.products.lan-can-gom-su.index') }}"
        />
        <hr class="border-t border-black/10 mt-4 w-full">
    </div>

    <!-- Product Detail Container -->
    <x-client.catalog.shared.product-detail-container
        title="{{ $product->name }}"
        price="{{ $priceLabel }}"
        rawPrice="{{ $productPrice }}"
        sku="{{ $productSku }}"
        :features="$product->des && is_array($product->des) ? $product->des : null"
        :images="$images"
        :variants="$variants"
        productType="lan_can_gom_su_ct"
        productId="{{ $product->lan_can_gom_su_ct_id }}"
    />

    <x-client.catalog.shared.journey-video :video="$journeyVideo ?? null" />
    <x-client.content.shared.works-simple />

    <!-- Product Description Section -->
    <section id="bang-kich-thuoc" class="w-[85%] max-w-[1320px] mx-auto pb-16 lg:pb-24 pt-0 md:pt-4">
        <!-- Section Title -->
        <div class="text-center mb-8 md:mb-16" data-aos="fade-up">
            <h3 class="text-[20px] md:text-3xl font-semibold text-secondary uppercase drop-shadow-sm">
                Mô tả sản phẩm
            </h3>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-10 lg:gap-16 items-center">
            <!-- Left Image -->
            <div class="flex items-center justify-center" data-aos="fade-right">
                <img src="{{ $product->size_image ? asset('storage/' . $product->size_image) : asset('assets/images/gach-bat-size-1.png') }}"
                    alt="Mô tả kích thước" class="w-full max-w-[550px] object-contain">
            </div>
            <!-- Right List -->
            <div class="flex flex-col justify-center" data-aos="fade-left">
                <ul
                    class="list-disc pl-5 md:pl-16 space-y-3 md:space-y-4 text-primary font-medium text-[15px] md:text-[20px] leading-relaxed">
                    @if (!empty($product->size_des))
                        @foreach ($product->size_des as $desc)
                            <li>{{ $desc }}</li>
                        @endforeach
                    @else
                        <li>Đang cập nhật thông tin kích thước kỹ thuật.</li>
                    @endif
                </ul>
            </div>
        </div>
    </section>

    <x-client.content.shared.outstanding-value />

    <!-- CÓ THỂ BẠN QUAN TÂM -->
    <x-client.catalog.shared.recommendations
        :related-products="$relatedProducts"
        route-name="client.products.lan-can-gom-su.detail"
        pk-field="lan_can_gom_su_ct_id"
        product-type="lan_can_gom_su_ct"
        :compare-table="true"
    />

    <!-- FAQ Section -->
    <x-client.shared.faq-cta-banner />
</x-client.layouts.main>
