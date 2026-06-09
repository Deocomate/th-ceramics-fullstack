@props([
    'products' => null,
])
<!-- Breadcrumb + Product Grid -->
<section class="w-full gach-hoa-product-showcase-section">
  <!-- BREADCRUMB & PRODUCT FILTER -->
  <x-client.shared.product-breadcrumb-filter current-label="Gạch Hoa Thông Gió" />

  <div class="gach-hoa-product-grid-shell">
    <x-client.shared.product-grid :products="$products" category="gach-hoa-thong-gio" routeName="client.products.gach-hoa-thong-gio.detail" />
  </div>
</section>