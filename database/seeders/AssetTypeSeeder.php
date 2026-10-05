<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AssetType;

class AssetTypeSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('🚀 Membuat/Update data Asset Type...');

        $assetTypes = [
            // IT Equipment
            ['name' => 'PC', 'code_prefix' => 'PC', 'description' => 'Personal Computer / Desktop', 'is_active' => true],
            ['name' => 'Laptop', 'code_prefix' => 'LTP', 'description' => 'Laptop/Notebook', 'is_active' => true],
            ['name' => 'Printer', 'code_prefix' => 'PRT', 'description' => 'Printer/Scanner/Copier', 'is_active' => true],
            ['name' => 'Router', 'code_prefix' => 'RTR', 'description' => 'Router/Access Point', 'is_active' => true],
            ['name' => 'Switch', 'code_prefix' => 'SWT', 'description' => 'Network Switch', 'is_active' => true],
            ['name' => 'Server', 'code_prefix' => 'SRV', 'description' => 'Server', 'is_active' => true],
            ['name' => 'UPS', 'code_prefix' => 'UPS', 'description' => 'Uninterruptible Power Supply', 'is_active' => true],
            
            // Non-IT Equipment
            ['name' => 'Furniture', 'code_prefix' => 'FRN', 'description' => 'Meja, Kursi, Lemari, dll', 'is_active' => true],
            ['name' => 'AC', 'code_prefix' => 'AC', 'description' => 'Air Conditioner', 'is_active' => true],
            ['name' => 'Vehicle', 'code_prefix' => 'VEH', 'description' => 'Kendaraan Operasional', 'is_active' => true],
            ['name' => 'CCTV', 'code_prefix' => 'CCTV', 'description' => 'CCTV Camera & DVR', 'is_active' => true],
            ['name' => 'Projector', 'code_prefix' => 'PROJ', 'description' => 'Proyektor', 'is_active' => true],
            ['name' => 'Whiteboard', 'code_prefix' => 'WB', 'description' => 'Whiteboard', 'is_active' => true],
            ['name' => 'Display', 'code_prefix' => 'DISP', 'description' => 'TV/Monitor Display', 'is_active' => true],
            ['name' => 'Generator', 'code_prefix' => 'GEN', 'description' => 'Generator Set', 'is_active' => true],
        ];

        foreach ($assetTypes as $type) {
            // Gunakan updateOrCreate dengan code_prefix sebagai unique key
            AssetType::updateOrCreate(
                ['code_prefix' => $type['code_prefix']], // Cari berdasarkan code_prefix (unique)
                $type // Update atau create dengan data ini
            );
        }

        $this->command->info('✅ Berhasil membuat/update ' . count($assetTypes) . ' Asset Types');
    }
}