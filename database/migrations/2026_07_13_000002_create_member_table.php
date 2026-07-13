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
        Schema::create('member', function (Blueprint $table) {
            $table->string('member_id', 20)->primary();
            $table->string('member_name', 100);
            $table->integer('gender');
            $table->date('birth_date')->nullable();
            $table->integer('member_type_id')->nullable();
            $table->string('member_address', 255)->nullable();
            $table->string('member_mail_address', 255)->nullable();
            $table->string('member_email', 100)->nullable();
            $table->string('postal_code', 20)->nullable();
            $table->string('inst_name', 100)->nullable();
            $table->integer('is_new')->nullable();
            $table->string('member_image', 200)->nullable();
            $table->string('pin', 50)->nullable();
            $table->string('member_phone', 50)->nullable();
            $table->string('member_fax', 50)->nullable();
            $table->date('member_since_date')->nullable();
            $table->date('register_date')->nullable();
            $table->date('expire_date');
            $table->text('member_notes')->nullable();
            $table->smallInteger('is_pending')->default(0);
            $table->string('mpasswd', 64)->nullable();
            $table->dateTime('last_login')->nullable();
            $table->string('last_login_ip', 50)->nullable();
            $table->date('input_date')->nullable();
            $table->date('last_update')->nullable();

            // Indexes
            $table->index('member_name');
            $table->index('member_type_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('member');
    }
};
