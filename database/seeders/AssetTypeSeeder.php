<?php

namespace Database\Seeders;

use App\Models\AssetType;
use Illuminate\Database\Seeder;

class AssetTypeSeeder extends Seeder
{
    public function run(): void
    {
        $assetTypes = [
            [
                'name' => 'PC Desktop',
                'code_prefix' => 'PC',
                'description' => 'Komputer desktop/tower',
                'is_active' => true,
            ],
            [
                'name' => 'Laptop',
                'code_prefix' => 'LTP',
                'description' => 'Laptop/Notebook',
                'is_active' => true,
            ],
            [
                'name' => 'Printer',
                'code_prefix' => 'PRT',
                'description' => 'Printer/Scanner',
                'is_active' => true,
            ],
            [
                'name' => 'Router',
                'code_prefix' => 'RTR',
                'description' => 'Router jaringan',
                'is_active' => true,
            ],
            [
                'name' => 'Switch',
                'code_prefix' => 'SWT',
                'description' => 'Network switch',
                'is_active' => true,
            ],
            [
                'name' => 'Access Point',
                'code_prefix' => 'AP',
                'description' => 'Wireless access point',
                'is_active' => true,
            ],
            [
                'name' => 'Server',
                'code_prefix' => 'SRV',
                'description' => 'Server komputer',
                'is_active' => true,
            ],
            [
                'name' => 'UPS',
                'code_prefix' => 'UPS',
                'description' => 'Uninterruptible Power Supply',
                'is_active' => true,
            ],
            [
                'name' => 'Monitor',
                'code_prefix' => 'MON',
                'description' => 'Monitor display',
                'is_active' => true,
            ],
            [
                'name' => 'Projector',
                'code_prefix' => 'PRJ',
                'description' => 'LCD/LED Projector',
                'is_active' => true,
            ],
            [
                'name' => 'Keyboard',
                'code_prefix' => 'KBD',
                'description' => 'Keyboard input device',
                'is_active' => true,
            ],
            [
                'name' => 'Mouse',
                'code_prefix' => 'MOU',
                'description' => 'Mouse input device',
                'is_active' => true,
            ],
            [
                'name' => 'External HDD',
                'code_prefix' => 'HDD',
                'description' => 'External hard disk drive',
                'is_active' => true,
            ],
            [
                'name' => 'Webcam',
                'code_prefix' => 'CAM',
                'description' => 'Web camera',
                'is_active' => true,
            ],
            [
                'name' => 'Perangkat Lain',
                'code_prefix' => 'DEV',
                'description' => 'Perangkat IT lainnya',
                'is_active' => true,
            ],
        ];

        foreach ($assetTypes as $type) {
            AssetType::create($type);
        }
    }
}
