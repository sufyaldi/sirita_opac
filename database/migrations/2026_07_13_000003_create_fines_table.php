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
        Schema::create('fines', function (Blueprint $table) {
            $table->integer('fines_id')->autoIncrement();
            $table->date('fines_date');
            $table->string('member_id', 20);
            $table->integer('debet')->default(0);
            $table->integer('credit')->default(0);
            $table->string('description', 255)->nullable();

            // Indexes
            $table->index('member_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fines');
    }
};
