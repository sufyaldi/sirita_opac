<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel konfigurasi sistem umum (key-value pair).
     */
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->string('setting_key', 100)->primary();
            $table->text('setting_value')->nullable();
            $table->string('setting_description', 255)->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
