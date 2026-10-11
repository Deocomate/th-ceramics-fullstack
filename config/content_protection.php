<?php

return [
    // Substrings matched case-insensitively against the User-Agent. A match only
    // keeps the deterrence scripts out of the page; it never widens data access.
    'exempt_user_agents' => [
        'Googlebot',
        'Google-InspectionTool',
        'Chrome-Lighthouse',
        // Lighthouse 12 and PageSpeed Insights emulate this phone and no longer
        // send the Chrome-Lighthouse token.
        'moto g power',
        'bingbot',
        'coccocbot',
        'DuckDuckBot',
        'facebookexternalhit',
        'Zalo',
        'Twitterbot',
        'Applebot',
    ],

    // Requests per minute allowed from one IP address across client pages.
    'page_rate_limit' => (int) env('CONTENT_PROTECTION_PAGE_RATE_LIMIT', 120),

    'devtools' => [
        // Detections inside the window before the visitor is sent to the legal warning page.
        'threshold' => 3,
        'window_hours' => 24,

        // disable-devtool detector types. 2 (Size) fires on browser side panels and
        // 5 (Debugger) only works on Chrome for iOS, so both stay out.
        'detectors' => [0, 1, 3, 4, 6, 7],
        'interval_ms' => 1000,

        // Count detections per IP address as well as per session. Turn off when
        // visitors behind one carrier address reach the warning page too early.
        'count_by_ip' => true,

        // Violation records hold IP addresses and User-Agents; the privacy policy
        // states this retention period.
        'retention_days' => 90,
    ],

    // Catalog readers get page images rendered in the admin browser, never the PDF.
    'catalog_pages' => [
        // Long edge of a page image in pixels. A lower value makes copies less useful
        // and softens the 200% zoom on high-density screens.
        'max_edge' => 2000,
        // Target size the admin browser encodes each page under.
        'max_bytes' => 1_000_000,
        // PDFs with more pages are refused.
        'max_pages' => 400,
    ],
];
