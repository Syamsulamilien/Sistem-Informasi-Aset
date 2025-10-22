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
        Schema::table('assets', function (Blueprint $table) {
            // Hapus kolom invoice_file (file upload)
            if (Schema::hasColumn('assets', 'invoice_file')) {
                $table->dropColumn('invoice_file');
            }
            
            // Tambah kolom invoice_number (teks)
            if (!Schema::hasColumn('assets', 'invoice_number')) {
                $table->string('invoice_number')->nullable()->after('warranty_expiry_date');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('assets', function (Blueprint $table) {
            // Kembalikan ke struktur lama
            if (Schema::hasColumn('assets', 'invoice_number')) {
                $table->dropColumn('invoice_number');
            }
            
            if (!Schema::hasColumn('assets', 'invoice_file')) {
                $table->string('invoice_file')->nullable();
            }
        });
    }
};