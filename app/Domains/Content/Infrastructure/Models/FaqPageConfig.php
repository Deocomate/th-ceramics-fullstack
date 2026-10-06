<?php

namespace App\Domains\Content\Infrastructure\Models;

use Illuminate\Database\Eloquent\Model;

class FaqPageConfig extends Model
{
    protected $table = 'page_faq';

    protected $primaryKey = 'page_faq_id';

    protected $fillable = [
        'banner_image',
    ];
}
