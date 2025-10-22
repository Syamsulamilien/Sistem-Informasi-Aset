<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB; // Tambahkan ini jika Anda menggunakan DB::statement di down()

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('asset_histories', function (Blueprint $table) {
            // Ubah tipe data kolom 'action' menjadi string (VARCHAR) dengan panjang 100
            $table->string('action', 100)->change(); 
        });
    }

    public function down(): void
    {
        Schema::table('asset_histories', function (Blueprint $table) {
            // Anda bisa mengembalikannya ke tipe sebelumnya (misalnya ENUM jika itu tipe awalnya)
            // Jika awalnya ENUM('Update', 'Pindah'), Anda bisa kembalikan seperti ini:
            // DB::statement("ALTER TABLE asset_histories CHANGE `action` `action` ENUM('Update', 'Pindah') NOT NULL");
            
            // Atau jika awalnya VARCHAR(20)
            // $table->string('action', 20)->change(); 
        });
    }
};
