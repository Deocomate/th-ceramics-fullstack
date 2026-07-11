<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Catalog extends Model
{
    use HasFactory;

    protected $table = 'catalog';

    protected $primaryKey = 'catalog_id';

    protected $fillable = [
        'tieu_de',
        'anh_dai_dien',
        'file',
    ];
}
