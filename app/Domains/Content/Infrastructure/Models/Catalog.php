<?php

namespace App\Domains\Content\Infrastructure\Models;

use Database\Factories\CatalogFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Catalog extends Model
{
    use HasFactory;

    protected static function newFactory(): CatalogFactory
    {
        return CatalogFactory::new();
    }

    protected $table = 'catalog';

    protected $primaryKey = 'catalog_id';

    protected $fillable = [
        'tieu_de',
        'anh_dai_dien',
        'file',
        'pages',
    ];

    protected $casts = [
        'pages' => 'array',
    ];

    /**
     * Page images the public reader shows in place of the original file.
     *
     * @return list<array{path: string, w: int, h: int, pdf_page: int, side: string}>
     */
    public function pageItems(): array
    {
        return array_values((array) ($this->pages['items'] ?? []));
    }
}
