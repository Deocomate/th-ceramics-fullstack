<?php

use App\Domains\Content\Infrastructure\Models\Faq;
use App\Domains\Content\Infrastructure\Models\FaqPageConfig;

test('faq page renders questions grouped by category', function () {
    if (FaqPageConfig::query()->count() === 0) {
        FaqPageConfig::query()->create([
            'banner_image' => 'assets/images/faq-banner.png',
        ]);
    }

    Faq::query()->create([
        'category' => 'san-pham',
        'question' => 'Cau hoi hien thi?',
        'answer' => 'Cau tra loi hien thi.',
        'sort_order' => 1,
        'is_active' => true,
    ]);

    $response = $this->get(route('client.faq'));

    $response
        ->assertOk()
        ->assertSee('Cau hoi hien thi?');

    expect($response->viewData('faqsGrouped')->keys()->all())->toContain('san-pham');
});
