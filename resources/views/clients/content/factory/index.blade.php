<x-client.layouts.main title="Xưởng sản xuất" data-page="factory" main-class="bg-[#F5EDE8] pb-14 lg:pb-20">
    <x-client.content.factory.hero-banner :factory="$factory ?? null" />
    <x-client.content.factory.intro-description :factory="$factory ?? null" />
    <x-client.content.factory.gallery-primary :factory="$factory ?? null" />
    <x-client.content.factory.manufacturing-process :factory="$factory ?? null" />
    <x-client.content.factory.material-selection :factory="$factory ?? null" />
    <x-client.content.factory.gallery-secondary :factory="$factory ?? null" />
    <x-client.shared.faq-cta-banner />
</x-client.layouts.main>
