<?php

return [
    // Enable after the additive migration, before starting the background backfill.
    'shadow_write' => env('PRODUCT_SHADOW_WRITE', false),
    'read_unified' => env('PRODUCT_READ_UNIFIED', false),
];
