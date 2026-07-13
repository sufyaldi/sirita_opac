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
        Schema::create('member_visitors', function (Blueprint $table) {
            $table->integer('id')->autoIncrement();
            $table->string('member_id', 50)->nullable(); // ID anggota jika terdaftar
            $table->string('name', 100);
            $table->string('institution', 150)->nullable(); // Institusi pengunjung
            $table->string('visitor_type', 50)->default('Umum'); // Jenis pengunjung (Mahasiswa, Dosen, Umum, dll)
            $table->timestamps();
            
            $table->index('member_id');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('member_visitors');
    }
};
