<x-client.layouts.main title="Tải Catalog - Dịch vụ khách hàng" data-page="customer-service" main-class="bg-background-secondary min-h-screen font-archivo">
  <div class="w-[85%] max-w-[1320px] mx-auto">
    <x-client.shared.breadcrumb :items="[
        ['label' => 'Trang chủ', 'url' => route('client.home')],
        ['label' => 'Dịch vụ khách hàng', 'url' => route('client.customer-service.show', 'trang-thai-don-hang')],
        ['label' => 'Tải Catalog'],
    ]" wrapper-class="py-10 lg:py-12" list-class="flex text-sm text-primary gap-2 lg:gap-4 items-center" link-class="hover:text-secondary transition-colors font-medium" current-class="text-primary font-normal underline decoration-primary underline-offset-4" separator-class="" separator-style="chevron" />
    <div class="flex flex-col lg:flex-row gap-12 mb-6 lg:mb-12">
      <x-client.customer-service.sidebar :active-catalog="true" />
      <x-client.customer-service.catalog-content :featured-catalog="$featuredCatalog" :catalogs="$catalogs" />
    </div>
  </div>
  <x-client.shared.faq-contact />
</x-client.layouts.main>
