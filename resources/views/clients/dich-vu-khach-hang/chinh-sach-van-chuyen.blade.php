<x-layouts.client title="Chính sách vận chuyển - Dịch vụ khách hàng" data-page="customer-service" main-class="bg-background-secondary min-h-screen font-archivo text-primary">
<div class="w-[85%] max-w-[1320px] mx-auto">
  @include('clients.dich-vu-khach-hang.partials.customer-service.breadcrumb', ['currentPage' => 'Chính sách vận chuyển'])
  <div class="flex flex-col lg:flex-row gap-12 mb-4 lg:mb-8">
    @include('clients.dich-vu-khach-hang.partials.customer-service.sidebar', ['activeShipping' => true])
    @include('clients.dich-vu-khach-hang.partials.customer-service.shipping-policy-content')
  </div>
</div>
<x-faq-faq-contact />
</x-layouts.client>