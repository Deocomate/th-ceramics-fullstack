<x-layouts.client title="Hướng dẫn thi công - Dịch vụ khách hàng" data-page="customer-service" main-class="bg-background-secondary min-h-screen font-archivo">
  <div class="w-[85%] max-w-[1320px] mx-auto">
    @include('clients.dich-vu-khach-hang.partials.customer-service.breadcrumb', ['currentPage' => 'Hướng dẫn thi công'])
    <div class="flex flex-col lg:flex-row gap-12 mb-6 lg:mb-12">
      @include('clients.dich-vu-khach-hang.partials.customer-service.sidebar', ['activeGuide' => true])
      @include('clients.dich-vu-khach-hang.partials.customer-service.installation-guide-content')
    </div>
  </div>
</x-layouts.client>