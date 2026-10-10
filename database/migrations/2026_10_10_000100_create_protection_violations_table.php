<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('protection_violations', function (Blueprint $table) {
            $table->id();
            $table->char('session_hash', 64)->index();
            $table->string('ip_address', 45)->index();
            $table->string('user_agent', 255)->nullable();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('detector', 32);
            $table->string('path', 255);
            $table->timestamp('created_at')->nullable()->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('protection_violations');
    }
};
