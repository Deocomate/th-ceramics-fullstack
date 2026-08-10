<?php

use App\Support\ProductJourneyVideo;

test('product journey video resolve prefers product over config', function () {
    $config = (object) ['video' => 'https://www.youtube.com/watch?v=Win12rIicBI'];

    expect(ProductJourneyVideo::resolve('https://youtu.be/1E6KuLVe07M', $config))
        ->toBe('https://youtu.be/1E6KuLVe07M');
});

test('product journey video resolve falls back to config video then video_url', function () {
    expect(ProductJourneyVideo::resolve(null, (object) ['video' => 'https://youtu.be/Win12rIicBI']))
        ->toBe('https://youtu.be/Win12rIicBI')
        ->and(ProductJourneyVideo::resolve('  ', (object) ['video_url' => 'https://youtu.be/x7ema3Yh5MU']))
        ->toBe('https://youtu.be/x7ema3Yh5MU')
        ->and(ProductJourneyVideo::resolve(null, null))->toBeNull();
});
