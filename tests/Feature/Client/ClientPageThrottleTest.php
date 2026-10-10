<?php

beforeEach(function () {
    config(['content_protection.page_rate_limit' => 3]);
});

test('client pages return 429 once the per minute limit is exceeded', function () {
    foreach (range(1, 3) as $attempt) {
        $this->get(route('client.home'))->assertOk();
    }

    $this->get(route('client.home'))->assertTooManyRequests();
});

test('client page limit is shared across client routes', function () {
    $this->get(route('client.auth.login'))->assertOk();
    $this->get(route('client.home'))->assertOk();
    $this->get(route('client.auth.register'))->assertOk();

    $this->get(route('client.showroom'))->assertTooManyRequests();
});

test('client page limit is counted per ip address', function () {
    foreach (range(1, 4) as $attempt) {
        $response = $this->get(route('client.home'));
    }

    $response->assertTooManyRequests();

    $this->withServerVariables(['REMOTE_ADDR' => '203.0.113.9'])
        ->get(route('client.home'))
        ->assertOk();
});

test('client page limit ignores forwarded and crawler headers', function () {
    foreach (range(1, 3) as $attempt) {
        $this->get(route('client.home'))->assertOk();
    }

    $this->withHeaders([
        'X-Forwarded-For' => '203.0.113.9',
        'User-Agent' => 'Mozilla/5.0 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)',
    ])->get(route('client.home'))->assertTooManyRequests();
});

test('admin routes are not limited by the client page limit', function () {
    foreach (range(1, 5) as $attempt) {
        $this->get(route('admin.auth.login'))->assertOk();
    }
});
