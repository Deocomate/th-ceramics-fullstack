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
  class="flex items-center justify-between gap-6 mt-[40px] md:mt-16 text-textPrimary font-bold text-[17px]"
  aria-label="Pagination"
>
  @if ($paginator->onFirstPage())
  <span class="w-10 h-10 border-2 border-black/10 rounded-full flex items-center justify-center text-black/20 cursor-not-allowed" aria-disabled="true">
    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
  </span>
  @else
  <a href="{{ $paginator->previousPageUrl() }}" class="w-10 h-10 border-2 border-black/20 rounded-full flex items-center justify-center text-black/40 hover:border-black hover:text-black transition-all cursor-pointer" rel="prev">
    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
  </a>
  @endif

  <div class="flex items-center gap-5">
    @foreach ($pages as $page)
      @if (is_string($page))
      <span class="text-black/40 tracking-widest px-1">{{ $page }}</span>
      @elseif ($page == $currentPage)
      <span class="text-black border-b-[3px] border-black pb-[2px] px-1" aria-current="page">{{ $page }}</span>
      @else
      <a href="{{ $paginator->url($page) }}" class="text-black/40 hover:text-black transition-colors px-1">{{ $page }}</a>
      @endif
    @endforeach
  </div>

  @if ($paginator->hasMorePages())
  <a href="{{ $paginator->nextPageUrl() }}" class="w-10 h-10 border-2 border-black/20 rounded-full flex items-center justify-center text-black/40 hover:border-black hover:text-black transition-all cursor-pointer" rel="next">
    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
  </a>
  @else
  <span class="w-10 h-10 border-2 border-black/10 rounded-full flex items-center justify-center text-black/20 cursor-not-allowed" aria-disabled="true">
    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
  </span>
  @endif
</nav>
@endif
