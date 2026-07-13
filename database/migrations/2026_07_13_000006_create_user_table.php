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
        Schema::create('user', function (Blueprint $table) {
            $table->integer('user_id')->autoIncrement();
            $table->string('username', 50)->unique();
            $table->string('realname', 100);
            $table->string('passwd', 64);
            $table->text('2fa')->nullable();
            $table->string('email', 200)->nullable();
            $table->smallInteger('user_type')->nullable();
            $table->string('user_image', 250)->nullable();
            $table->text('social_media')->nullable();
            $table->dateTime('last_login')->nullable();
            $table->string('last_login_ip', 50)->nullable();
            $table->string('groups', 200)->nullable();
            $table->string('forgot', 80)->nullable();
            $table->text('admin_template')->nullable();
            $table->date('input_date')->nullable();
            $table->date('last_update')->nullable();

            // Indexes
            $table->index('realname');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user');
    }
};
