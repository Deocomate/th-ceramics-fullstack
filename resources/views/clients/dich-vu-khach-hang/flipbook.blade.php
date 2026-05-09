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
    }
    .my-page canvas {
      display: block;
      width: 100%; height: 100%;
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

<script>
  document.addEventListener('DOMContentLoaded', async function() {
    const pdfUrl = @json(asset('storage/' . $catalog->file));
    const flipbookEl = document.getElementById('flipbook');
    const spinner = document.getElementById('spinner');

    try {
      const pdf = await pdfjsLib.getDocument(pdfUrl).promise;
      const totalPages = pdf.numPages;

      const pageDivs = [];
      for (let i = 1; i <= totalPages; i++) {
        const pageDiv = document.createElement('div');
        pageDiv.className = 'my-page';
        pageDiv.dataset.pageNumber = i;
        flipbookEl.appendChild(pageDiv);
        pageDivs.push(pageDiv);
      }

      const pageFlip = new St.PageFlip(flipbookEl, {
        width: 500,
        height: 700,
        size: 'stretch',
        minWidth: 315,
        maxWidth: 1000,
        minHeight: 420,
        maxHeight: 1350,
        showCover: true,
        mobileScrollSupport: false,
        usePortrait: window.innerWidth < 768,
      });
      pageFlip.loadFromHTML(document.querySelectorAll('.my-page'));

      spinner.style.display = 'none';

      for (let i = 1; i <= totalPages; i++) {
        pdf.getPage(i).then(page => {
          const viewport = page.getViewport({ scale: 1.5 });
          const canvas = document.createElement('canvas');
          canvas.width = viewport.width;
          canvas.height = viewport.height;

          const ctx = canvas.getContext('2d');
          page.render({ canvasContext: ctx, viewport: viewport }).promise.then(() => {
            pageDivs[i-1].appendChild(canvas);
          });
        });
      }

    } catch (err) {
      spinner.innerHTML = '<p style="color: #f87171;">Không thể tải catalog. Vui lòng thử lại sau.</p>';
      console.error('Flipbook error:', err);
    }
  });
</script>

</body>
</html>
