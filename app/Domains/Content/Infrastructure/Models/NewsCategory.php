<?php

namespace App\Domains\Content\Infrastructure\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class NewsCategory extends Model
{
    protected $table = 'danh_muc_tin_tuc';

    protected $primaryKey = 'danh_muc_tin_tuc_id';

    protected $fillable = [
        'ten_danh_muc',
        'is_delete',
    ];

    public function articles(): HasMany
    {
        return $this->hasMany(NewsArticle::class, 'danh_muc_tin_tuc_id', 'danh_muc_tin_tuc_id');
    }

    public function tinTucs(): HasMany
    {
        return $this->articles();
    }
}
