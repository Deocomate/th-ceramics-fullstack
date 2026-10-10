const assert = require('node:assert/strict');
const fs = require('node:fs');
const path = require('node:path');
const test = require('node:test');
const vm = require('node:vm');

const source = fs.readFileSync(path.resolve(__dirname, '../../public/assets/js/content-protection.js'), 'utf8');

const DEVTOOLS_CONFIG = {
    reportUrl: '/bao-ve-noi-dung/ghi-nhan',
    noticeUrl: '/noi-dung-duoc-bao-ve',
    detectors: [0, 1, 3, 4, 6, 7],
    interval: 1000,
};

function loadProtection(config, { fetchImpl } = {}) {
    const listeners = {};
    const fetchCalls = [];
    const replaced = [];
    let devtoolOptions = null;
    const document = {
        body: { style: {} },
        addEventListener: (name, handler) => { (listeners[name] = listeners[name] || []).push(handler); },
        querySelector: (selector) => selector === 'meta[name="csrf-token"]'
            ? { getAttribute: () => 'token-123' }
            : null,
    };
    const window = {
        __contentProtection: config,
        DisableDevtool: (options) => { devtoolOptions = options; },
        location: { pathname: '/san-pham/ngoi-am-duong', replace: (url) => replaced.push(url) },
        fetch: (url, options) => {
            fetchCalls.push({ url, options });
            return (fetchImpl || (() => Promise.resolve({
                ok: true,
                status: 200,
                json: () => Promise.resolve({ redirect: '/canh-bao-ban-quyen' }),
            })))();
        },
    };
    vm.runInNewContext(source, { window, document });

    const dispatch = (name, event) => {
        let prevented = false;
        (listeners[name] || []).forEach((handler) => handler({ ...event, preventDefault() { prevented = true; } }));
        return prevented;
    };

    return { listeners, fetchCalls, replaced, document, dispatch, devtoolOptions: () => devtoolOptions };
}

const settle = () => new Promise((resolve) => setTimeout(resolve, 5));

function key(code, modifiers = {}) {
    const name = code.startsWith('Key') ? code.slice(3).toLowerCase() : code;

    return { code, key: name, ctrlKey: false, metaKey: false, shiftKey: false, altKey: false, ...modifiers };
}

test('developer tool, view source and save shortcuts are blocked', () => {
    const { dispatch } = loadProtection({ deterrence: true, devtools: null });
    const blocked = [
        key('F12'),
        key('KeyI', { ctrlKey: true, shiftKey: true }),
        key('KeyJ', { ctrlKey: true, shiftKey: true }),
        key('KeyC', { ctrlKey: true, shiftKey: true }),
        key('KeyI', { metaKey: true, shiftKey: true }),
        key('KeyJ', { metaKey: true, shiftKey: true }),
        key('KeyC', { metaKey: true, shiftKey: true }),
        // macOS reports the Option-modified glyph in `key`, so only `code` identifies the letter.
        { ...key('KeyI', { metaKey: true, altKey: true }), key: 'ˆ' },
        { ...key('KeyJ', { metaKey: true, altKey: true }), key: '∆' },
        { ...key('KeyC', { metaKey: true, altKey: true }), key: 'ç' },
        { ...key('KeyU', { metaKey: true, altKey: true }), key: 'Dead' },
        key('KeyU', { ctrlKey: true }),
        key('KeyU', { metaKey: true }),
        key('KeyS', { ctrlKey: true }),
        key('KeyS', { metaKey: true }),
    ];

    for (const event of blocked) {
        assert.equal(dispatch('keydown', event), true, `${JSON.stringify(event)} should be blocked`);
    }
});

test('copy, select all, find, print, reload and plain typing stay available', () => {
    const { dispatch } = loadProtection({ deterrence: true, devtools: null });
    const allowed = [
        key('KeyC', { ctrlKey: true }),
        key('KeyC', { metaKey: true }),
        key('KeyA', { ctrlKey: true }),
        key('KeyF', { ctrlKey: true }),
        key('KeyP', { ctrlKey: true }),
        key('KeyR', { ctrlKey: true, shiftKey: true }),
        key('F5'),
        key('KeyI'),
        key('KeyU'),
        key('KeyS'),
        key('KeyJ', { shiftKey: true }),
        key('KeyI', { altKey: true }),
    ];

    for (const event of allowed) {
        assert.equal(dispatch('keydown', event), false, `${JSON.stringify(event)} should not be blocked`);
    }
});

test('context menu and drag are blocked on media but not on text', () => {
    const { dispatch, listeners } = loadProtection({ deterrence: true, devtools: null });

    for (const tagName of ['IMG', 'VIDEO', 'CANVAS', 'PICTURE']) {
        assert.equal(dispatch('contextmenu', { target: { tagName } }), true, `contextmenu on ${tagName}`);
        assert.equal(dispatch('dragstart', { target: { tagName } }), true, `dragstart on ${tagName}`);
    }
    for (const tagName of ['P', 'A', 'SPAN', 'INPUT']) {
        assert.equal(dispatch('contextmenu', { target: { tagName } }), false, `contextmenu on ${tagName}`);
        assert.equal(dispatch('dragstart', { target: { tagName } }), false, `dragstart on ${tagName}`);
    }
    assert.equal(dispatch('contextmenu', { target: null }), false);

    assert.deepEqual(Object.keys(listeners).sort(), ['contextmenu', 'dragstart', 'keydown']);
});

test('deterrence listeners are not attached while that layer is off', () => {
    const { listeners, devtoolOptions } = loadProtection({ deterrence: false, devtools: DEVTOOLS_CONFIG });

    assert.deepEqual(Object.keys(listeners), []);
    assert.ok(devtoolOptions(), 'detector should still start');
});

test('detector starts with the configured detectors and leaves menu and console alone', () => {
    const { devtoolOptions } = loadProtection({ deterrence: true, devtools: DEVTOOLS_CONFIG });
    const options = devtoolOptions();

    assert.equal(options.disableMenu, false);
    assert.equal(options.clearLog, false);
    assert.deepEqual(Array.from(options.detectors), [0, 1, 3, 4, 6, 7]);
    assert.equal(options.interval, 1000);
    assert.equal(typeof options.ondevtoolopen, 'function');
});

test('detector does not start without devtools configuration', () => {
    assert.equal(loadProtection({ deterrence: true, devtools: null }).devtoolOptions(), null);
    assert.equal(loadProtection(undefined).devtoolOptions(), null);
    assert.deepEqual(Object.keys(loadProtection(undefined).listeners), []);
});

test('a detection is reported once per page load and follows the server redirect', async () => {
    const { devtoolOptions, fetchCalls, replaced, document } = loadProtection({ deterrence: true, devtools: DEVTOOLS_CONFIG });

    devtoolOptions().ondevtoolopen(1);
    devtoolOptions().ondevtoolopen(1);
    devtoolOptions().ondevtoolopen(4);
    await settle();

    assert.equal(fetchCalls.length, 1);
    assert.equal(fetchCalls[0].url, '/bao-ve-noi-dung/ghi-nhan');
    assert.equal(fetchCalls[0].options.method, 'POST');
    assert.equal(fetchCalls[0].options.keepalive, true);
    assert.equal(fetchCalls[0].options.credentials, 'same-origin');
    assert.equal(fetchCalls[0].options.headers['X-CSRF-TOKEN'], 'token-123');
    assert.deepEqual(JSON.parse(fetchCalls[0].options.body), { detector: 1, path: '/san-pham/ngoi-am-duong' });
    assert.equal(document.body.style.display, 'none');
    assert.deepEqual(replaced, ['/canh-bao-ban-quyen']);
});

test('a failed report falls back to the notice page', async () => {
    const rejected = loadProtection({ deterrence: true, devtools: DEVTOOLS_CONFIG }, {
        fetchImpl: () => Promise.reject(new Error('offline')),
    });
    rejected.devtoolOptions().ondevtoolopen(1);

    const expired = loadProtection({ deterrence: true, devtools: DEVTOOLS_CONFIG }, {
        fetchImpl: () => Promise.resolve({ ok: false, status: 419, json: () => Promise.reject(new Error('html')) }),
    });
    expired.devtoolOptions().ondevtoolopen(1);
    await settle();

    assert.deepEqual(rejected.replaced, ['/noi-dung-duoc-bao-ve']);
    assert.deepEqual(expired.replaced, ['/noi-dung-duoc-bao-ve']);
});

test('a no-content answer restores the page without redirecting', async () => {
    const { devtoolOptions, replaced, document, fetchCalls } = loadProtection({ deterrence: true, devtools: DEVTOOLS_CONFIG }, {
        fetchImpl: () => Promise.resolve({ ok: true, status: 204 }),
    });

    devtoolOptions().ondevtoolopen(1);
    await settle();
    devtoolOptions().ondevtoolopen(1);
    await settle();

    assert.equal(fetchCalls.length, 1);
    assert.deepEqual(replaced, []);
    assert.equal(document.body.style.display, '');
});
