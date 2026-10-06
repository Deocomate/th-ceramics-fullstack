<?php

namespace App\Domains\Content\Infrastructure\Models;

use Illuminate\Database\Eloquent\Model;

class InstallationGuide extends Model
{
    protected $table = 'thi_cong';

    protected $primaryKey = 'thi_cong';

    protected $fillable = [
        'tieu_de',
        'anh',
        'link_youtube',
    ];
}
