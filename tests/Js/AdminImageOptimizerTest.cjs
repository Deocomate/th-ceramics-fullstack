const assert = require('node:assert/strict');
const fs = require('node:fs');
const path = require('node:path');
const test = require('node:test');
const vm = require('node:vm');

const source = fs.readFileSync(path.resolve(__dirname, '../../public/assets/js/admin-image-optimizer.js'), 'utf8');

function loadOptimizer({ decodeFails = false } = {}) {
    let activeDecodes = 0;
    let maximumDecodes = 0;
    let closed = 0;
    const events = {};
    const window = { crypto: { randomUUID: () => '00000000-0000-4000-8000-000000000001' } };
    class TestForm {
        querySelectorAll() { return []; }
        requestSubmit() { this.submissions = (this.submissions || 0) + 1; }
    }
    const document = {
        currentScript: { dataset: { uploadUrl: '/staged', heicUrl: '/heic' } },
        documentElement: {},
        querySelectorAll: () => [],
        addEventListener: (name, handler) => { events[name] = handler; },
        createElement: (tag) => tag === 'canvas' ? {
            width: 0,
            height: 0,
            getContext: () => ({ clearRect() {}, drawImage() {} }),
            toBlob: (resolve) => resolve(new Blob([Buffer.alloc(850_000)], { type: 'image/webp' })),
        } : { closest: () => null, name: 'image', id: 'image' },
    };
    class BrokenImage {
        set src(_value) { queueMicrotask(() => this.onerror?.()); }
    }
    vm.runInNewContext(source, {
        window,
        document,
        File,
        Blob,
        Buffer,
        Image: BrokenImage,
        URL: { createObjectURL: () => 'blob:test', revokeObjectURL() {} },
        MutationObserver: class { observe() {} },
        createImageBitmap: async () => {
            if (decodeFails) throw new Error('decode failed');
            activeDecodes++;
            maximumDecodes = Math.max(maximumDecodes, activeDecodes);
            await new Promise((resolve) => setTimeout(resolve, 1));
            return { width: 5000, height: 3000, close() { activeDecodes--; closed++; } };
        },
        HTMLInputElement: class {},
        HTMLFormElement: TestForm,
        HTMLElement: class {},
        setTimeout,
        queueMicrotask,
    });

    return {
        optimizer: window.AdminImageOptimizer,
        events,
        TestForm,
        stats: () => ({ activeDecodes, maximumDecodes, closed }),
    };
}

test('large supported images become WebP files under 1 MB one at a time', async () => {
    const { optimizer, stats } = loadOptimizer();
    const input = { name: 'images[]', id: 'gallery', closest: () => null };
    const files = [
        new File([Buffer.alloc(12_000_000)], 'large.jpg', { type: 'image/jpeg' }),
        new File([Buffer.alloc(8_000_000)], 'iphone.heic', { type: 'image/heic' }),
    ];

    const result = await optimizer.processFiles(files, input);

    assert.deepEqual(Array.from(result, (file) => file.name), ['large.webp', 'iphone.webp']);
    assert.ok(result.every((file) => file.type === 'image/webp' && file.size < 1_000_000));
    assert.deepEqual(stats(), { activeDecodes: 0, maximumDecodes: 1, closed: 2 });
});

test('form submission waits for an active gallery upload', async () => {
    const { optimizer, events, TestForm } = loadOptimizer();
    const form = new TestForm();
    let completeUpload;
    optimizer.trackPending(form, new Promise((resolve) => { completeUpload = resolve; }));
    let prevented = false;

    const submission = events.submit({
        target: form,
        submitter: null,
        preventDefault() { prevented = true; },
        stopImmediatePropagation() {},
    });
    assert.equal(prevented, true);
    assert.equal(form.submissions || 0, 0);

    completeUpload();
    await submission;
    assert.equal(form.submissions, 1);
});

test('corrupt and unsupported files return understandable Vietnamese errors', async () => {
    const { optimizer } = loadOptimizer({ decodeFails: true });
    const input = { name: 'image', id: 'image', closest: () => null };

    await assert.rejects(
        optimizer.processFile(new File(['broken'], 'broken.jpg', { type: 'image/jpeg' }), input),
        /Không đọc được ảnh/,
    );
    await assert.rejects(
        optimizer.processFile(new File(['text'], 'note.txt', { type: 'text/plain' }), input),
        /Định dạng chưa hỗ trợ/,
    );
});

test('product gallery script remains valid after Blade values are inserted', () => {
    const template = fs.readFileSync(path.resolve(__dirname, '../../resources/views/admin/partials/product-gallery-manager.blade.php'), 'utf8');
    const script = template.match(/<script>\s*(window\.__galleryVideoField[\s\S]*?)<\/script>/)?.[1]
        .replace(/@json\([^)]+\)/g, '"new_video_urls[]"')
        .replace(/\{\{[^}]+\}\}/g, 'true');

    assert.ok(script, 'gallery script should be present');
    assert.doesNotThrow(() => new vm.Script(script));
});
