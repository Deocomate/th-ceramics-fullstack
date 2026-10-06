<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>{{ $catalog->tieu_de ?? 'Catalog' }} — Gốm sứ Thanh Hải</title>

  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.16.105/pdf.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/page-flip@2.0.7/dist/js/page-flip.browser.min.js"></script>

  <style>
    *, *::before, *::after { box-sizing: border-box; }

    #flipbook-viewport {
      overflow: visible;
      padding: 24px;
      scrollbar-width: none; /* Firefox */
      -ms-overflow-style: none; /* IE/Edge */
    }

    #flipbook-viewport::-webkit-scrollbar {
      display: none; /* Chrome/Safari/Opera */
    }

    #flipbook-viewport.is-zoomed {
      overflow: hidden;
      cursor: grab;
    }

    #flipbook-viewport.is-panning {
      cursor: grabbing;
    }

    #flipbook-zoom-wrap {
      margin: 0 auto;
    }

    #flipbook {
      transform-origin: center center;
      transition: transform 0.25s ease;
      overflow: visible;
    }

    .my-page {
      background: #fff;
      box-shadow: 0 0 12px rgba(0, 0, 0, 0.12);
      display: flex;
      align-items: center;
      justify-content: center;
      overflow: hidden;
    }

    .my-page canvas {
      display: block;
    }

    .my-page .page-loading {
      width: 28px;
      height: 28px;
      border: 2px solid rgba(0, 0, 0, 0.08);
      border-top-color: #ab6520;
      border-radius: 50%;
      animation: spin 0.8s linear infinite;
    }

    @keyframes spin { to { transform: rotate(360deg); } }

    .nav-btn:disabled {
      opacity: 0.35;
      cursor: not-allowed;
      pointer-events: none;
    }
  </style>
</head>
<body class="min-h-screen flex flex-col bg-[#faf9f6] text-stone-800 relative overflow-hidden select-none">

  {{-- Loading overlay --}}
  <div class="fixed inset-0 z-[200] flex flex-col items-center justify-center gap-4 bg-[#faf9f6] text-stone-700" id="spinner">
    <div class="w-10 h-10 border-[3px] border-stone-200 border-t-[#ab6520] rounded-full animate-spin"></div>
    <p class="text-sm font-medium">Đang tải catalog...</p>
  </div>

  {{-- Header --}}
  <header class="fixed top-0 inset-x-0 h-16 bg-white/80 backdrop-blur-md border-b border-stone-200/60 z-50 flex items-center justify-between px-4 sm:px-6 gap-4">
    <div class="min-w-0 flex-1">
      <h1 class="text-sm sm:text-base font-semibold text-stone-900 truncate">{{ $catalog->tieu_de ?? 'Catalog' }}</h1>
      <p class="text-xs text-stone-500 mt-0.5">
        Trang: <span id="page-num">1</span> / <span id="page-count">0</span>
      </p>
    </div>
    <div class="flex items-center gap-2 shrink-0">
      <a href="{{ route('client.dich-vu.tai-catalog') }}"
         class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs sm:text-sm font-medium text-stone-700 bg-white border border-stone-200 rounded-md hover:bg-stone-50 hover:border-stone-300 transition-colors">
        <i data-lucide="arrow-left" class="w-4 h-4"></i>
        <span class="hidden sm:inline">Quay lại</span>
      </a>
    </div>
  </header>

  {{-- Main content --}}
  <main class="flex-1 flex items-center justify-center relative p-4 mt-16 mb-16 min-h-0">
    <button id="prev-btn" type="button" aria-label="Trang trước"
            class="nav-btn hidden md:flex absolute left-4 lg:left-6 z-40 w-11 h-11 items-center justify-center rounded-full bg-white border border-stone-200 shadow-md text-stone-600 hover:text-[#ab6520] hover:border-[#ab6520]/30 hover:shadow-lg active:scale-95 transition-all">
      <i data-lucide="chevron-left" class="w-5 h-5"></i>
    </button>

    <div id="flipbook-viewport" class="w-full h-full relative flex items-center justify-center">
      <div id="flipbook-zoom-wrap" class="relative">
        <div id="flipbook" class="shadow-2xl shadow-stone-400/40 rounded-sm"></div>
      </div>
    </div>

    <button id="next-btn" type="button" aria-label="Trang sau"
            class="nav-btn hidden md:flex absolute right-4 lg:right-6 z-40 w-11 h-11 items-center justify-center rounded-full bg-white border border-stone-200 shadow-md text-stone-600 hover:text-[#ab6520] hover:border-[#ab6520]/30 hover:shadow-lg active:scale-95 transition-all">
      <i data-lucide="chevron-right" class="w-5 h-5"></i>
    </button>
  </main>

  {{-- Footer controls --}}
  <footer class="fixed bottom-0 inset-x-0 h-16 bg-white/80 backdrop-blur-md border-t border-stone-200/60 z-50 flex items-center justify-center px-4 sm:px-6">
    <div class="flex items-center gap-2 sm:gap-3">
      <button id="zoom-out-btn" type="button" aria-label="Thu nhỏ"
              class="w-8 h-8 flex items-center justify-center rounded-md text-stone-600 hover:bg-stone-100 hover:text-[#ab6520] active:scale-95 transition-all">
        <i data-lucide="zoom-out" class="w-4.5 h-4.5"></i>
      </button>

      <input type="range" id="zoom-slider" min="100" max="200" step="5" value="100"
             class="w-24 sm:w-32 h-1 bg-stone-200 rounded-lg appearance-none cursor-pointer accent-[#ab6520] focus:outline-none">

      <span id="zoom-level" class="text-xs text-stone-500 w-10 text-center tabular-nums">100%</span>

      <button id="zoom-in-btn" type="button" aria-label="Phóng to"
              class="w-8 h-8 flex items-center justify-center rounded-md text-stone-600 hover:bg-stone-100 hover:text-[#ab6520] active:scale-95 transition-all">
        <i data-lucide="zoom-in" class="w-4.5 h-4.5"></i>
      </button>

      <div class="w-px h-6 bg-stone-200 mx-1 sm:mx-2"></div>

      <button id="fullscreen-btn" type="button" aria-label="Toàn màn hình"
              class="w-10 h-10 flex items-center justify-center rounded-md text-stone-600 hover:bg-stone-100 hover:text-[#ab6520] active:scale-95 transition-all">
        <i data-lucide="maximize" class="w-5 h-5" id="fullscreen-icon"></i>
      </button>
    </div>
  </footer>

  <script src="https://unpkg.com/lucide@0.468.0"></script>
  <script src="{{ asset('assets/js/catalog-flipbook.js') }}"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      if (typeof lucide !== 'undefined') {
        lucide.createIcons();
      }
      initCatalogFlipbook(@json(asset('storage/' . $catalog->file)));
    });
  </script>

</body>
</html>
