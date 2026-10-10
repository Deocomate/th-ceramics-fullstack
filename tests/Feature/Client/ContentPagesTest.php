<?php

use App\Domains\Content\Infrastructure\Models\ContactPageConfig;
use App\Domains\Content\Infrastructure\Models\FactoryPageConfig;
use App\Domains\Content\Infrastructure\Models\Faq;
use App\Domains\Content\Infrastructure\Models\FaqPageConfig;

test('contact page renders', function () {
    ContactPageConfig::query()->create([
        'hotline' => '0909 123 456',
        'form_title' => 'Lien he tu van',
    ]);

    $response = $this->get(route('client.contact'));

    $response
        ->assertOk()
        ->assertSee('Lien he tu van');

    expect($response->viewData('contact'))->toBeInstanceOf(ContactPageConfig::class);
});

test('faq page renders questions grouped by category', function () {
    FaqPageConfig::query()->create([
        'banner_image' => 'assets/images/faq-banner.png',
    ]);
    $category = array_key_first(Faq::CATEGORIES);
    Faq::query()->create([
        'category' => $category,
        'question' => 'Ngoi co ben khong?',
        'answer' => 'Rat ben.',
        'sort_order' => 1,
        'is_active' => true,
    ]);

    $response = $this->get(route('client.faq'));

    $response
        ->assertOk()
        ->assertSee('Ngoi co ben khong?');

    expect($response->viewData('faqPage'))->toBeInstanceOf(FaqPageConfig::class);
    expect($response->viewData('faqsGrouped')->keys()->all())->toBe([$category]);
});

test('factory page renders', function () {
    FactoryPageConfig::query()->create([
        'intro_title' => 'Xuong san xuat Hai Thanh',
    ]);

    $response = $this->get(route('client.factory'));

    $response
        ->assertOk()
        ->assertSee('Xuong san xuat Hai Thanh');

    expect($response->viewData('factory'))->toBeInstanceOf(FactoryPageConfig::class);
});
