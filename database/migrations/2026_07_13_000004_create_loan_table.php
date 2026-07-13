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
        Schema::create('loan', function (Blueprint $table) {
            $table->integer('loan_id')->autoIncrement();
            $table->string('item_code', 20)->nullable();
            $table->string('member_id', 20)->nullable();
            $table->date('loan_date');
            $table->date('due_date');
            $table->integer('renewed')->default(0);
            $table->integer('loan_rules_id')->default(0);
            $table->date('actual')->nullable();
            $table->integer('is_lent')->default(0);
            $table->integer('is_return')->default(0);
            $table->date('return_date')->nullable();
            $table->dateTime('input_date')->nullable();
            $table->dateTime('last_update')->nullable();
            $table->integer('uid')->nullable();

            // Indexes
            $table->index('item_code');
            $table->index('member_id');
            $table->index(['input_date', 'last_update', 'uid']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loan');
    }
};
