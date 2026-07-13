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
        Schema::create('mst_gmd', function (Blueprint $table) {
            $table->integer('gmd_id')->autoIncrement();
            $table->string('gmd_code', 3)->nullable()->unique();
            $table->string('gmd_name', 30)->unique();
            $table->string('icon_image', 100)->nullable();
            $table->date('input_date');
            $table->date('last_update')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mst_gmd');
    }
};
