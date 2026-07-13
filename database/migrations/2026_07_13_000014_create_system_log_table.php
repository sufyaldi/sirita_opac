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
        Schema::create('system_log', function (Blueprint $table) {
            $table->integer('log_id')->autoIncrement();
            $table->enum('log_type', ['staff', 'member', 'system'])->default('staff');
            $table->string('id', 50)->nullable();
            $table->string('log_location', 50);
            $table->string('sub_module', 50)->nullable();
            $table->string('action', 50)->nullable();
            $table->text('log_msg');
            $table->dateTime('log_date');

            $table->index('log_type');
            $table->index('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('system_log');
    }
};
