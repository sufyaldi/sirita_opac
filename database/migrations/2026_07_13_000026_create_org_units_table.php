<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('org_units', function (Blueprint $table) {
            $table->integer('id')->autoIncrement();
            $table->string('code', 20)->unique();
            $table->string('name', 200);
            $table->string('short_name', 50)->nullable();
            $table->enum('type', [
                'university',
                'main_library',
                'faculty',
                'department',
                'lp',
                'upt',
                'pusat',
                'other',
            ])->default('other');
            $table->integer('parent_id')->nullable();
            $table->tinyInteger('is_active')->default(1);
            $table->integer('sort_order')->default(0);
            $table->integer('student_count')->nullable()->comment('Jumlah mahasiswa aktif untuk perhitungan rasio koleksi:mahasiswa');
            $table->text('address')->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('email', 100)->nullable();
            $table->string('logo', 255)->nullable();
            $table->json('settings')->nullable()->comment('loan_days, fine_per_day, max_loans, open_days, dll');
            $table->dateTime('input_date')->nullable();
            $table->dateTime('last_update')->nullable();

            $table->index('parent_id');
            $table->index(['type', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('org_units');
    }
};
