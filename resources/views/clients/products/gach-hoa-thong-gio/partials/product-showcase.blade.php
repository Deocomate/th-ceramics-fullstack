<!-- Breadcrumb + Product Grid -->
<section class="w-full gach-hoa-product-showcase-section">
  <div
    class="w-[85%] max-w-[1320px] mx-auto pt-6 pb-3 md:pb-6 md:pt-8 relative z-10"
  >
    <x-products.breadcrumb current-label="Gạch Hoa Thông Gió" />
  </div>

  <div class="gach-hoa-product-grid-shell">
    <x-products.product-filter />
    <x-products.product-grid :products="$products" category="gach-hoa-thong-gio" routeName="client.products.gach-hoa-thong-gio.detail" />
  </div>
</section>