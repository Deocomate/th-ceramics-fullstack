<?php

namespace Database\Seeders\Support;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

trait SeedsFromSqlData
{
    use LoadsSeederData;

    protected function truncateTables(string ...$tables): void
    {
        $driver = DB::getDriverName();

        if ($driver === 'mysql') {
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        } elseif ($driver === 'sqlite') {
            DB::statement('PRAGMA foreign_keys = OFF;');
        }

        foreach ($tables as $table) {
            DB::table($table)->truncate();
        }

        if ($driver === 'mysql') {
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        } elseif ($driver === 'sqlite') {
            DB::statement('PRAGMA foreign_keys = ON;');
        }
    }

    /**
     * @param  class-string<Model>  $modelClass
     * @param  array<string>  $except
     */
    protected function seedFromData(string $table, string $modelClass, array $except = ['created_at', 'updated_at']): void
    {
        Model::unguarded(function () use ($table, $modelClass, $except): void {
            foreach ($this->seederData($table) as $row) {
                foreach ($except as $column) {
                    unset($row[$column]);
                }

                $modelClass::create($row);
            }
        });
    }

    /**
     * @param  class-string<Model>  $modelClass
     */
    protected function upsertFromData(string $table, string $modelClass, string $primaryKey): void
    {
        Model::unguarded(function () use ($table, $modelClass, $primaryKey): void {
            foreach ($this->seederData($table) as $row) {
                $id = $row[$primaryKey];
                unset($row[$primaryKey], $row['created_at'], $row['updated_at']);

                $modelClass::updateOrCreate(
                    [$primaryKey => $id],
                    $row
                );
            }
        });
    }

    /**
     * @param  array<string, mixed>  $row
     * @return array<string, mixed>
     */
    protected function withoutTimestamps(array $row): array
    {
        unset($row['created_at'], $row['updated_at']);

        return $row;
    }
}
