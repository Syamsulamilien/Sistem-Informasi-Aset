<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Ubah kolom kategori dari ENUM ke STRING agar bisa input manual
        DB::statement("ALTER TABLE asset_types MODIFY COLUMN kategori VARCHAR(255) NOT NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Kembalikan ke ENUM jika rollback
        DB::statement("ALTER TABLE asset_types MODIFY COLUMN kategori ENUM('Asset TI', 'Asset Rumah Tangga', 'Asset Transportasi', 'Asset Gizi', 'Asset Lainnya') NOT NULL DEFAULT 'Asset TI'");
    }
};
