<?php

use App\Domains\Media\Infrastructure\ImageReferenceRewriter;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

beforeEach(function () {
    Schema::create('rewriter_pages', function (Blueprint $table) {
        $table->id('page_id');
        $table->string('image')->nullable();
        $table->longText('gallery')->nullable();
        $table->text('body')->nullable();
    });

    $this->rewriter = new ImageReferenceRewriter;
    $this->map = [
        'du_an/images/a.png' => 'du_an/images/a.webp',
        'du_an/images/b.jpg' => 'du_an/images/b.webp',
        'assets/images/hero.png' => 'assets/images/hero.webp',
    ];
});

function rewriterPage(array $values): int
{
    return DB::table('rewriter_pages')->insertGetId($values);
}

function rewriterPageValue(int $id, string $column): ?string
{
    return DB::table('rewriter_pages')->where('page_id', $id)->value($column);
}

test('a plain column value is replaced', function () {
    $id = rewriterPage(['image' => 'du_an/images/a.png']);

    $report = $this->rewriter->rewrite($this->map);

    expect(rewriterPageValue($id, 'image'))->toBe('du_an/images/a.webp')
        ->and($report['changes'])->toBe(['rewriter_pages.image' => 1]);
});

test('paths inside JSON with escaped slashes are replaced and keep their escaping', function () {
    $id = rewriterPage(['gallery' => json_encode(['assets/images/hero.png', 'du_an/images/a.png'])]);

    $this->rewriter->rewrite($this->map);

    expect(rewriterPageValue($id, 'gallery'))
        ->toBe('["assets\/images\/hero.webp","du_an\/images\/a.webp"]');
});

test('several paths in one value are all replaced', function () {
    $id = rewriterPage([
        'body' => '<img src="/storage/du_an/images/a.png"><img src="http://localhost/storage/du_an/images/b.jpg"> <img src="/assets/images/hero.png">',
    ]);

    $this->rewriter->rewrite($this->map);

    expect(rewriterPageValue($id, 'body'))->toBe(
        '<img src="/storage/du_an/images/a.webp"><img src="http://localhost/storage/du_an/images/b.webp"> <img src="/assets/images/hero.webp">'
    );
});

test('JSON-escaped non-ASCII file names are replaced', function () {
    $id = rewriterPage(['gallery' => json_encode(['tin_tuc/Gốm thumb.png'])]);

    $this->rewriter->rewrite(['tin_tuc/Gốm thumb.png' => 'tin_tuc/Gốm thumb.webp']);

    expect(json_decode(rewriterPageValue($id, 'gallery'), true))->toBe(['tin_tuc/Gốm thumb.webp']);
});

test('paths missing from the map and longer paths ending in a mapped path are left alone', function () {
    $unmapped = rewriterPage(['image' => 'du_an/images/c.png']);
    $longer = rewriterPage(['image' => 'seeders/du_an/images/a.png']);
    $storageCopy = rewriterPage(['image' => 'storage/assets/images/hero.png']);
    $suffixed = rewriterPage(['image' => 'du_an/images/a.png.bak']);

    $report = $this->rewriter->rewrite($this->map);

    expect($report['changes'])->toBe([])
        ->and(rewriterPageValue($unmapped, 'image'))->toBe('du_an/images/c.png')
        ->and(rewriterPageValue($longer, 'image'))->toBe('seeders/du_an/images/a.png')
        ->and(rewriterPageValue($storageCopy, 'image'))->toBe('storage/assets/images/hero.png')
        ->and(rewriterPageValue($suffixed, 'image'))->toBe('du_an/images/a.png.bak');
});

test('a mapped path followed by more path characters is a different file', function (string $value) {
    $id = rewriterPage(['image' => $value]);

    $this->rewriter->rewrite($this->map);

    expect(rewriterPageValue($id, 'image'))->toBe($value);
})->with(['du_an/images/a.png-300x300', 'du_an/images/a.png_old', 'du_an/images/a.png/thumb.webp']);

test('upper-case extensions and doubly escaped slashes are replaced', function () {
    $upper = rewriterPage(['image' => 'du_an/images/IMG_01.JPG']);
    $escaped = rewriterPage(['gallery' => json_encode(json_encode(['du_an/images/a.png']))]);

    $this->rewriter->rewrite($this->map + ['du_an/images/IMG_01.JPG' => 'du_an/images/IMG_01.webp']);

    expect(rewriterPageValue($upper, 'image'))->toBe('du_an/images/IMG_01.webp')
        ->and(json_decode(json_decode(rewriterPageValue($escaped, 'gallery'), true), true))->toBe(['du_an/images/a.webp'])
        ->and($this->rewriter->references())->toEqualCanonicalizing(['du_an/images/IMG_01.webp', 'du_an/images/a.webp']);
});

test('framework tables are not rewritten', function () {
    DB::table('cache')->insert(['key' => 'page', 'value' => 'du_an/images/a.png', 'expiration' => 0]);

    $this->rewriter->rewrite($this->map);

    expect(DB::table('cache')->where('key', 'page')->value('value'))->toBe('du_an/images/a.png')
        ->and($this->rewriter->tables()['scanned'])->not->toHaveKeys(['cache', 'migrations', 'sessions', 'protection_violations']);
});

test('tables without a single-column primary key are skipped and reported', function () {
    Schema::create('rewriter_pivot', function (Blueprint $table) {
        $table->unsignedInteger('left_id');
        $table->unsignedInteger('right_id');
        $table->string('image');
        $table->primary(['left_id', 'right_id']);
    });
    DB::table('rewriter_pivot')->insert(['left_id' => 1, 'right_id' => 1, 'image' => 'du_an/images/a.png']);

    $report = $this->rewriter->rewrite($this->map);

    expect($report['skipped'])->toContain('rewriter_pivot')
        ->and(DB::table('rewriter_pivot')->value('image'))->toBe('du_an/images/a.png');
});

test('only the tables of the current schema are listed', function () {
    Schema::partialMock()
        ->shouldReceive('getTables')
        ->once()
        ->with(Schema::getCurrentSchemaName())
        ->andReturn([]);

    expect($this->rewriter->tables())->toBe(['scanned' => [], 'skipped' => []]);
});

test('tables of another schema on the same connection are not rewritten', function () {
    // SQLite refuses ATTACH inside the transaction that wraps each test.
    DB::rollBack();

    try {
        DB::statement("ATTACH DATABASE ':memory:' AS other_site");
        DB::statement('CREATE TABLE other_site.pages (id INTEGER PRIMARY KEY, image TEXT)');
        DB::statement("INSERT INTO other_site.pages (id, image) VALUES (1, 'du_an/images/a.png')");

        expect(array_column(Schema::getTables(), 'schema'))->toContain('other_site');

        $report = $this->rewriter->rewrite($this->map);

        expect($report['changes'])->toBe([])
            ->and(DB::selectOne('SELECT image FROM other_site.pages')->image)->toBe('du_an/images/a.png');
    } finally {
        DB::statement('DETACH DATABASE other_site');
        DB::beginTransaction();
    }
});

test('a dry run counts changes without writing', function () {
    $id = rewriterPage(['image' => 'du_an/images/a.png']);

    $report = $this->rewriter->rewrite($this->map, apply: false);

    expect($report['changes'])->toBe(['rewriter_pages.image' => 1])
        ->and(rewriterPageValue($id, 'image'))->toBe('du_an/images/a.png');
});

test('the reversed map restores the original data', function () {
    $values = [
        'image' => '/du_an/images/a.png',
        'gallery' => json_encode(['assets/images/hero.png', 'du_an/images/b.jpg', 'du_an/images/keep.webp']),
        'body' => '<p>Ảnh: <img src="/storage/du_an/images/b.jpg"></p>',
    ];
    $id = rewriterPage($values);

    $this->rewriter->rewrite($this->map);
    expect(rewriterPageValue($id, 'image'))->toBe('/du_an/images/a.webp');

    $this->rewriter->rewrite(array_flip($this->map));

    expect((array) DB::table('rewriter_pages')->where('page_id', $id)->first(array_keys($values)))->toBe($values);
});

test('references lists every stored image path without JSON escaping', function () {
    rewriterPage([
        'image' => 'du_an/images/a.png',
        'gallery' => json_encode(['assets/images/hero.png', 'tin_tuc/Gốm thumb.webp']),
        'body' => '<p>Xem <img src="/storage/du_an/images/b.jpg"> và tệp ghi-chu.pdf</p>',
    ]);

    expect($this->rewriter->references())->toEqualCanonicalizing([
        'du_an/images/a.png',
        'assets/images/hero.png',
        'tin_tuc/Gốm thumb.webp',
        '/storage/du_an/images/b.jpg',
    ]);
});
