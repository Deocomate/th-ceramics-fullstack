<?php

use App\Domains\Identity\Infrastructure\Models\User;
use Illuminate\Contracts\Database\ModelIdentifier;
use Illuminate\Queue\SerializesAndRestoresModelIdentifiers;

test('queued model identifiers from the original user namespace restore the current user', function () {
    $user = User::factory()->create();
    $identifier = new ModelIdentifier('App\\Models\\User', $user->id, [], $user->getConnectionName());
    $restorer = new class
    {
        use SerializesAndRestoresModelIdentifiers;

        public function restore(ModelIdentifier $identifier): mixed
        {
            return $this->getRestoredPropertyValue($identifier);
        }
    };

    $restored = $restorer->restore(unserialize(serialize($identifier)));

    expect($restored)->toBeInstanceOf(User::class)
        ->and($restored->id)->toBe($user->id)
        ->and($restored->email)->toBe($user->email);
});
