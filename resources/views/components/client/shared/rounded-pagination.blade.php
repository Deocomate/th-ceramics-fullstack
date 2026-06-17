@props(['paginator'])

@if ($paginator && method_exists($paginator, 'lastPage'))
@php
  $currentPage = $paginator->currentPage();
  $lastPage = $paginator->lastPage();
  $windowStart = max(2, $currentPage - 1);
  $windowEnd = min($lastPage - 1, $currentPage + 1);
  $pages = [1];

  if ($windowStart > 2) {
      $pages[] = '...';
  }

  for ($page = $windowStart; $page <= $windowEnd; $page++) {
      $pages[] = $page;
  }

  if ($windowEnd < $lastPage - 1) {
      $pages[] = '...';
  }

  if ($lastPage > 1) {
      $pages[] = $lastPage;
  }
@endphp
<nav
  class="flex items-center justify-between w-full mt-10 md:mt-16 text-[#101010] font-archivo font-medium text-sm"
  aria-label="Pagination"
>
  {{-- LEFT ARROW --}}
  @if ($paginator->onFirstPage())
  <span class="w-10 h-10 flex items-center justify-center opacity-30 cursor-not-allowed" aria-disabled="true">
    <img src="{{ asset('assets/images/pagination-left-arrow.png') }}" class="w-4 h-4 object-contain" alt="Previous">
  </span>
  @else
  <a href="{{ $paginator->previousPageUrl() }}" class="w-10 h-10 flex items-center justify-center hover:opacity-60 transition-opacity cursor-pointer" rel="prev">
    <img src="{{ asset('assets/images/pagination-left-arrow.png') }}" class="w-4 h-4 object-contain" alt="Previous">
  </a>
  @endif

  {{-- NUMBERS (centered, small 2px gap) --}}
  <div class="flex items-center gap-[2px] mx-auto">
    @foreach ($pages as $page)
      @if (is_string($page))
      <span class="w-10 h-10 flex items-center justify-center text-[#101010]/50 select-none">{{ $page }}</span>
      @elseif ($page == $currentPage)
      <span class="w-10 h-10 rounded-full flex items-center justify-center bg-[#F9FAFB] text-secondary font-semibold shadow-sm" aria-current="page">{{ $page }}</span>
      @else
      <a href="{{ $paginator->url($page) }}" class="w-10 h-10 rounded-full flex items-center justify-center text-[#101010] hover:text-secondary hover:bg-[#F9FAFB] transition-colors">{{ $page }}</a>
      @endif
    @endforeach
  </div>

  {{-- RIGHT ARROW --}}
  @if ($paginator->hasMorePages())
  <a href="{{ $paginator->nextPageUrl() }}" class="w-10 h-10 flex items-center justify-center hover:opacity-60 transition-opacity cursor-pointer" rel="next">
    <img src="{{ asset('assets/images/pagination-right-arrow.png') }}" class="w-4 h-4 object-contain" alt="Next">
  </a>
  @else
  <span class="w-10 h-10 flex items-center justify-center opacity-30 cursor-not-allowed" aria-disabled="true">
    <img src="{{ asset('assets/images/pagination-right-arrow.png') }}" class="w-4 h-4 object-contain" alt="Next">
  </span>
  @endif
</nav>
@endif
