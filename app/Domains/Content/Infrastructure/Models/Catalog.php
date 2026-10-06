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
    ];
}
