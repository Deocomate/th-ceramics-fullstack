<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('page_factory', function (Blueprint $table) {
            if (! Schema::hasColumn('page_factory', 'gallery_2')) {
                $table->json('gallery_2')->nullable()->after('gallery_1');
            }
        });

        DB::table('page_factory')
            ->select('page_factory_id', 'intro_description', 'process_description', 'process_bottom_desc')
            ->orderBy('page_factory_id')
            ->chunkById(100, function ($records) {
                foreach ($records as $record) {
                    DB::table('page_factory')
                        ->where('page_factory_id', $record->page_factory_id)
                        ->update([
                            'intro_description' => $this->wrapLegacyContent($record->intro_description),
                            'process_description' => $this->wrapLegacyContent($record->process_description),
                            'process_bottom_desc' => $this->wrapLegacyContent($record->process_bottom_desc),
                        ]);
                }
            }, 'page_factory_id');
    }

    public function down(): void
    {
        Schema::table('page_factory', function (Blueprint $table) {
            if (Schema::hasColumn('page_factory', 'gallery_2')) {
                $table->dropColumn('gallery_2');
            }
        });
    }

    private function wrapLegacyContent(?string $content): string
    {
        if ($content === null || trim($content) === '') {
            return json_encode([], JSON_UNESCAPED_UNICODE);
        }

        $decoded = json_decode($content, true);

        if (is_array($decoded) && $this->looksLikeBlockArray($decoded)) {
            return json_encode($decoded, JSON_UNESCAPED_UNICODE);
        }

        return json_encode([
            [
                'type' => 'paragraph',
                'content' => $content,
            ],
        ], JSON_UNESCAPED_UNICODE);
    }

    private function looksLikeBlockArray(array $blocks): bool
    {
        if ($blocks === []) {
            return true;
        }

        foreach ($blocks as $block) {
            if (! is_array($block) || ! isset($block['type'])) {
                return false;
            }
        }

        return true;
    }
};
