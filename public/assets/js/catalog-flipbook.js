(function () {
  'use strict';

  const SPREAD_RATIO = 1.25;
  const PRELOAD_AHEAD = 5;
  const PRELOAD_BEHIND = 1;
  const BACKGROUND_CONCURRENCY = 2;

  pdfjsLib.GlobalWorkerOptions.workerSrc =
    'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.16.105/pdf.worker.min.js';

  async function analyzePdf(pdf) {
    const flipPlan = [];
    let maxUnitW = 0;
    let maxUnitH = 0;

    for (let i = 1; i <= pdf.numPages; i++) {
      const page = await pdf.getPage(i);
      const viewport = page.getViewport({ scale: 1 });
      const ratio = viewport.width / viewport.height;
      const isSpread = ratio >= SPREAD_RATIO;

      const meta = {
        pdfPageNumber: i,
        pdfWidth: viewport.width,
        pdfHeight: viewport.height,
        isSpread,
      };

      if (isSpread) {
        flipPlan.push({ ...meta, side: 'left' });
        flipPlan.push({ ...meta, side: 'right' });
        maxUnitW = Math.max(maxUnitW, viewport.width / 2);
        maxUnitH = Math.max(maxUnitH, viewport.height);
      } else {
        flipPlan.push({ ...meta, side: 'full' });
        maxUnitW = Math.max(maxUnitW, viewport.width);
        maxUnitH = Math.max(maxUnitH, viewport.height);
      }
    }

    return { flipPlan, maxUnitW, maxUnitH };
  }

  function calcFlipDimensions(maxUnitW, maxUnitH) {
    const isMobile = window.innerWidth < 768;
    const maxW = isMobile
      ? window.innerWidth * 0.88
      : Math.min(window.innerWidth * 0.42, 520);
    const maxH = window.innerHeight * 0.82;
    const scale = Math.min(maxW / maxUnitW, maxH / maxUnitH);

    return {
      flipW: Math.round(maxUnitW * scale),
      flipH: Math.round(maxUnitH * scale),
    };
  }

  function renderPageToCanvas(page, canvas, entry, flipW, flipH) {
    const ctx = canvas.getContext('2d');
    canvas.width = flipW;
    canvas.height = flipH;
    ctx.fillStyle = '#fff';
    ctx.fillRect(0, 0, flipW, flipH);

    const { side, pdfWidth, pdfHeight } = entry;
    const isSpread = side === 'left' || side === 'right';
    const contentW = isSpread ? pdfWidth / 2 : pdfWidth;
    const contentH = pdfHeight;
    const renderScale = Math.min(flipW / contentW, flipH / contentH);
    const viewport = page.getViewport({ scale: renderScale });
    const renderedH = viewport.height;
    const offsetY = (flipH - renderedH) / 2;

    let offsetX = 0;

    if (side === 'full') {
      offsetX = (flipW - viewport.width) / 2;
    } else if (side === 'left') {
      const halfRenderedW = (pdfWidth / 2) * renderScale;
      offsetX = (flipW - halfRenderedW) / 2;
    } else if (side === 'right') {
      const halfRenderedW = (pdfWidth / 2) * renderScale;
      offsetX = -(pdfWidth / 2) * renderScale + (flipW - halfRenderedW) / 2;
    }

    return page.render({
      canvasContext: ctx,
      viewport,
      transform: [1, 0, 0, 1, offsetX, offsetY],
    }).promise;
  }

  function showPagePlaceholder(pageDiv) {
    if (pageDiv.querySelector('canvas') || pageDiv.querySelector('.page-loading')) {
      return;
    }

    const placeholder = document.createElement('div');
    placeholder.className = 'page-loading';
    placeholder.setAttribute('aria-hidden', 'true');
    pageDiv.appendChild(placeholder);
  }

  function removePagePlaceholder(pageDiv) {
    pageDiv.querySelector('.page-loading')?.remove();
  }

  async function renderFlipEntry(pdf, pageDiv, entry, flipW, flipH) {
    if (pageDiv.querySelector('canvas')) {
      return;
    }

    showPagePlaceholder(pageDiv);

    const page = await pdf.getPage(entry.pdfPageNumber);
    const canvas = document.createElement('canvas');
    await renderPageToCanvas(page, canvas, entry, flipW, flipH);
    removePagePlaceholder(pageDiv);
    pageDiv.appendChild(canvas);
  }

  function createPageRenderer(pdf, pageDivs, flipPlan, flipW, flipH) {
    const rendered = new Set();
    const rendering = new Map();

    async function renderIndex(index) {
      if (index < 0 || index >= flipPlan.length) {
        return;
      }

      if (rendered.has(index)) {
        return;
      }

      if (rendering.has(index)) {
        return rendering.get(index);
      }

      const promise = renderFlipEntry(
        pdf,
        pageDivs[index],
        flipPlan[index],
        flipW,
        flipH
      )
        .then(function () {
          rendered.add(index);
        })
        .finally(function () {
          rendering.delete(index);
        });

      rendering.set(index, promise);
      return promise;
    }

    function preloadAround(currentIndex) {
      const tasks = [];

      for (
        let i = currentIndex - PRELOAD_BEHIND;
        i <= currentIndex + PRELOAD_AHEAD;
        i++
      ) {
        tasks.push(renderIndex(i));
      }

      return Promise.all(tasks);
    }

    function startBackgroundLoad(priorityIndex) {
      const pending = [];

      for (let i = 0; i < flipPlan.length; i++) {
        if (rendered.has(i) || rendering.has(i)) {
          continue;
        }

        pending.push(i);
      }

      pending.sort(function (a, b) {
        return Math.abs(a - priorityIndex) - Math.abs(b - priorityIndex);
      });

      let cursor = 0;

      async function worker() {
        while (cursor < pending.length) {
          const index = pending[cursor];
          cursor += 1;
          await renderIndex(index);
        }
      }

      const workers = Math.min(BACKGROUND_CONCURRENCY, pending.length);
      const jobs = [];

      for (let w = 0; w < workers; w++) {
        jobs.push(worker());
      }

      return Promise.all(jobs).catch(function (err) {
        console.error('Background page load error:', err);
      });
    }

    return {
      renderIndex: renderIndex,
      preloadAround: preloadAround,
      startBackgroundLoad: startBackgroundLoad,
    };
  }

  function showError(spinner, message) {
    spinner.innerHTML =
      '<p style="color: #f87171;">' + message + '</p>';
  }

  async function initCatalogFlipbook(pdfUrl) {
    const flipbookEl = document.getElementById('flipbook');
    const spinner = document.getElementById('spinner');

    if (!flipbookEl || !spinner) {
      return;
    }

    try {
      const pdf = await pdfjsLib.getDocument(pdfUrl).promise;
      const { flipPlan, maxUnitW, maxUnitH } = await analyzePdf(pdf);
      const { flipW, flipH } = calcFlipDimensions(maxUnitW, maxUnitH);

      const pageDivs = flipPlan.map(function (entry, index) {
        const pageDiv = document.createElement('div');
        pageDiv.className = 'my-page';
        pageDiv.dataset.flipIndex = String(index);
        pageDiv.dataset.pdfPage = String(entry.pdfPageNumber);
        pageDiv.dataset.side = entry.side;
        flipbookEl.appendChild(pageDiv);
        return pageDiv;
      });

      if (pageDivs.length === 0) {
        showError(spinner, 'Catalog không có trang nào.');
        return;
      }

      const renderer = createPageRenderer(pdf, pageDivs, flipPlan, flipW, flipH);

      await renderer.preloadAround(0);
      spinner.style.display = 'none';

      const pageFlip = new St.PageFlip(flipbookEl, {
        width: flipW,
        height: flipH,
        size: 'stretch',
        minWidth: Math.round(flipW * 0.65),
        maxWidth: Math.round(flipW * 1.35),
        minHeight: Math.round(flipH * 0.65),
        maxHeight: Math.round(flipH * 1.35),
        showCover: true,
        mobileScrollSupport: false,
        usePortrait: window.innerWidth < 768,
      });

      pageFlip.loadFromHTML(pageDivs);

      pageFlip.on('flip', function (event) {
        const currentIndex = event.data;
        renderer.preloadAround(currentIndex);
      });

      renderer.startBackgroundLoad(0);
    } catch (err) {
      showError(spinner, 'Không thể tải catalog. Vui lòng thử lại sau.');
      console.error('Flipbook error:', err);
    }
  }

  window.initCatalogFlipbook = initCatalogFlipbook;
})();
