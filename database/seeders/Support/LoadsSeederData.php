<?php

namespace Database\Seeders\Support;

trait LoadsSeederData
{
    /** @return array<int, array<string, mixed>> */
    protected function seederData(string $table): array
    {
        $path = database_path("seeders/data/{$table}.php");

        if (! is_file($path)) {
            throw new \RuntimeException("Seeder data file not found: {$path}");
        }

        /** @var array<int, array<string, mixed>> $data */
        $data = require $path;

        return $data;
    }

    /** @return array<string, mixed>|null */
    protected function seederDataFirst(string $table): ?array
    {
        $rows = $this->seederData($table);

        return $rows[0] ?? null;
    }
}
