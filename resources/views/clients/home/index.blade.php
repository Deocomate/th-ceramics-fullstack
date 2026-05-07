<x-layouts.client title="Trang chủ" data-page="index" main-class="bg-white">
    @include('clients.home.partials.banner')
    @include('clients.home.partials.works')
    @include('clients.home.partials.partners')
    @include('clients.home.partials.products-ngoi-am-duong')
    @include('clients.home.partials.products-ngoi-hai')
    @include('clients.home.partials.products-gach-hoa')
    @include('clients.home.partials.thank-you')

    <section class="bg-neutral-2 pt-8 lg:pt-20 overflow-hidden">
        <div class="w-[85%] max-w-[1320px] mx-auto mb-5 lg:mb-12">
            <h2
                class="text-center text-secondary text-[20px] leading-[24px] md:text-left lg:text-left lg:text-4xl font-bold uppercase"
                data-aos="fade-up">
                Giải thưởng & thành tựu
            </h2>
        </div>
        <x-home-awards />
    </section>

    @include('clients.home.partials.press')
    @include('clients.home.partials.why-choose-us')
    @include('clients.home.partials.showroom')
    <x-newsletter />
</x-layouts.client>