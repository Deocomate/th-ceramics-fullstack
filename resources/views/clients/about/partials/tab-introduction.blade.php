<!-- Về gốm sứ Thanh Hải -->
<div
  id="tab-introduction"
  class="tab-content block animate-fade-in-up w-full"
>
  <div class="w-[85%] lg:w-[85%] max-w-[1320px] mx-auto md:px-4">
  @include('clients.about.partials.intro-story', ['about' => $about ?? null])
  @include('clients.about.partials.core-values', ['about' => $about ?? null])
  @include('clients.about.partials.journey', ['about' => $about ?? null])
  @include('clients.about.partials.founders', ['about' => $about ?? null])
  </div>
  @include('clients.about.partials.awards', ['about' => $about ?? null])
  @include('clients.about.partials.certificates', ['about' => $about ?? null])
</div>
