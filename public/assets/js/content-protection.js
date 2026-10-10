(function () {
    'use strict';

    var config = window.__contentProtection;
    if (!config) return;

    var PROTECTED_TAGS = ['IMG', 'VIDEO', 'CANVAS', 'PICTURE'];

    function isProtectedMedia(target) {
        return !!target && PROTECTED_TAGS.indexOf(target.tagName) !== -1;
    }

    function blockOnMedia(event) {
        if (isProtectedMedia(event.target)) event.preventDefault();
    }

    // `code` names the physical key. `key` is unusable for the macOS shortcuts:
    // holding Option turns the letter into another glyph.
    function pressedLetter(event) {
        var code = event.code || '';

        return /^Key[A-Z]$/.test(code) ? code.slice(3).toLowerCase() : String(event.key || '').toLowerCase();
    }

    function isBlockedShortcut(event) {
        if (event.key === 'F12' || event.code === 'F12') return true;

        var letter = pressedLetter(event);
        var primary = event.ctrlKey || event.metaKey;

        // Developer tools: Ctrl/Cmd+Shift+I, J, C and Cmd+Option+I, J, C; view source: Cmd+Option+U.
        if (primary && event.shiftKey && 'ijc'.indexOf(letter) !== -1 && letter.length === 1) return true;
        if (event.metaKey && event.altKey && 'ijcu'.indexOf(letter) !== -1 && letter.length === 1) return true;

        // View source and save page. Ctrl/Cmd+C stays free so text can be copied.
        return primary && !event.shiftKey && !event.altKey && (letter === 'u' || letter === 's');
    }

    if (config.deterrence) {
        document.addEventListener('contextmenu', blockOnMedia, true);
        document.addEventListener('dragstart', blockOnMedia, true);
        document.addEventListener('keydown', function (event) {
            if (isBlockedShortcut(event)) event.preventDefault();
        }, true);
    }

    var devtools = config.devtools;
    if (!devtools || typeof window.DisableDevtool !== 'function') return;

    // The detector calls back on every interval while the tools stay open.
    var reported = false;

    function setBodyHidden(hidden) {
        if (document.body) document.body.style.display = hidden ? 'none' : '';
    }

    function reportDetection(detector) {
        if (reported) return;
        reported = true;
        setBodyHidden(true);

        var tokenMeta = document.querySelector('meta[name="csrf-token"]');

        window.fetch(devtools.reportUrl, {
            method: 'POST',
            keepalive: true,
            credentials: 'same-origin',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': tokenMeta ? tokenMeta.getAttribute('content') : '',
            },
            body: JSON.stringify({ detector: detector, path: window.location.pathname }),
        }).then(function (response) {
            // No content: the guard was switched off or this visitor is exempt.
            if (response.status === 204) return null;
            if (!response.ok) throw new Error('report rejected');

            return response.json();
        }).then(function (data) {
            if (data === null) {
                setBodyHidden(false);

                return;
            }
            window.location.replace(data.redirect || devtools.noticeUrl);
        }).catch(function () {
            window.location.replace(devtools.noticeUrl);
        });
    }

    window.DisableDevtool({
        disableMenu: false,
        clearLog: false,
        detectors: devtools.detectors,
        interval: devtools.interval,
        ondevtoolopen: reportDetection,
    });
})();
