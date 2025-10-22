<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Perubahan:
     * - Hapus kolom 'floor'
     * - Kolom 'name' tetap (akan menjadi "Nama Unit")
     * - Kolom 'unit' akan menjadi 'description' tambahan
     */
    public function up(): void
    {
        Schema::table('locations', function (Blueprint $table) {
            // Hapus kolom floor jika ada
            if (Schema::hasColumn('locations', 'floor')) {
                $table->dropColumn('floor');
            }
            
            // Kolom name, unit, dan description sudah ada, tidak perlu diubah
            // Hanya perubahan di label UI saja:
            // - 'name' = Nama Unit (di UI)
            // - 'unit' = tidak digunakan lagi (atau bisa dihapus)
            // - 'description' = Deskripsi
            
            // OPSIONAL: Jika ingin hapus kolom 'unit' juga
            // if (Schema::hasColumn('locations', 'unit')) {
            //     $table->dropColumn('unit');
            // }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('locations', function (Blueprint $table) {
            // Kembalikan kolom floor
            if (!Schema::hasColumn('locations', 'floor')) {
                $table->string('floor')->nullable()->after('name');
            }
        });
    }
};