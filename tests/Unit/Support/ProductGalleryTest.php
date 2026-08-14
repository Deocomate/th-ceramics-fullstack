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
        ->and($items[1]['thumb_url'])->toBe('https://img.youtube.com/vi/Win12rIicBI/maxresdefault.jpg')
        ->and($items[1]['embed_url'])->toStartWith('https://www.youtube.com/embed/Win12rIicBI?')
        ->and($items[1]['embed_url'])->toContain('enablejsapi=1')
        ->and($items[1]['embed_url'])->toContain('vq=hd1080')
        ->and($items[1]['embed_url'])->toContain('controls=0')
        ->and($items[1]['embed_url'])->toContain('modestbranding=1');
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

test('removeImagePaths and removeVideoUrls remove multiple items in one pass', function () {
    $gallery = [
        'keep.png',
        'remove-a.png',
        'remove-b.png',
        ['type' => 'video', 'url' => 'https://www.youtube.com/watch?v=Win12rIicBI'],
        ['type' => 'video', 'url' => 'https://youtu.be/OtherVideoId1'],
    ];

    $updated = ProductGallery::removeImagePaths($gallery, ['remove-a.png', 'remove-b.png']);
    $updated = ProductGallery::removeVideoUrls($updated, [
        'https://www.youtube.com/embed/Win12rIicBI',
        'https://youtu.be/OtherVideoId1',
    ]);

    expect($updated)->toBe(['keep.png']);
});

test('promoteImageToCover moves path to front', function () {
    $gallery = [
        'first.png',
        'second.png',
        ['type' => 'video', 'url' => 'https://www.youtube.com/watch?v=Win12rIicBI'],
    ];

    expect(ProductGallery::promoteImageToCover($gallery, 'second.png'))->toBe([
        'second.png',
        'first.png',
        ['type' => 'video', 'url' => 'https://www.youtube.com/watch?v=Win12rIicBI'],
    ]);
});

test('reorderMedia applies token order', function () {
    $gallery = [
        'a.png',
        'b.png',
        ['type' => 'video', 'url' => 'https://www.youtube.com/watch?v=Win12rIicBI'],
    ];

    $reordered = ProductGallery::reorderMedia($gallery, [
        'video:https://www.youtube.com/watch?v=Win12rIicBI',
        'image:b.png',
        'image:a.png',
    ]);

    expect($reordered)->toBe([
        ['type' => 'video', 'url' => 'https://www.youtube.com/watch?v=Win12rIicBI'],
        'b.png',
        'a.png',
    ]);
});

test('normalizes uploaded file videos separately from youtube', function () {
    $items = ProductGallery::normalize([
        'cover.png',
        ['type' => 'video', 'source' => 'file', 'path' => 'ngoi_am_duong_ct/videos/clip.mp4'],
        ['type' => 'video', 'url' => 'https://youtu.be/Win12rIicBI'],
    ]);

    expect($items)->toHaveCount(3)
        ->and($items[1]['type'])->toBe('video')
        ->and($items[1]['source'])->toBe('file')
        ->and($items[1]['path'])->toBe('ngoi_am_duong_ct/videos/clip.mp4')
        ->and($items[1]['display_url'])->toContain('clip.mp4')
        ->and($items[2]['source'])->toBe('youtube')
        ->and($items[2]['youtube_id'])->toBe('Win12rIicBI');
});

test('file video tokens and removal leave youtube items intact', function () {
    $gallery = [
        'cover.png',
        ['type' => 'video', 'source' => 'file', 'path' => 'ngoi_am_duong_ct/videos/clip.mp4'],
        ['type' => 'video', 'url' => 'https://www.youtube.com/watch?v=Win12rIicBI'],
    ];

    expect(ProductGallery::mediaToken($gallery[1]))->toBe('video-file:ngoi_am_duong_ct/videos/clip.mp4');

    $updated = ProductGallery::removeVideoPath($gallery, 'ngoi_am_duong_ct/videos/clip.mp4');

    expect($updated)->toHaveCount(2)
        ->and($updated[0])->toBe('cover.png')
        ->and($updated[1]['url'])->toBe('https://www.youtube.com/watch?v=Win12rIicBI');
});

test('video directory is derived from the image directory', function () {
    expect(ProductGallery::videoDirectoryFromImageDirectory('ngoi_am_duong_ct/images'))
        ->toBe('ngoi_am_duong_ct/videos')
        ->and(ProductGallery::videoDirectoryFromImageDirectory('custom/folder'))
        ->toBe('custom/folder/videos');
});
