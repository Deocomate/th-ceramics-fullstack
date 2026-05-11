<!-- Nghệ thuật thủ công -->
<div
  id="tab-craft"
  class="tab-content hidden animate-fade-in-up w-full"
>
  <div class="w-[85%] max-w-[1320px] mx-auto md:px-4 tab-craft-copy">
  @include('clients.about.partials.craft-intro', ['about' => $about ?? null])
  @include('clients.about.partials.craft-material', ['about' => $about ?? null])
  @include('clients.about.partials.craft-skills', ['about' => $about ?? null])
  </div>
</div>
