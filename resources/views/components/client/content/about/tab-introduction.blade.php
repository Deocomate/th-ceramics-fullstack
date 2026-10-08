@props(['about' => null])
<!-- Về gốm sứ Thanh Hải -->
<div
  id="tab-introduction"
  class="tab-content block animate-fade-in-up w-full"
>
  <div class="w-[85%] lg:w-[85%] max-w-[1320px] mx-auto md:px-4">
  <x-client.content.about.intro-story :about="$about ?? null" />
  <x-client.content.about.core-values :about="$about ?? null" />
  <x-client.content.about.timeline :about="$about ?? null" />
  <x-client.content.about.founders :about="$about ?? null" />
  </div>
  <x-client.content.about.awards-section :about="$about ?? null" />
  <x-client.content.about.certificates-slider :about="$about ?? null" />
</div>
