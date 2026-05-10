<!-- Pagination -->
@if($projects->hasPages())
<div
  class="flex items-center justify-between gap-8 md:gap-12"
  data-aos="fade-up"
>
  {{-- Previous --}}
  @if($projects->onFirstPage())
    <span class="text-primary/30 cursor-not-allowed" aria-label="Previous Page">
      <svg fill="currentColor" class="w-5 h-5" viewBox="0 0 24 24">
        <path d="M20 11H7.83l5.59-5.59L12 4l-8 8 8 8 1.41-1.41L7.83 13H20v-2z"></path>
      </svg>
    </span>
  @else
    <a href="{{ $projects->previousPageUrl() }}" class="text-primary hover:text-secondary transition-colors" aria-label="Previous Page">
      <svg fill="currentColor" class="w-5 h-5" viewBox="0 0 24 24">
        <path d="M20 11H7.83l5.59-5.59L12 4l-8 8 8 8 1.41-1.41L7.83 13H20v-2z"></path>
      </svg>
    </a>
  @endif

  <div class="flex items-center gap-6">
    @foreach($projects->getUrlRange(1, $projects->lastPage()) as $page => $url)
      @if($page == $projects->currentPage())
        <span class="w-10 h-10 flex items-center justify-center text-sm font-archivo font-bold bg-white text-secondary rounded-full shadow-sm cursor-pointer border border-[#FFFAF3]">{{ $page }}</span>
      @else
        <a href="{{ $url }}" class="text-sm font-archivo font-bold text-primary hover:text-secondary cursor-pointer">{{ $page }}</a>
      @endif
    @endforeach
  </div>

  {{-- Next --}}
  @if($projects->hasMorePages())
    <a href="{{ $projects->nextPageUrl() }}" class="text-primary hover:text-secondary transition-colors" aria-label="Next Page">
      <svg fill="currentColor" class="w-5 h-5" viewBox="0 0 24 24">
        <path d="M12 4l-1.41 1.41L16.17 11H4v2h12.17l-5.58 5.59L12 20l8-8z"></path>
      </svg>
    </a>
  @else
    <span class="text-primary/30 cursor-not-allowed" aria-label="Next Page">
      <svg fill="currentColor" class="w-5 h-5" viewBox="0 0 24 24">
        <path d="M12 4l-1.41 1.41L16.17 11H4v2h12.17l-5.58 5.59L12 20l8-8z"></path>
      </svg>
    </span>
  @endif
</div>
@endif
