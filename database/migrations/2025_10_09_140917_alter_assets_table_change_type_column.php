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
    Schema::table('assets', function (Blueprint $table) {
        // Perhatikan bahwa Anda mungkin perlu menginstal 'doctrine/dbal'
        $table->string('type', 100)->change(); 
    });
}

public function down(): void
{
    Schema::table('assets', function (Blueprint $table) {
        // Jika ingin mengembalikannya ke enum lama (opsional)
        // DB::statement("ALTER TABLE assets CHANGE type type ENUM('PC', 'Laptop', 'Printer', 'Perangkat Lain') NOT NULL");
        // Atau biarkan kosong jika Anda tidak berencana untuk rollback
    });
}
};
