
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('assets', function (Blueprint $table) {
            $table->string('intensitas_pemakaian')->nullable()->after('penanggung_jawab');
            $table->integer('masa_pemakaian')->nullable()->after('intensitas_pemakaian');
            $table->string('masa_pemakaian_satuan')->default('Bulan')->after('masa_pemakaian');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('assets', function (Blueprint $table) {
            $table->dropColumn(['intensitas_pemakaian', 'masa_pemakaian', 'masa_pemakaian_satuan']);
        });
    }
};
