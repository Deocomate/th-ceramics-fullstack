(function () {
  'use strict';

  // A PDF page at least this wide relative to its height is a two-page spread.
  const SPREAD_RATIO = 1.25;
  const START_QUALITY = 0.85;
  const MIN_QUALITY = 0.45;
  const QUALITY_STEP = 0.1;
  const SHRINK_FACTOR = 0.85;
  const MIN_EDGE = 600;
  const UPLOAD_RETRIES = 3;
  const WORKER_SRC = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.16.105/pdf.worker.min.js';

  const dataset = (document.currentScript && document.currentScript.dataset) || {};
  const limits = {
    maxEdge: Number(dataset.maxEdge) || 2000,
    maxBytes: Number(dataset.maxBytes) || 1000000,
    maxPages: Number(dataset.maxPages) || 400,
  };

  /**
   * Turn PDF page sizes into the ordered list of flipbook pages. A spread becomes a
   * left and a right page, each half the width of the PDF page.
   */
  function planPages(pageSizes) {
    const plan = [];

    pageSizes.forEach(function (size, index) {
      const pdfPage = index + 1;

      if (size.width / size.height >= SPREAD_RATIO) {
        plan.push({ pdfPage: pdfPage, side: 'left', width: size.width / 2, height: size.height });
        plan.push({ pdfPage: pdfPage, side: 'right', width: size.width / 2, height: size.height });
      } else {
        plan.push({ pdfPage: pdfPage, side: 'full', width: size.width, height: size.height });
      }
    });

    return plan;
  }

  function toBlob(canvas, quality) {
    return new Promise(function (resolve) {
      canvas.toBlob(resolve, 'image/webp', quality);
    });
  }

  async function renderEntry(page, entry, edge) {
    const scale = edge / Math.max(entry.width, entry.height);
    const canvas = document.createElement('canvas');
    canvas.width = Math.max(1, Math.round(entry.width * scale));
    canvas.height = Math.max(1, Math.round(entry.height * scale));

    const context = canvas.getContext('2d');
    context.fillStyle = '#fff';
    context.fillRect(0, 0, canvas.width, canvas.height);

    // The right half of a spread is the same render shifted left by one page width.
    const offsetX = entry.side === 'right' ? -canvas.width : 0;
    // PDF.js paces the default intent with animation frames, which stop while the tab is in
    // the background; the print intent renders straight through so a run never stalls there.
    await page.render({
      intent: 'print',
      canvasContext: context,
      viewport: page.getViewport({ scale: scale }),
      transform: [1, 0, 0, 1, offsetX, 0],
    }).promise;

    return canvas;
  }

  function releaseCanvas(canvas) {
    canvas.width = 0;
    canvas.height = 0;
  }

  async function encodeEntry(page, entry) {
    let edge = limits.maxEdge;

    for (;;) {
      const canvas = await renderEntry(page, entry, edge);

      try {
        for (let quality = START_QUALITY; quality >= MIN_QUALITY - 0.001; quality -= QUALITY_STEP) {
          const blob = await toBlob(canvas, Math.round(quality * 100) / 100);

          if (!blob || blob.type !== 'image/webp') {
            throw new Error('Trình duyệt này không mã hóa được ảnh WebP. Hãy dùng Chrome, Edge hoặc Firefox bản mới.');
          }
          if (blob.size <= limits.maxBytes) {
            return blob;
          }
        }
      } finally {
        releaseCanvas(canvas);
      }

      if (edge <= MIN_EDGE) {
        throw new Error('Không nén được trang ' + entry.pdfPage + ' xuống dưới giới hạn dung lượng.');
      }
      edge = Math.max(MIN_EDGE, Math.round(edge * SHRINK_FACTOR));
    }
  }

  function wait(milliseconds) {
    return new Promise(function (resolve) {
      setTimeout(resolve, milliseconds);
    });
  }

  async function serverMessage(response) {
    try {
      const body = await response.json();
      const errors = body.errors ? Object.values(body.errors) : [];

      return (errors[0] && errors[0][0]) || body.message || '';
    } catch (error) {
      return '';
    }
  }

  // Network failures and server errors are retried; a rejected request is not.
  async function send(url, body, csrfToken, headers) {
    for (let attempt = 0; ; attempt++) {
      let response = null;

      try {
        response = await fetch(url, {
          method: 'POST',
          body: body,
          credentials: 'same-origin',
          headers: Object.assign({
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': csrfToken,
          }, headers || {}),
        });
      } catch (error) {
        response = null;
      }

      if (response && response.ok) {
        return response;
      }
      if (response && response.status < 500) {
        throw new Error((await serverMessage(response)) || 'Máy chủ từ chối ảnh trang (mã ' + response.status + ').');
      }
      if (attempt >= UPLOAD_RETRIES) {
        throw new Error('Lỗi mạng khi tải ảnh trang lên. Hãy kiểm tra kết nối rồi bấm "Tạo ảnh trang" để thử lại.');
      }
      await wait(1000 * (attempt + 1));
    }
  }

  /**
   * Render every page of a PDF, upload the images one at a time and publish the batch.
   * Readers keep the previous images until the last step succeeds.
   *
   * @param {{source: string, pagesUrl: string, finalizeUrl: string, csrfToken: string, onProgress?: function(number, number): void}} options
   */
  async function generate(options) {
    pdfjsLib.GlobalWorkerOptions.workerSrc = WORKER_SRC;
    const pdf = await pdfjsLib.getDocument({ url: options.source, withCredentials: true }).promise;

    try {
      if (pdf.numPages > limits.maxPages) {
        throw new Error('File PDF có ' + pdf.numPages + ' trang, vượt giới hạn ' + limits.maxPages + ' trang.');
      }

      const sizes = [];
      for (let number = 1; number <= pdf.numPages; number++) {
        const viewport = (await pdf.getPage(number)).getViewport({ scale: 1 });
        sizes.push({ width: viewport.width, height: viewport.height });
      }

      const plan = planPages(sizes);
      const batch = window.crypto.randomUUID();

      for (let index = 0; index < plan.length; index++) {
        const entry = plan[index];
        const page = await pdf.getPage(entry.pdfPage);
        const blob = await encodeEntry(page, entry);

        if (entry.side !== 'left') {
          page.cleanup();
        }

        const form = new FormData();
        form.append('batch', batch);
        form.append('index', String(index));
        form.append('total', String(plan.length));
        form.append('image', blob, 'page.webp');
        await send(options.pagesUrl, form, options.csrfToken);

        if (options.onProgress) {
          options.onProgress(index + 1, plan.length);
        }
      }

      await send(options.finalizeUrl, JSON.stringify({
        batch: batch,
        pages: plan.map(function (entry) {
          return { pdf_page: entry.pdfPage, side: entry.side };
        }),
      }), options.csrfToken, { 'Content-Type': 'application/json' });

      return plan.length;
    } finally {
      pdf.destroy();
    }
  }

  window.AdminCatalogPageRenderer = { planPages: planPages, generate: generate };
})();
