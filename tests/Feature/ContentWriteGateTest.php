<?php

use App\Http\Middleware\EnsureContentWritesOpen;
use App\Models\User;
use Illuminate\Support\Facades\File;

it('keeps public reads open while blocking admin content changes during reconciliation', function () {
    $path = storage_path(EnsureContentWritesOpen::LOCK_FILE);
    File::ensureDirectoryExists(dirname($path));
    file_put_contents($path, 'test');
    try {
        $this->actingAs(User::factory()->create(['role' => 'superadmin']));
        $this->post(route('admin.ngoi-am-duong-ct.store'), [])->assertStatus(423);
        $this->get('/up')->assertOk();
    } finally {
        @unlink($path);
    }
});
