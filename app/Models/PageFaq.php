<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PageFaq extends Model
{
    protected $table = 'page_faq';

    protected $primaryKey = 'page_faq_id';

    protected $fillable = [
        'banner_image',
    ];
}
