<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>{{ $catalog->tieu_de ?? 'Catalog' }} — Gốm sứ Thanh Hải</title>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.16.105/pdf.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/page-flip@2.0.7/dist/js/page-flip.browser.min.js"></script>

  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    body {
      background: #1a1a2e;
      display: flex; flex-direction: column; align-items: center; justify-content: center;
      min-height: 100vh; overflow-x: hidden;
      font-family: system-ui, -apple-system, sans-serif;
    }
    .back-link {
      position: fixed; top: 16px; left: 16px; z-index: 100;
      color: #fff; text-decoration: none; font-size: 14px;
      padding: 8px 16px; border: 1px solid rgba(255,255,255,0.3); border-radius: 4px;
      transition: all 0.2s;
    }
    .back-link:hover { background: rgba(255,255,255,0.1); border-color: rgba(255,255,255,0.6); }
    .spinner-overlay {
      position: fixed; inset: 0; z-index: 200;
      display: flex; flex-direction: column; align-items: center; justify-content: center;
      background: #1a1a2e; color: #fff; gap: 16px;
    }
    .spinner {
      width: 40px; height: 40px;
      border: 3px solid rgba(255,255,255,0.2);
      border-top-color: #fff; border-radius: 50%;
      animation: spin 0.8s linear infinite;
    }
    @keyframes spin { to { transform: rotate(360deg); } }
    #flipbook {
      margin: 20px auto;
      overflow: visible;
    }
    .my-page {
      background: #fff;
      box-shadow: 0 0 12px rgba(0,0,0,0.3);
      display: flex;
      align-items: center;
      justify-content: center;
      overflow: hidden;
    }
    .my-page canvas {
      display: block;
      width: 100%;
      height: auto;
    }
    .my-page .page-loading {
      width: 28px;
      height: 28px;
      border: 2px solid rgba(0, 0, 0, 0.08);
      border-top-color: rgba(0, 0, 0, 0.35);
      border-radius: 50%;
      animation: spin 0.8s linear infinite;
    }
  </style>
</head>
<body>

<a href="{{ route('client.dich-vu.tai-catalog') }}" class="back-link">&larr; Quay lại</a>

<div class="spinner-overlay" id="spinner">
  <div class="spinner"></div>
  <p>Đang tải catalog...</p>
</div>

<div id="flipbook"></div>

<script src="{{ asset('assets/js/catalog-flipbook.js') }}"></script>
<script>
  document.addEventListener('DOMContentLoaded', function () {
    initCatalogFlipbook(@json(asset('storage/' . $catalog->file)));
  });
</script>

</body>
</html>
