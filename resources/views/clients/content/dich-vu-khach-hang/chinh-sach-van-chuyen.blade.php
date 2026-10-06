<x-client.layouts.main title="Chính sách vận chuyển - Dịch vụ khách hàng" data-page="customer-service" main-class="bg-background-secondary min-h-screen font-archivo text-primary">
<div class="w-[85%] max-w-[1320px] mx-auto">
  <x-client.shared.breadcrumb :items="[
      ['label' => 'Trang chủ', 'url' => route('client.home')],
      ['label' => 'Dịch vụ khách hàng', 'url' => route('client.customer-service.show', 'trang-thai-don-hang')],
      ['label' => 'Chính sách vận chuyển'],
  ]" wrapper-class="py-10 lg:py-12" list-class="flex text-sm text-primary gap-2 lg:gap-4 items-center" link-class="hover:text-secondary transition-colors font-medium" current-class="text-primary font-normal underline decoration-primary underline-offset-4" separator-class="" separator-style="chevron" />
  <div class="flex flex-col lg:flex-row gap-12 mb-0 lg:mb-12">
    <x-client.shared.customer-service.sidebar :active-shipping="true" />
    <x-client.content.customer-service.shipping-policy-content />
  </div>
</div>
<x-client.shared.faq-contact />
</x-client.layouts.main>
