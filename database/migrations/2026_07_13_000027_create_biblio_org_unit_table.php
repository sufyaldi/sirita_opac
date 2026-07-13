<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Junction table: biblio <-> org_units
     * Menyimpan klaim kepemilikan/relevansi judul buku oleh unit perpustakaan.
     * Digunakan untuk laporan akreditasi (BAN-PT/LAM):
     *   "Berapa judul yang diklaim oleh Prodi Fisika?"
     */
    public function up(): void
    {
        Schema::create('biblio_org_unit', function (Blueprint $table) {
            $table->integer('id')->autoIncrement();
            $table->integer('biblio_id');
            $table->integer('unit_id');
            $table->tinyInteger('is_core')->default(0)->comment('1 = buku inti/wajib mata kuliah');
            $table->string('notes', 255)->nullable()->comment('Catatan relevansi untuk akreditasi');
            $table->integer('tagged_by')->nullable()->comment('user_id yang menambahkan tag');
            $table->dateTime('tagged_at')->nullable();

            $table->unique(['biblio_id', 'unit_id'], 'biblio_unit_unique');
            $table->index('biblio_id');
            $table->index('unit_id');
            $table->index('is_core');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('biblio_org_unit');
    }
};
