<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('assets', function (Blueprint $table) {
            $table->id();
            $table->string('asset_code')->unique();
            $table->string('type', 100);
            $table->string('brand');
            $table->string('model');
            $table->string('serial_number')->unique();
            $table->integer('ram_gb')->nullable();
            $table->year('purchase_year');
            $table->enum('condition', ['Baik', 'Rusak Ringan', 'Rusak Berat']);
            $table->enum('status', ['Aktif', 'Nonaktif'])->default('Aktif');
            $table->foreignId('location_id')->constrained()->onDelete('cascade');
            $table->date('warranty_expiry_date')->nullable();
            $table->string('photo')->nullable();
            $table->string('invoice_file')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assets');
    }
};