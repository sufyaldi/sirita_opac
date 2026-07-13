<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel berita/pengumuman perpustakaan untuk halaman depan OPAC.
     */
    public function up(): void
    {
        Schema::create('promotions', function (Blueprint $table) {
            $table->integer('id')->autoIncrement();
            $table->string('title', 200);
            $table->text('content');
            $table->string('image_url', 255)->nullable();
            $table->tinyInteger('is_active')->default(1);
            $table->integer('sort_order')->default(0);
            $table->dateTime('publish_date')->nullable();
            $table->dateTime('input_date')->nullable();
            $table->dateTime('last_update')->nullable();
            
            $table->index('is_active');
            $table->index('sort_order');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('promotions');
    }
};
