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
        Schema::create('files', function (Blueprint $table) {
            $table->integer('file_id')->autoIncrement();
            $table->text('file_title');
            $table->text('file_name');
            $table->text('file_url')->nullable();
            $table->text('file_dir')->nullable();
            $table->string('mime_type', 100)->nullable();
            $table->text('file_desc')->nullable();
            $table->text('file_key')->nullable();
            $table->integer('uploader_id')->default(1);
            $table->dateTime('input_date');
            $table->dateTime('last_update');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('files');
    }
};
