<?php

namespace Database\Seeders;

use App\Models\Asset;
use App\Models\AssetHistory;
use App\Models\Location;
use Illuminate\Database\Seeder;

class AssetSeeder extends Seeder
{
    public function run(): void
    {
        $locations = Location::all();
        $types = ['PC', 'Laptop', 'Printer', 'Perangkat Lain'];
        $brands = [
            'PC' => ['Dell', 'HP', 'Lenovo', 'Asus'],
            'Laptop' => ['Dell', 'HP', 'Lenovo', 'Asus', 'Acer'],
            'Printer' => ['Canon', 'Epson', 'HP', 'Brother'],
            'Perangkat Lain' => ['Cisco', 'TP-Link', 'Ubiquiti'],
        ];
        $conditions = ['Baik', 'Rusak Ringan', 'Rusak Berat'];
        $statuses = ['Aktif', 'Nonaktif'];

        for ($i = 1; $i <= 50; $i++) {
            $type = $types[array_rand($types)];
            $brand = $brands[$type][array_rand($brands[$type])];
            $year = rand(2019, 2024);
            $warrantyYear = $year + rand(1, 3);
            
            $asset = Asset::create([
                'asset_code' => 'AST-' . date('Y') . '-' . str_pad($i, 4, '0', STR_PAD_LEFT),
                'type' => $type,
                'brand' => $brand,
                'model' => $brand . ' Model ' . rand(1000, 9999),
                'serial_number' => 'SN' . strtoupper(substr(md5($i), 0, 12)),
                'ram_gb' => in_array($type, ['PC', 'Laptop']) ? [4, 8, 16, 32][array_rand([4, 8, 16, 32])] : null,
                'purchase_year' => $year,
                'condition' => $conditions[array_rand($conditions)],
                'status' => $statuses[array_rand($statuses)],
                'location_id' => $locations->random()->id,
                'warranty_expiry_date' => date('Y-m-d', strtotime("$warrantyYear-12-31")),
            ]);

            // Create initial history
            AssetHistory::create([
                'asset_id' => $asset->id,
                'action' => 'Update Data',
                'to_location' => $asset->location_id,
                'notes' => 'Aset awal ditambahkan dari seeder',
                'user_id' => 1,
            ]);
        }
    }
}