<?php

namespace App\Domains\Content\Infrastructure\Models;

use App\Domains\Content\Domain\FaqCategory;
use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    protected $table = 'faqs';

    protected $primaryKey = 'faq_id';

    public const CATEGORIES = FaqCategory::ALL;

    protected $fillable = [
        'category',
        'question',
        'answer',
        'sort_order',
        'is_active',
        'is_delete',
    ];

    public function getRouteKeyName(): string
    {
        return 'faq_id';
    }

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }
}
