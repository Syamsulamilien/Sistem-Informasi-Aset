<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('asset_types', function (Blueprint $table) {
            $table->enum('kategori', [
                'Asset TI',
                'Asset Rumah Tangga',
                'Asset Transportasi',
                'Asset Gizi',
                'Asset Lainnya'
            ])->default('Asset TI')->after('code_prefix');
        });
    }

    public function down(): void
    {
        Schema::table('asset_types', function (Blueprint $table) {
            $table->dropColumn('kategori');
        });
    }
};