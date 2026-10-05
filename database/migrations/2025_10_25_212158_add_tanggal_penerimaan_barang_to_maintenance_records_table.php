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
        Schema::table('maintenance_records', function (Blueprint $table) {
            // Menambahkan kolom tanggal_penerimaan_barang setelah performed_date
            $table->date('tanggal_penerimaan_barang')
                  ->nullable()
                  ->after('performed_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('maintenance_records', function (Blueprint $table) {
            $table->dropColumn('tanggal_penerimaan_barang');
        });
    }
};