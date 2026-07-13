<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock_take_session', function (Blueprint $table) {
            $table->integer('session_id')->autoIncrement();
            $table->string('session_name', 255);
            $table->string('status', 50)->default('active'); // active, finished
            $table->timestamps();
        });

        Schema::create('stock_take_log', function (Blueprint $table) {
            $table->integer('log_id')->autoIncrement();
            $table->integer('session_id');
            $table->string('item_code', 100);
            $table->timestamps();

            // Foreign key
            $table->foreign('session_id')->references('session_id')->on('stock_take_session')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_take_log');
        Schema::dropIfExists('stock_take_session');
    }
};
