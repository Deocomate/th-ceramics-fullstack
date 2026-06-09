@props([
    'config' => null,
])
<div class="mt-[20px] md:mt-auto mb-[30px] md:mb-20 gach-hoa-process-section">
    <x-client.shared.custom-design-process :images="$config->process_images ?? []" />
</div>
