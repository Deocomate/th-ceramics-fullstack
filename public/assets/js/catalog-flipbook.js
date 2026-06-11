(function () {
  'use strict';

  const SPREAD_RATIO = 1.25;
  const PRELOAD_AHEAD = 5;
  const PRELOAD_BEHIND = 1;
  const BACKGROUND_CONCURRENCY = 2;
  const UI_CHROME_HEIGHT = 128;
  const ZOOM_MIN = 1.0;
  const ZOOM_MAX = 2.0;
  const ZOOM_STEP = 0.25;

  let currentZoom = ZOOM_MIN;
  let panX = 0;
  let panY = 0;

  let pageFlip = null;
  let renderer = null;
  let pdfDoc = null;
  let flipPlan = [];
  let maxUnitW = 0;
  let maxUnitH = 0;
  let totalPdfPages = 0;

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

    return { flipPlan, maxUnitW, maxUnitH, totalPdfPages: pdf.numPages };
  }

  function calcFlipDimensions(maxUnitW, maxUnitH) {
    const isMobile = window.innerWidth < 768;
    const maxW = isMobile
      ? window.innerWidth * 0.88
      : Math.min(window.innerWidth * 0.46, 800);
    const maxH = (window.innerHeight - UI_CHROME_HEIGHT) * (isMobile ? 0.88 : 0.90);
    const scale = Math.min(maxW / maxUnitW, maxH / maxUnitH);

    return {
      flipW: Math.round(maxUnitW * scale),
      flipH: Math.round(maxUnitH * scale),
    };
  }

  function formatPageDisplay(flipPlan, currentIndex, isPortrait) {
    const entry = flipPlan[currentIndex];
    if (!entry) {
      return '1';
    }

    if (isPortrait || currentIndex === 0) {
      return String(entry.pdfPageNumber);
    }

    if (entry.side === 'left') {
      const rightEntry = flipPlan[currentIndex + 1];
      if (rightEntry?.side === 'right' && rightEntry.pdfPageNumber === entry.pdfPageNumber) {
        return String(entry.pdfPageNumber);
      }
      if (rightEntry) {
        return entry.pdfPageNumber + '-' + rightEntry.pdfPageNumber;
      }
    }

    if (entry.side === 'right') {
      const leftEntry = flipPlan[currentIndex - 1];
      if (leftEntry?.side === 'left' && leftEntry.pdfPageNumber === entry.pdfPageNumber) {
        return String(entry.pdfPageNumber);
      }
    }

    return String(entry.pdfPageNumber);
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
      '<p class="text-red-500 text-sm font-medium">' + message + '</p>';
  }

  function setupControls(pageFlip, flipPlan, totalPdfPages, flipW, flipH) {
    const prevBtn = document.getElementById('prev-btn');
    const nextBtn = document.getElementById('next-btn');
    const pageNumEl = document.getElementById('page-num');
    const pageCountEl = document.getElementById('page-count');
    const zoomInBtn = document.getElementById('zoom-in-btn');
    const zoomOutBtn = document.getElementById('zoom-out-btn');
    const zoomLevelEl = document.getElementById('zoom-level');
    const zoomSlider = document.getElementById('zoom-slider');
    const fullscreenBtn = document.getElementById('fullscreen-btn');
    const fullscreenIcon = document.getElementById('fullscreen-icon');
    const flipbookEl = document.getElementById('flipbook');
    const zoomWrapEl = document.getElementById('flipbook-zoom-wrap');
    const viewportEl = document.getElementById('flipbook-viewport');

    if (!flipbookEl || !viewportEl || !zoomWrapEl) {
      return;
    }

    let currentZoom = ZOOM_MIN;
    let panX = 0;
    let panY = 0;
    const isPortrait = window.innerWidth < 768;

    if (pageCountEl) {
      pageCountEl.textContent = String(totalPdfPages);
    }

    function updateNavButtons() {
      const currentIndex = pageFlip.getCurrentPageIndex();
      const lastIndex = pageFlip.getPageCount() - 1;

      if (prevBtn) {
        prevBtn.disabled = currentIndex <= 0;
      }
      if (nextBtn) {
        nextBtn.disabled = currentIndex >= lastIndex;
      }
    }

    function updatePageNumber(currentIndex) {
      if (!pageNumEl) {
        return;
      }

      pageNumEl.textContent = formatPageDisplay(
        flipPlan,
        currentIndex,
        isPortrait
      );
    }

    function applyZoom() {
      const isZoomed = currentZoom > ZOOM_MIN;

      if (isZoomed) {
        const bookWidth = isPortrait ? flipW : (flipW * 2);
        const zoomedWidth = Math.round(bookWidth * currentZoom);
        const zoomedHeight = Math.round(flipH * currentZoom);

        const viewportWidth = viewportEl.clientWidth;
        const viewportHeight = viewportEl.clientHeight;

        // Constrain panX and panY
        if (zoomedWidth > viewportWidth) {
          const maxPanX = (zoomedWidth - viewportWidth) / 2;
          panX = Math.max(-maxPanX, Math.min(maxPanX, panX));
        } else {
          panX = 0;
        }

        if (zoomedHeight > viewportHeight) {
          const maxPanY = (zoomedHeight - viewportHeight) / 2;
          panY = Math.max(-maxPanY, Math.min(maxPanY, panY));
        } else {
          panY = 0;
        }

        flipbookEl.style.transformOrigin = 'center center';
        flipbookEl.style.transform = 'translate(' + panX + 'px, ' + panY + 'px) scale(' + currentZoom + ')';
      } else {
        panX = 0;
        panY = 0;
        flipbookEl.style.transform = '';
        flipbookEl.style.transformOrigin = '';
      }

      if (zoomLevelEl) {
        zoomLevelEl.textContent = Math.round(currentZoom * 100) + '%';
      }

      if (zoomSlider) {
        zoomSlider.value = String(Math.round(currentZoom * 100));
      }

      viewportEl.classList.toggle('is-zoomed', isZoomed);
      flipbookEl.style.pointerEvents = isZoomed ? 'none' : '';

      if (zoomInBtn) {
        zoomInBtn.disabled = currentZoom >= ZOOM_MAX;
        zoomInBtn.style.opacity = currentZoom >= ZOOM_MAX ? '0.35' : '';
      }
      if (zoomOutBtn) {
        zoomOutBtn.disabled = currentZoom <= ZOOM_MIN;
        zoomOutBtn.style.opacity = currentZoom <= ZOOM_MIN ? '0.35' : '';
      }
    }

    function goPrev() {
      if (currentZoom > ZOOM_MIN) {
        return;
      }
      pageFlip.flipPrev();
    }

    function goNext() {
      if (currentZoom > ZOOM_MIN) {
        return;
      }
      pageFlip.flipNext();
    }

    if (prevBtn) {
      prevBtn.addEventListener('click', goPrev);
    }
    if (nextBtn) {
      nextBtn.addEventListener('click', goNext);
    }

    window.addEventListener('keydown', function (event) {
      if (currentZoom > ZOOM_MIN) {
        return;
      }

      if (event.key === 'ArrowLeft') {
        event.preventDefault();
        goPrev();
      } else if (event.key === 'ArrowRight') {
        event.preventDefault();
        goNext();
      }
    });

    if (zoomInBtn) {
      zoomInBtn.addEventListener('click', function () {
        currentZoom = Math.min(ZOOM_MAX, currentZoom + ZOOM_STEP);
        applyZoom();
      });
    }

    if (zoomOutBtn) {
      zoomOutBtn.addEventListener('click', function () {
        currentZoom = Math.max(ZOOM_MIN, currentZoom - ZOOM_STEP);
        applyZoom();
      });
    }

    if (zoomSlider) {
      zoomSlider.addEventListener('input', function (event) {
        currentZoom = parseFloat(event.target.value) / 100;
        applyZoom();
      });
    }

    function updateFullscreenIcon() {
      if (!fullscreenIcon) {
        return;
      }

      const isFs = !!document.fullscreenElement;
      fullscreenIcon.setAttribute('data-lucide', isFs ? 'minimize' : 'maximize');

      if (typeof lucide !== 'undefined') {
        lucide.createIcons();
      }
    }

    if (fullscreenBtn) {
      fullscreenBtn.addEventListener('click', function () {
        if (document.fullscreenElement) {
          document.exitFullscreen();
        } else {
          document.documentElement.requestFullscreen();
        }
      });
    }

    document.addEventListener('fullscreenchange', updateFullscreenIcon);

    let isPanning = false;
    let panStartX = 0;
    let panStartY = 0;
    let startPanX = 0;
    let startPanY = 0;

    viewportEl.addEventListener('mousedown', function (event) {
      if (currentZoom <= ZOOM_MIN || event.button !== 0) {
        return;
      }

      isPanning = true;
      panStartX = event.clientX;
      panStartY = event.clientY;
      startPanX = panX;
      startPanY = panY;
      viewportEl.classList.add('is-panning');
      event.preventDefault();
    });

    window.addEventListener('mousemove', function (event) {
      if (!isPanning) {
        return;
      }

      const dx = event.clientX - panStartX;
      const dy = event.clientY - panStartY;
      panX = startPanX + dx;
      panY = startPanY + dy;
      applyZoom();
    });

    window.addEventListener('mouseup', function () {
      if (isPanning) {
        isPanning = false;
        viewportEl.classList.remove('is-panning');
      }
    });

    viewportEl.addEventListener('touchstart', function (event) {
      if (currentZoom <= ZOOM_MIN || event.touches.length !== 1) {
        return;
      }

      isPanning = true;
      panStartX = event.touches[0].clientX;
      panStartY = event.touches[0].clientY;
      startPanX = panX;
      startPanY = panY;
    }, { passive: true });

    viewportEl.addEventListener('touchmove', function (event) {
      if (!isPanning || event.touches.length !== 1) {
        return;
      }

      const dx = event.touches[0].clientX - panStartX;
      const dy = event.touches[0].clientY - panStartY;
      panX = startPanX + dx;
      panY = startPanY + dy;
      applyZoom();
    }, { passive: true });

    viewportEl.addEventListener('touchend', function () {
      isPanning = false;
    });

    pageFlip.on('flip', function (event) {
      updatePageNumber(event.data);
      updateNavButtons();
    });

    updatePageNumber(pageFlip.getCurrentPageIndex());
    updateNavButtons();
    applyZoom();
  }

  async function initCatalogFlipbook(pdfUrl) {
    const flipbookEl = document.getElementById('flipbook');
    const spinner = document.getElementById('spinner');

    if (!flipbookEl || !spinner) {
      return;
    }

    try {
      pdfDoc = await pdfjsLib.getDocument(pdfUrl).promise;
      const analysis = await analyzePdf(pdfDoc);
      flipPlan = analysis.flipPlan;
      maxUnitW = analysis.maxUnitW;
      maxUnitH = analysis.maxUnitH;
      totalPdfPages = analysis.totalPdfPages;

      const { flipW, flipH } = calcFlipDimensions(maxUnitW, maxUnitH);

      const isMobile = window.innerWidth < 768;
      const bookWidth = isMobile ? flipW : (flipW * 2);

      const zoomWrapEl = document.getElementById('flipbook-zoom-wrap');
      if (zoomWrapEl) {
        zoomWrapEl.style.width = bookWidth + 'px';
        zoomWrapEl.style.height = flipH + 'px';
      }

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

      renderer = createPageRenderer(pdfDoc, pageDivs, flipPlan, flipW, flipH);

      await renderer.preloadAround(0);
      spinner.style.display = 'none';

      pageFlip = new St.PageFlip(flipbookEl, {
        width: flipW,
        height: flipH,
        size: 'stretch',
        minWidth: 100,
        maxWidth: 3000,
        minHeight: 100,
        maxHeight: 3000,
        showCover: true,
        mobileScrollSupport: false,
        usePortrait: isMobile,
      });

      pageFlip.loadFromHTML(pageDivs);

      pageFlip.on('flip', function (event) {
        renderer.preloadAround(event.data);
      });

      setupControls(pageFlip, flipPlan, totalPdfPages, flipW, flipH);
      renderer.startBackgroundLoad(0);
    } catch (err) {
      showError(spinner, 'Không thể tải catalog. Vui lòng thử lại sau.');
      console.error('Flipbook error:', err);
    }
  }

  window.addEventListener('resize', function () {
    if (!pageFlip) {
      return;
    }
    const { flipW: newW, flipH: newH } = calcFlipDimensions(maxUnitW, maxUnitH);
    const isPortrait = window.innerWidth < 768;
    const bookWidth = isPortrait ? newW : (newW * 2);

    const zoomWrapEl = document.getElementById('flipbook-zoom-wrap');
    if (zoomWrapEl) {
      zoomWrapEl.style.width = bookWidth + 'px';
      zoomWrapEl.style.height = newH + 'px';
    }

    currentZoom = ZOOM_MIN;
    panX = 0;
    panY = 0;

    const flipbookEl = document.getElementById('flipbook');
    if (flipbookEl) {
      flipbookEl.style.transform = '';
      flipbookEl.style.transformOrigin = '';
    }
    const zoomLevelEl = document.getElementById('zoom-level');
    if (zoomLevelEl) {
      zoomLevelEl.textContent = '100%';
    }
    const zoomSlider = document.getElementById('zoom-slider');
    if (zoomSlider) {
      zoomSlider.value = '100';
    }
    const viewportEl = document.getElementById('flipbook-viewport');
    if (viewportEl) {
      viewportEl.classList.remove('is-zoomed');
    }
  });

  window.initCatalogFlipbook = initCatalogFlipbook;
})();
