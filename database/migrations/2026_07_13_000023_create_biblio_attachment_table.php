<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('biblio_attachment', function (Blueprint $table) {
            $table->integer('biblio_id');
            $table->integer('file_id');
            $table->enum('placement', ['link', 'popup', 'embed'])->nullable();
            $table->enum('access_type', ['public', 'private'])->default('public');
            $table->text('access_limit')->nullable();

            $table->index('biblio_id');
            $table->index('file_id');
            $table->unique(['biblio_id', 'file_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('biblio_attachment');
    }
};
