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
        Schema::create('holiday', function (Blueprint $table) {
            $table->integer('holiday_id')->autoIncrement();
            $table->string('holiday_dayname', 20);
            $table->date('holiday_date')->nullable();
            $table->string('description', 100)->nullable();

            $table->unique(['holiday_dayname', 'holiday_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('holiday');
    }
};
