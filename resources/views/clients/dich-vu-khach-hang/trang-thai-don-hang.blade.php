<x-layouts.client title="Trạng thái đơn hàng - Dịch vụ khách hàng" data-page="customer-service" main-class="bg-background-secondary min-h-screen">
<div class="w-[85%] max-w-[1320px] mx-auto">
  @include('clients.dich-vu-khach-hang.partials.customer-service.breadcrumb', ['currentPage' => 'Trạng thái đơn hàng'])
  <div class="flex flex-col lg:flex-row gap-12 mb-0 lg:mb-12">
    <!-- Sidebar -->
    @include('clients.dich-vu-khach-hang.partials.customer-service.sidebar', ['activeOrder' => true])

    <!-- Content Area -->
    @include('clients.dich-vu-khach-hang.partials.customer-service.order-status')
  </div>
</div>
<x-faq-faq-contact />
</x-layouts.client>