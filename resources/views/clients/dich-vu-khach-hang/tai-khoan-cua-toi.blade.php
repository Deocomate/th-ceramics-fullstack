<x-layouts.client title="Tài khoản của tôi - Dịch vụ khách hàng" data-page="customer-service" main-class="bg-background-secondary min-h-screen">
  <div class="w-[85%] max-w-[1320px] mx-auto">
    @include('clients.dich-vu-khach-hang.partials.customer-service.breadcrumb', ['currentPage' => 'Tài khoản của tôi'])
    <div class="flex flex-col lg:flex-row gap-12 mb-6 lg:mb-12">
      @include('clients.dich-vu-khach-hang.partials.customer-service.sidebar', ['activeAccount' => true])
      @include('clients.dich-vu-khach-hang.partials.customer-service.account-content')
    </div>
  </div>
  <x-faq-faq-contact />
</x-layouts.client>