const assert = require('node:assert/strict');
const fs = require('node:fs');
const path = require('node:path');
const test = require('node:test');
const vm = require('node:vm');

const source = fs.readFileSync(path.resolve(__dirname, '../../public/assets/js/admin-catalog-page-renderer.js'), 'utf8');

function loadRenderer({ pageSizes = [], blobType = 'image/webp', blobBytes = () => 400_000, failures = {}, dataset = {} } = {}) {
    const requests = [];
    const canvases = [];
    const attempts = {};
    const window = { crypto: { randomUUID: () => '00000000-0000-4000-8000-000000000001' } };
    const document = {
        currentScript: { dataset: { maxEdge: '2000', maxBytes: '1000000', maxPages: '400', ...dataset } },
        createElement: () => {
            const canvas = {
                width: 0,
                height: 0,
                getContext: () => ({ fillStyle: '', fillRect() {}, drawImage() {} }),
                toBlob(resolve, _type, quality) {
                    canvas.qualities.push(quality);
                    resolve(new Blob([Buffer.alloc(blobBytes(quality, canvas))], { type: blobType }));
                },
                qualities: [],
            };
            canvases.push(canvas);
            return canvas;
        },
    };
    const pdf = {
        numPages: pageSizes.length,
        getPage: async (number) => ({
            getViewport: ({ scale }) => ({ width: pageSizes[number - 1][0] * scale, height: pageSizes[number - 1][1] * scale }),
            render: (options) => {
                pdf.renders.push({ page: number, transform: options.transform, intent: options.intent });
                return { promise: Promise.resolve() };
            },
            cleanup() {},
        }),
        renders: [],
        destroy() { pdf.destroyed = true; },
    };
    vm.runInNewContext(source, {
        window,
        document,
        Blob,
        FormData,
        pdfjsLib: { GlobalWorkerOptions: {}, getDocument: () => ({ promise: Promise.resolve(pdf) }) },
        fetch: async (url, options) => {
            const index = options.body instanceof FormData ? Number(options.body.get('index')) : null;
            const key = index === null ? 'finalize' : index;
            attempts[key] = (attempts[key] || 0) + 1;
            if (attempts[key] <= (failures[key] || 0)) {
                throw new TypeError('network down');
            }
            requests.push({ url, index, body: options.body });
            return { ok: true, status: 200, json: async () => ({ success: true }) };
        },
        setTimeout: (callback) => setTimeout(callback, 0),
    });

    return { renderer: window.AdminCatalogPageRenderer, requests, canvases, pdf };
}

const options = { source: '/admin/catalog/5/file', pagesUrl: '/pages', finalizeUrl: '/finalize', csrfToken: 'token' };

test('wide pages split into a left and a right entry and single pages stay whole', () => {
    const { renderer } = loadRenderer();

    const plan = renderer.planPages([
        { width: 600, height: 800 },
        { width: 1000, height: 800 },
        { width: 990, height: 800 },
        { width: 1200, height: 800 },
    ]);

    assert.deepEqual(JSON.parse(JSON.stringify(plan)), [
        { pdfPage: 1, side: 'full', width: 600, height: 800 },
        { pdfPage: 2, side: 'left', width: 500, height: 800 },
        { pdfPage: 2, side: 'right', width: 500, height: 800 },
        { pdfPage: 3, side: 'full', width: 990, height: 800 },
        { pdfPage: 4, side: 'left', width: 600, height: 800 },
        { pdfPage: 4, side: 'right', width: 600, height: 800 },
    ]);
});

test('every entry is rendered on a 2000px long edge, uploaded in order and finalized', async () => {
    const { renderer, requests, canvases, pdf } = loadRenderer({ pageSizes: [[600, 800], [1000, 800]] });
    const progress = [];

    await renderer.generate({ ...options, onProgress: (done, total) => progress.push([done, total]) });

    assert.deepEqual(requests.map((request) => request.index), [0, 1, 2, null]);
    assert.equal(requests[0].body.get('batch'), '00000000-0000-4000-8000-000000000001');
    assert.equal(requests[0].body.get('total'), '3');
    assert.equal(requests[3].url, '/finalize');
    assert.deepEqual(JSON.parse(requests[3].body), {
        batch: '00000000-0000-4000-8000-000000000001',
        pages: [
            { pdf_page: 1, side: 'full' },
            { pdf_page: 2, side: 'left' },
            { pdf_page: 2, side: 'right' },
        ],
    });
    assert.deepEqual(progress, [[1, 3], [2, 3], [3, 3]]);
    // The right half is drawn by shifting the whole spread left by one page width.
    assert.deepEqual(pdf.renders.map((render) => render.transform[4]), [0, 0, -1250]);
    // Rendering must not wait on animation frames, or a background tab stalls the run.
    assert.ok(pdf.renders.every((render) => render.intent === 'print'));
    // Canvases are released as soon as their page is encoded.
    assert.ok(canvases.every((canvas) => canvas.width === 0 && canvas.height === 0));
    assert.equal(pdf.destroyed, true);
});

test('quality drops until the page fits the byte limit', async () => {
    const { renderer, canvases } = loadRenderer({
        pageSizes: [[600, 800]],
        blobBytes: (quality) => (quality > 0.7 ? 1_200_000 : 900_000),
    });

    await renderer.generate(options);

    assert.equal(canvases[0].qualities[0], 0.85);
    assert.ok(canvases[0].qualities.at(-1) <= 0.7);
});

test('a failed upload is retried and a persistent failure stops the run', async () => {
    const recovered = loadRenderer({ pageSizes: [[600, 800]], failures: { 0: 3 } });
    await recovered.renderer.generate(options);
    assert.deepEqual(recovered.requests.map((request) => request.index), [0, null]);

    const broken = loadRenderer({ pageSizes: [[600, 800]], failures: { 0: 4 } });
    await assert.rejects(broken.renderer.generate(options), /mạng/);
    assert.deepEqual(broken.requests, []);
});

test('a browser without WebP encoding gets a clear error', async () => {
    const { renderer, requests } = loadRenderer({ pageSizes: [[600, 800]], blobType: 'image/png' });

    await assert.rejects(renderer.generate(options), /WebP/);
    assert.deepEqual(requests, []);
});

test('a PDF above the page limit is refused before rendering', async () => {
    const { renderer, requests } = loadRenderer({ pageSizes: [[600, 800], [600, 800], [600, 800]], dataset: { maxPages: '2' } });

    await assert.rejects(renderer.generate(options), /2 trang/);
    assert.deepEqual(requests, []);
});
