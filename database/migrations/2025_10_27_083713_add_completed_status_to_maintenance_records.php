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
        // Modify the enum column to include all 4 statuses
        DB::statement("ALTER TABLE `maintenance_records` MODIFY COLUMN `status` ENUM('Scheduled', 'In Progress', 'Completed', 'Cancelled') NOT NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Update 'Completed' to 'In Progress' before removing it
        DB::table('maintenance_records')
            ->where('status', 'Completed')
            ->update(['status' => 'In Progress']);

        // Revert enum to 3 statuses
        DB::statement("ALTER TABLE `maintenance_records` MODIFY COLUMN `status` ENUM('Scheduled', 'In Progress', 'Cancelled') NOT NULL");
    }
};