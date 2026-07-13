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
        Schema::create('mst_loan_rules', function (Blueprint $table) {
            $table->integer('loan_rules_id')->autoIncrement();
            $table->integer('member_type_id')->default(0);
            $table->integer('coll_type_id')->default(0);
            $table->integer('gmd_id')->default(0);
            $table->integer('loan_limit')->default(0);
            $table->integer('loan_periode')->default(0);
            $table->integer('reborrow_limit')->default(0);
            $table->integer('fine_each_day')->default(0);
            $table->integer('grace_periode')->default(0);
            $table->date('input_date')->nullable();
            $table->date('last_update')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mst_loan_rules');
    }
};
