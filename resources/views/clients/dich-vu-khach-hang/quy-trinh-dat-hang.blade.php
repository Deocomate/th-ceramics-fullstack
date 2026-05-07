<x-layouts.client title="Quy trình đặt hàng - Dịch vụ khách hàng" data-page="customer-service" main-class="bg-background-secondary min-h-screen font-archivo">
  <div class="w-[85%] max-w-[1320px] mx-auto">
    @include('clients.dich-vu-khach-hang.partials.customer-service.breadcrumb', ['currentPage' => 'Quy trình đặt hàng'])
    <div class="flex flex-col lg:flex-row gap-12 mb-0 lg:mb-12">
      @include('clients.dich-vu-khach-hang.partials.customer-service.sidebar', ['activeProcess' => true])
      @include('clients.dich-vu-khach-hang.partials.customer-service.order-process-content')
    </div>
  </div>
  <x-faq-faq-contact />
</x-layouts.client>