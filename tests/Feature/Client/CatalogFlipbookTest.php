<?php

use App\Domains\Content\Infrastructure\Models\Catalog;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    Storage::fake('public');
    Storage::fake('local');
});

function catalogWithPages(): Catalog
{
    return Catalog::factory()->create([
        'file' => 'catalog/files/book.pdf',
        'pages' => ['batch' => 'b1', 'items' => [
            ['path' => 'catalog/pages/1/b1/000.webp', 'w' => 1414, 'h' => 2000, 'pdf_page' => 1, 'side' => 'full'],
            ['path' => 'catalog/pages/1/b1/001.webp', 'w' => 1414, 'h' => 2000, 'pdf_page' => 2, 'side' => 'left'],
            ['path' => 'catalog/pages/1/b1/002.webp', 'w' => 1414, 'h' => 2000, 'pdf_page' => 2, 'side' => 'right'],
        ]],
    ]);
}

test('the reader is not found until the catalog has page images', function (?array $pages) {
    $catalog = Catalog::factory()->create(['file' => 'catalog/files/book.pdf', 'pages' => $pages]);

    $this->get(route('client.dich-vu.tai-catalog.read', $catalog->catalog_id))->assertNotFound();
})->with([
    'no manifest' => [null],
    'empty manifest' => [['batch' => 'b1', 'items' => []]],
]);

test('the reader lists page images and never mentions the PDF', function () {
    $catalog = catalogWithPages();

    $content = $this->get(route('client.dich-vu.tai-catalog.read', $catalog->catalog_id))
        ->assertOk()
        ->getContent();

    expect($content)
        ->toContain('catalog\/pages\/1\/b1\/000.webp')
        ->toContain('catalog\/pages\/1\/b1\/002.webp')
        ->toContain('"pdfPage":2')
        ->toContain('"side":"right"')
        ->not->toContain('.pdf')
        ->not->toContain('pdf.min.js')
        ->not->toContain('pdf.worker');
});

test('the catalog list links to the reader only for catalogs with page images', function () {
    $ready = catalogWithPages();
    $pending = Catalog::factory()->create(['file' => 'catalog/files/pending.pdf']);

    $this->get(route('client.dich-vu.tai-catalog'))
        ->assertOk()
        ->assertSee(route('client.dich-vu.tai-catalog.read', $ready->catalog_id), false)
        ->assertDontSee(route('client.dich-vu.tai-catalog.read', $pending->catalog_id), false)
        ->assertDontSee('.pdf', false);
});

test('the original PDF has no public URL', function () {
    Storage::disk('local')->put('catalog/files/book.pdf', '%PDF-1.4');

    expect($this->get('/storage/catalog/files/book.pdf')->getStatusCode())->toBeIn([403, 404]);
});
