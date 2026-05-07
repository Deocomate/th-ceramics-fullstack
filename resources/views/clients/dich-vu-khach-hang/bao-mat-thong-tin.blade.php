<x-layouts.client title="Bảo mật thông tin - Dịch vụ khách hàng" data-page="customer-service" main-class="bg-background-secondary min-h-screen font-archivo text-primary">
<div class="w-[85%] max-w-[1320px] mx-auto">
  @include('clients.dich-vu-khach-hang.partials.customer-service.breadcrumb', ['currentPage' => 'Bảo mật thông tin'])
  <div class="flex flex-col lg:flex-row gap-12 mb-6 lg:mb-12">
    @include('clients.dich-vu-khach-hang.partials.customer-service.sidebar', ['activePrivacy' => true])
    @include('clients.dich-vu-khach-hang.partials.customer-service.privacy-policy-content')
  </div>
</div>
<x-faq-faq-contact />
</x-layouts.client>