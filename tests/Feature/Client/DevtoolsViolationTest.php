<?php

use App\Domains\Content\Infrastructure\Services\HomePageConfigService;
use App\Domains\Identity\Infrastructure\Models\User;
use App\Domains\Protection\Infrastructure\Models\ProtectionViolation;
use Illuminate\Support\Facades\Log;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

const DEVTOOLS_SESSION_A = 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa';
const DEVTOOLS_SESSION_B = 'bbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbb';
const DEVTOOLS_SESSION_C = 'cccccccccccccccccccccccccccccccccccccccc';

function reportDevtools(TestCase $test, string $sessionId = DEVTOOLS_SESSION_A): TestResponse
{
    return $test->withCredentials()
        ->withCookie(config('session.cookie'), $sessionId)
        ->postJson(route('client.protection.report'), [
            'detector' => 1,
            'path' => '/san-pham/ngoi-am-duong',
        ]);
}

beforeEach(function () {
    app(HomePageConfigService::class)->update(['is_devtools_guard_enabled' => true]);
});

test('first report points to the notice page and stores one violation', function () {
    reportDevtools($this)
        ->assertOk()
        ->assertExactJson(['redirect' => route('client.protection.notice')]);

    expect(ProtectionViolation::query()->count())->toBe(1);

    $violation = ProtectionViolation::query()->sole();

    expect($violation->session_hash)->toBe(hash('sha256', DEVTOOLS_SESSION_A))
        ->and($violation->ip_address)->toBe('127.0.0.1')
        ->and($violation->user_agent)->toBe('Symfony')
        ->and($violation->path)->toBe('/san-pham/ngoi-am-duong')
        ->and($violation->detector)->toBe('define-id')
        ->and($violation->user_id)->toBeNull()
        ->and($violation->created_at)->not->toBeNull();
});

test('third report inside the window points to the warning page', function () {
    Log::spy();

    reportDevtools($this)->assertJson(['redirect' => route('client.protection.notice')]);
    $this->travel(6)->seconds();
    reportDevtools($this)->assertJson(['redirect' => route('client.protection.notice')]);
    $this->travel(6)->seconds();
    reportDevtools($this)->assertExactJson(['redirect' => route('client.protection.warning')]);

    expect(ProtectionViolation::query()->count())->toBe(3);

    Log::shouldHaveReceived('warning')
        ->once()
        ->withArgs(fn (string $message, array $context) => $context['ip'] === '127.0.0.1'
            && $context['count'] === 3
            && $context['path'] === '/san-pham/ngoi-am-duong'
            && array_key_exists('user_agent', $context));
});

test('escalation threshold is configurable', function () {
    config(['content_protection.devtools.threshold' => 1]);

    reportDevtools($this)->assertExactJson(['redirect' => route('client.protection.warning')]);
});

test('two reports from one session within five seconds count once', function () {
    reportDevtools($this)->assertOk();
    $this->travel(4)->seconds();
    reportDevtools($this)->assertExactJson(['redirect' => route('client.protection.notice')]);

    expect(ProtectionViolation::query()->count())->toBe(1);
});

test('reports from other sessions are not deduplicated', function () {
    reportDevtools($this, DEVTOOLS_SESSION_A)->assertOk();
    reportDevtools($this, DEVTOOLS_SESSION_B)->assertOk();

    expect(ProtectionViolation::query()->count())->toBe(2);
});

test('count is the higher of the session count and the ip count', function () {
    reportDevtools($this, DEVTOOLS_SESSION_A);
    reportDevtools($this, DEVTOOLS_SESSION_B);

    reportDevtools($this, DEVTOOLS_SESSION_C)
        ->assertExactJson(['redirect' => route('client.protection.warning')]);
});

test('ip counting can be switched off', function () {
    config(['content_protection.devtools.count_by_ip' => false]);

    reportDevtools($this, DEVTOOLS_SESSION_A);
    reportDevtools($this, DEVTOOLS_SESSION_B);

    reportDevtools($this, DEVTOOLS_SESSION_C)
        ->assertExactJson(['redirect' => route('client.protection.notice')]);
});

test('violations older than the window are not counted', function () {
    reportDevtools($this);
    $this->travel(6)->seconds();
    reportDevtools($this);
    $this->travel(25)->hours();

    reportDevtools($this)->assertExactJson(['redirect' => route('client.protection.notice')]);
});

test('signed in customer is recorded with the account id', function () {
    $customer = User::factory()->create(['role' => 'customer']);

    $this->actingAs($customer);
    reportDevtools($this)->assertOk();

    expect(ProtectionViolation::query()->sole()->user_id)->toBe($customer->id);
});

test('signed in admin gets no content and no violation', function (string $role) {
    $this->actingAs(User::factory()->create(['role' => $role]));

    reportDevtools($this)->assertNoContent();

    expect(ProtectionViolation::query()->count())->toBe(0);
})->with(['superadmin', 'admin']);

test('crawler user agent gets no content and no violation', function () {
    $this->withHeader('User-Agent', 'Mozilla/5.0 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)');

    reportDevtools($this)->assertNoContent();

    expect(ProtectionViolation::query()->count())->toBe(0);
});

test('report gets no content and no violation while the devtools guard is off', function () {
    app(HomePageConfigService::class)->update([
        'is_content_protection_enabled' => true,
        'is_devtools_guard_enabled' => false,
    ]);

    reportDevtools($this)->assertNoContent();

    expect(ProtectionViolation::query()->count())->toBe(0);
});

test('report without a csrf token is rejected', function () {
    // The framework skips CSRF verification only while it believes tests are running.
    $this->app->detectEnvironment(fn () => 'local');

    reportDevtools($this)->assertStatus(419);

    expect(ProtectionViolation::query()->count())->toBe(0);
});

test('report endpoint is limited to twenty requests per minute', function () {
    app(HomePageConfigService::class)->update(['is_devtools_guard_enabled' => false]);

    foreach (range(1, 20) as $attempt) {
        reportDevtools($this)->assertNoContent();
    }

    reportDevtools($this)->assertTooManyRequests();
});

test('oversized and malformed report fields are stored safely', function () {
    $this->withHeader('User-Agent', str_repeat('u', 400))
        ->withCredentials()
        ->withCookie(config('session.cookie'), DEVTOOLS_SESSION_A)
        ->postJson(route('client.protection.report'), [
            'detector' => '<script>',
            'path' => '/'.str_repeat('p', 400),
        ])
        ->assertOk();

    $violation = ProtectionViolation::query()->sole();

    expect($violation->detector)->toBe('unknown')
        ->and(strlen($violation->path))->toBe(255)
        ->and(strlen($violation->user_agent))->toBe(255);
});

test('warning page shows the record code of the escalated violation', function () {
    config(['content_protection.devtools.threshold' => 1]);

    reportDevtools($this)->assertJson(['redirect' => route('client.protection.warning')]);

    $this->get(route('client.protection.warning'))
        ->assertOk()
        ->assertSee('#'.ProtectionViolation::query()->sole()->id);
});

test('violations older than the retention period are pruned', function () {
    reportDevtools($this, DEVTOOLS_SESSION_A);
    $this->travel(91)->days();
    reportDevtools($this, DEVTOOLS_SESSION_B);

    $this->artisan('model:prune', ['--model' => [ProtectionViolation::class]])->assertSuccessful();

    expect(ProtectionViolation::query()->pluck('session_hash')->all())
        ->toBe([hash('sha256', DEVTOOLS_SESSION_B)]);
});
