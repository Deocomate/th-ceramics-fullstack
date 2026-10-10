<?php

return [
    // Substrings matched case-insensitively against the User-Agent. A match only
    // keeps the deterrence scripts out of the page; it never widens data access.
    'exempt_user_agents' => [
        'Googlebot',
        'Google-InspectionTool',
        'Chrome-Lighthouse',
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
];
