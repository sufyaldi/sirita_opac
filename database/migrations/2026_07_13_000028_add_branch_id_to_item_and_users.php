<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tambahkan branch_id (kepemilikan fisik eksemplar) ke tabel item.
     * Tambahkan user_org_units untuk akses pustakawan per unit.
     */
    public function up(): void
    {
        // Matikan strict mode sql sementara untuk bypass error 0000-00-00 00:00:00
        $oldSqlMode = Illuminate\Support\Facades\DB::select("SELECT @@SESSION.sql_mode as mode")[0]->mode;
        Illuminate\Support\Facades\DB::statement("SET SESSION sql_mode = ''");

        // Kepemilikan fisik eksemplar per unit
        Schema::table('item', function (Blueprint $table) {
            $table->integer('branch_id')->nullable()->after('uid')
                  ->comment('Unit perpustakaan pemilik eksemplar ini');
            $table->index('branch_id');
        });

        // Kembalikan sql mode semula
        Illuminate\Support\Facades\DB::statement("SET SESSION sql_mode = '{$oldSqlMode}'");

        // Tabel pivot: user <-> org_units (Odoo-style company_ids)
        Schema::create('user_org_units', function (Blueprint $table) {
            $table->integer('id')->autoIncrement();
            $table->integer('user_id');
            $table->integer('unit_id');
            $table->enum('role', ['admin', 'librarian', 'viewer'])->default('librarian');
            $table->tinyInteger('is_default')->default(0);
            $table->dateTime('created_at')->nullable();

            $table->unique(['user_id', 'unit_id'], 'user_unit_unique');
            $table->index('user_id');
            $table->index('unit_id');
        });
    }

    public function down(): void
    {
        Schema::table('item', function (Blueprint $table) {
            $table->dropColumn('branch_id');
        });
        Schema::dropIfExists('user_org_units');
    }
};
