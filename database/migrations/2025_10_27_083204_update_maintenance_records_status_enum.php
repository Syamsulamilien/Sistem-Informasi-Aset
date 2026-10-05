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
        // Update existing 'Completed' records to 'In Progress'
        DB::table('maintenance_records')
            ->where('status', 'Completed')
            ->update(['status' => 'Scheduled']); // Temporary change to valid value

        // Modify the enum column
        DB::statement("ALTER TABLE `maintenance_records` MODIFY COLUMN `status` ENUM('Scheduled', 'In Progress', 'Cancelled') NOT NULL");

        // Update back to 'In Progress'
        DB::table('maintenance_records')
            ->where('status', 'Scheduled')
            ->whereNotNull('performed_date') // Records that were previously 'Completed'
            ->update(['status' => 'In Progress']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Update 'In Progress' back to 'Completed'
        DB::table('maintenance_records')
            ->where('status', 'In Progress')
            ->update(['status' => 'Scheduled']); // Temporary change

        // Revert enum to old values
        DB::statement("ALTER TABLE `maintenance_records` MODIFY COLUMN `status` ENUM('Scheduled', 'Completed', 'Cancelled') NOT NULL");

        // Update back to 'Completed'
        DB::table('maintenance_records')
            ->where('status', 'Scheduled')
            ->whereNotNull('performed_date')
            ->update(['status' => 'Completed']);
    }
};