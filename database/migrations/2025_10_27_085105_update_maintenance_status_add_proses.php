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
        // Untuk MySQL
        DB::statement("ALTER TABLE maintenance_records MODIFY COLUMN status ENUM('Scheduled', 'Proses', 'Completed', 'Cancelled') NOT NULL DEFAULT 'Scheduled'");
        
        // Jika menggunakan PostgreSQL, uncomment baris di bawah dan comment baris MySQL di atas
        // DB::statement("ALTER TABLE maintenance_records DROP CONSTRAINT maintenance_records_status_check");
        // DB::statement("ALTER TABLE maintenance_records ADD CONSTRAINT maintenance_records_status_check CHECK (status IN ('Scheduled', 'Proses', 'Completed', 'Cancelled'))");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Rollback ke status lama (tanpa 'Proses')
        DB::statement("ALTER TABLE maintenance_records MODIFY COLUMN status ENUM('Scheduled', 'Completed', 'Cancelled') NOT NULL DEFAULT 'Scheduled'");
        
        // Untuk PostgreSQL:
        // DB::statement("ALTER TABLE maintenance_records DROP CONSTRAINT maintenance_records_status_check");
        // DB::statement("ALTER TABLE maintenance_records ADD CONSTRAINT maintenance_records_status_check CHECK (status IN ('Scheduled', 'Completed', 'Cancelled'))");
    }
};