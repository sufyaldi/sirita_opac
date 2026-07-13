<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('serial_subscription', function (Blueprint $table) {
            $table->integer('subscription_id')->autoIncrement();
            $table->string('title', 255);
            $table->string('publisher_name', 255)->nullable();
            $table->date('period_start');
            $table->date('period_end');
            $table->string('frequency', 50)->default('Bulanan');
            $table->string('notes', 255)->nullable();
            $table->timestamps();
        });

        Schema::create('serial_kardex', function (Blueprint $table) {
            $table->integer('kardex_id')->autoIncrement();
            $table->integer('subscription_id');
            $table->string('issue_no', 100);
            $table->date('publish_date')->nullable();
            $table->date('receive_date')->nullable();
            $table->string('status', 50)->default('expected'); // expected, received, missing
            $table->timestamps();

            // Foreign Key constraint
            $table->foreign('subscription_id')->references('subscription_id')->on('serial_subscription')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('serial_kardex');
        Schema::dropIfExists('serial_subscription');
    }
};
