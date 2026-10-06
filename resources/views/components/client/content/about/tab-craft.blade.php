@props(['about' => null])
<!-- Nghệ thuật thủ công -->
<div
  id="tab-craft"
  class="tab-content hidden animate-fade-in-up w-full"
>
  <div class="w-[85%] max-w-[1320px] mx-auto md:px-4 tab-craft-copy">
  <x-client.content.about.craft-intro :about="$about ?? null" />
  <x-client.content.about.craft-material :about="$about ?? null" />
  <x-client.content.about.craft-skills :about="$about ?? null" />
  </div>
</div>
