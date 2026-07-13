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
        Schema::create('mst_member_type', function (Blueprint $table) {
            $table->integer('member_type_id')->autoIncrement();
            $table->string('member_type_name', 50)->unique();
            $table->integer('loan_limit');
            $table->integer('loan_periode');
            $table->integer('enable_reserve')->default(0);
            $table->integer('reserve_limit')->default(0);
            $table->integer('member_periode');
            $table->integer('reborrow_limit');
            $table->integer('fine_each_day');
            $table->integer('grace_periode')->default(0);
            $table->date('input_date');
            $table->date('last_update');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mst_member_type');
    }
};
