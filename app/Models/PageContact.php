<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PageContact extends Model
{
    protected $table = 'page_contact';

    protected $primaryKey = 'page_contact_id';

    protected $fillable = [
        'map_image',
        'hotline',
        'zalo_link',
        'form_title',
    ];
}
