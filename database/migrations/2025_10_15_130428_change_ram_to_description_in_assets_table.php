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
            // Tambah kolom description
            $table->text('description')->nullable()->after('serial_number');
        });
        
        // Hapus kolom ram_gb
        Schema::table('assets', function (Blueprint $table) {
            $table->dropColumn('ram_gb');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('assets', function (Blueprint $table) {
            // Kembalikan kolom ram_gb
            $table->integer('ram_gb')->nullable()->after('serial_number');
        });
        
        // Hapus kolom description
        Schema::table('assets', function (Blueprint $table) {
            $table->dropColumn('description');
        });
    }
};