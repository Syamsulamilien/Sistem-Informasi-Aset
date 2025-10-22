<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('asset_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('asset_id')->constrained()->onDelete('cascade');
            $table->enum('action', ['Pindah Ruangan', 'Nonaktif', 'Update Data', 'Maintenance']);
            $table->foreignId('from_location')->nullable()->constrained('locations')->onDelete('set null');
            $table->foreignId('to_location')->nullable()->constrained('locations')->onDelete('set null');
            $table->text('notes')->nullable();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('asset_histories');
    }
};
