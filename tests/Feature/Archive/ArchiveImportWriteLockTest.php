<?php

namespace Tests\Feature\Archive;

use App\Domains\Archive\ContentArchiveService;
use App\Domains\Archive\Jobs\ContentArchiveJob;
use App\Domains\Archive\Jobs\ContentArchiveJob as LegacyContentArchiveJob;
use App\Domains\Content\Infrastructure\Services\ContentWriteLock;
use App\Domains\Identity\Infrastructure\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Queue;
use Mockery;
use RuntimeException;
use Tests\TestCase;

class ArchiveImportWriteLockTest extends TestCase
{
    use RefreshDatabase;

    private string $statusFile;

    protected function setUp(): void
    {
        parent::setUp();
        $this->statusFile = storage_path('app/private/content-archives/test-status.json');
        File::ensureDirectoryExists(dirname($this->statusFile));
        ContentWriteLock::manualUnlock();
    }

    protected function tearDown(): void
    {
        ContentWriteLock::manualUnlock();
        if (is_file($this->statusFile)) {
            @unlink($this->statusFile);
        }
        parent::tearDown();
    }

    public function test_content_write_lock_token_lifecycle(): void
    {
        $token1 = 'token-alpha';
        $token2 = 'token-beta';

        $this->assertFalse(ContentWriteLock::isLocked());

        // Acquire lock with token 1
        $this->assertTrue(ContentWriteLock::acquire($token1));
        $this->assertTrue(ContentWriteLock::isLocked());
        $this->assertSame($token1, ContentWriteLock::currentOwner());

        // Token 2 cannot acquire while token 1 holds it
        $this->assertFalse(ContentWriteLock::acquire($token2));

        // Token 2 cannot release token 1's lock
        $this->assertFalse(ContentWriteLock::release($token2));
        $this->assertTrue(ContentWriteLock::isLocked());

        // Token 1 successfully releases lock
        $this->assertTrue(ContentWriteLock::release($token1));
        $this->assertFalse(ContentWriteLock::isLocked());
    }

    public function test_manual_commands_cannot_replace_or_release_import_lock(): void
    {
        $this->assertTrue(ContentWriteLock::acquire('import-job'));

        $this->artisan('content:writes lock')->assertFailed();
        $this->artisan('content:writes unlock')->assertFailed();
        $this->assertSame('import-job', ContentWriteLock::currentOwner());

        $this->assertTrue(ContentWriteLock::release('import-job'));
        $this->artisan('content:writes lock')->assertSuccessful();
        $this->assertSame('manual', ContentWriteLock::currentOwner());
        $this->artisan('content:writes lock')->assertSuccessful();
        $this->artisan('content:writes unlock')->assertSuccessful();
        $this->assertFalse(ContentWriteLock::isLocked());
    }

    public function test_import_job_acquires_lock_and_releases_in_finally_on_success(): void
    {
        $mockArchive = Mockery::mock(ContentArchiveService::class);
        $mockArchive->shouldReceive('import')
            ->once()
            ->with('dummy.zip')
            ->andReturnUsing(function () {
                // While inside import, lock must be active
                $this->assertTrue(ContentWriteLock::isLocked());

                return ['added' => 1, 'updated' => 0, 'skipped' => 0];
            });

        $job = new ContentArchiveJob('import', $this->statusFile, 'dummy.zip');
        $job->handle($mockArchive);

        // After job finishes, lock must be released
        $this->assertFalse(ContentWriteLock::isLocked());
        $this->assertFileExists($this->statusFile);

        $status = json_decode((string) file_get_contents($this->statusFile), true);
        $this->assertSame('completed', $status['state']);
    }

    public function test_import_job_releases_lock_in_finally_on_failure(): void
    {
        $mockArchive = Mockery::mock(ContentArchiveService::class);
        $mockArchive->shouldReceive('import')
            ->once()
            ->with('failing.zip')
            ->andReturnUsing(function () {
                $this->assertTrue(ContentWriteLock::isLocked());
                throw new RuntimeException('Import exploded');
            });

        $job = new ContentArchiveJob('import', $this->statusFile, 'failing.zip');

        try {
            $job->handle($mockArchive);
            $this->fail('Expected exception was not thrown');
        } catch (RuntimeException $e) {
            $this->assertSame('Import exploded', $e->getMessage());
        }

        // Lock must NOT be orphaned
        $this->assertFalse(ContentWriteLock::isLocked());
        $this->assertFileExists($this->statusFile);

        $status = json_decode((string) file_get_contents($this->statusFile), true);
        $this->assertSame('failed', $status['state']);
    }

    public function test_route_apply_is_not_blocked_before_dispatch_when_lock_is_active(): void
    {
        $admin = User::factory()->create(['role' => 'superadmin']);

        // Even if system has active lock, archive management routes remain accessible
        ContentWriteLock::manualLock();
        $this->assertTrue(ContentWriteLock::isLocked());

        Queue::fake();

        $response = $this->actingAs($admin)
            ->withSession(['content_archive_file' => 'import-0123456789abcdef0123456789abcdef.zip'])
            ->post(route('admin.content-archive.apply'));

        // It should NOT receive HTTP 423
        $this->assertNotSame(423, $response->getStatusCode());
    }

    public function test_admin_content_writes_are_blocked_during_lock(): void
    {
        $admin = User::factory()->create(['role' => 'superadmin']);

        ContentWriteLock::manualLock();
        $this->assertTrue(ContentWriteLock::isLocked());

        // Admin attempting to create/update content receives 423
        $response = $this->actingAs($admin)
            ->post(route('admin.ngoi-am-duong-ct.store'), []);

        $response->assertStatus(423);
    }

    public function test_legacy_content_archive_job_serializes_and_deserializes(): void
    {
        $legacy = new LegacyContentArchiveJob('export', $this->statusFile);
        $serialized = serialize($legacy);
        $unserialized = unserialize($serialized);

        $this->assertInstanceOf(LegacyContentArchiveJob::class, $unserialized);
        $this->assertInstanceOf(ContentArchiveJob::class, $unserialized);
        $this->assertSame('export', $unserialized->action);
    }
}
