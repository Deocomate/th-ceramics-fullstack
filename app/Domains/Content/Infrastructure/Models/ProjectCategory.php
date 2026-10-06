<?php

namespace App\Domains\Content\Infrastructure\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProjectCategory extends Model
{
    protected $table = 'danh_muc_du_an';

    protected $primaryKey = 'danh_muc_du_an_id';

    protected $fillable = [
        'ten_danh_muc',
        'is_delete',
    ];

    public function projects(): HasMany
    {
        return $this->hasMany(Project::class, 'danh_muc_du_an_id', 'danh_muc_du_an_id');
    }

    public function duAns(): HasMany
    {
        return $this->projects();
    }
}
