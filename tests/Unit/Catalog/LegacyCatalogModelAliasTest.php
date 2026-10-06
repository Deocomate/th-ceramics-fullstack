<?php

use Illuminate\Database\Eloquent\Model;

test('legacy catalog model payloads deserialize through canonical aliases', function () {
    foreach (glob(dirname(__DIR__, 3).'/app/Models/*.php') ?: [] as $file) {
        $legacyClass = 'App\\Models\\'.pathinfo($file, PATHINFO_FILENAME);
        if ($legacyClass === 'App\\Models\\User') {
            continue;
        }
        $payload = sprintf('O:%d:"%s":0:{}', strlen($legacyClass), $legacyClass);

        $model = unserialize($payload);

        expect($model)->toBeInstanceOf(Model::class)
            ->and(get_class($model))->toStartWith('App\\Domains\\Catalog\\Infrastructure\\Models\\');
    }
});
