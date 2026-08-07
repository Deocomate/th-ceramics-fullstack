<?php

use App\Support\ProductGallery;

test('extracts youtube id from common url formats', function (string $url, string $expected) {
    expect(ProductGallery::extractYoutubeId($url))->toBe($expected);
})->with([
    'watch' => ['https://www.youtube.com/watch?v=Win12rIicBI', 'Win12rIicBI'],
    'embed' => ['https://www.youtube.com/embed/Win12rIicBI', 'Win12rIicBI'],
    'shorts' => ['https://www.youtube.com/shorts/Win12rIicBI', 'Win12rIicBI'],
    'youtu.be' => ['https://youtu.be/Win12rIicBI', 'Win12rIicBI'],
]);

test('returns null for non youtube urls', function () {
    expect(ProductGallery::extractYoutubeId('https://vimeo.com/123'))->toBeNull()
        ->and(ProductGallery::extractYoutubeId(''))->toBeNull()
        ->and(ProductGallery::extractYoutubeId(null))->toBeNull();
});

test('normalizes legacy string images and video objects', function () {
    $items = ProductGallery::normalize([
        'seeders/products/a.png',
        ['type' => 'video', 'url' => 'https://www.youtube.com/watch?v=Win12rIicBI'],
        '',
        ['type' => 'video', 'url' => 'not-a-youtube-url'],
    ]);

    expect($items)->toHaveCount(2)
        ->and($items[0]['type'])->toBe('image')
        ->and($items[0]['path'])->toBe('seeders/products/a.png')
        ->and($items[1]['type'])->toBe('video')
        ->and($items[1]['youtube_id'])->toBe('Win12rIicBI')
        ->and($items[1]['thumb_url'])->toBe('https://img.youtube.com/vi/Win12rIicBI/hqdefault.jpg')
        ->and($items[1]['embed_url'])->toBe('https://www.youtube.com/embed/Win12rIicBI?enablejsapi=1');
});

test('firstImagePath skips video items', function () {
    expect(ProductGallery::firstImagePath([
        ['type' => 'video', 'url' => 'https://www.youtube.com/watch?v=Win12rIicBI'],
        'seeders/products/cover.png',
        'seeders/products/other.png',
    ]))->toBe('seeders/products/cover.png');
});

test('appendVideoUrls only keeps valid youtube links', function () {
    $result = ProductGallery::appendVideoUrls(
        ['existing.png'],
        [
            'https://www.youtube.com/watch?v=Win12rIicBI',
            'https://example.com/not-youtube',
            '  ',
        ]
    );

    expect($result)->toHaveCount(2)
        ->and($result[0])->toBe('existing.png')
        ->and($result[1])->toBe([
            'type' => 'video',
            'url' => 'https://www.youtube.com/watch?v=Win12rIicBI',
        ]);
});

test('removeImagePath and removeVideoUrl preserve other items', function () {
    $gallery = [
        'keep.png',
        'remove.png',
        ['type' => 'video', 'url' => 'https://www.youtube.com/watch?v=Win12rIicBI'],
        ['type' => 'video', 'url' => 'https://youtu.be/OtherVideoId1'],
    ];

    $afterImage = ProductGallery::removeImagePath($gallery, 'remove.png');
    expect($afterImage)->toHaveCount(3)
        ->and($afterImage[0])->toBe('keep.png');

    $afterVideo = ProductGallery::removeVideoUrl($afterImage, 'https://www.youtube.com/embed/Win12rIicBI');
    expect($afterVideo)->toHaveCount(2)
        ->and($afterVideo[1]['url'])->toBe('https://youtu.be/OtherVideoId1');
});
