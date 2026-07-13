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
        Schema::create('item', function (Blueprint $table) {
            $table->integer('item_id')->autoIncrement();
            $table->integer('biblio_id')->nullable();
            $table->string('call_number', 50)->nullable();
            $table->integer('coll_type_id')->nullable();
            $table->string('item_code', 20)->nullable()->unique();
            $table->string('inventory_code', 200)->nullable();
            $table->date('received_date')->nullable();
            $table->string('supplier_id', 6)->nullable();
            $table->string('order_no', 20)->nullable();
            $table->string('location_id', 3)->nullable();
            $table->date('order_date')->nullable();
            $table->char('item_status_id', 3)->nullable();
            $table->string('site', 50)->nullable();
            $table->integer('source')->default(0);
            $table->string('invoice', 20)->nullable();
            $table->integer('price')->nullable();
            $table->string('price_currency', 10)->nullable();
            $table->date('invoice_date')->nullable();
            $table->dateTime('input_date');
            $table->dateTime('last_update')->nullable();
            $table->integer('uid')->nullable();

            // Indexes
            $table->index(['coll_type_id', 'location_id', 'item_status_id'], 'item_references_idx');
            $table->index('biblio_id', 'biblio_id_idx');
            $table->index('uid');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('item');
    }
};
