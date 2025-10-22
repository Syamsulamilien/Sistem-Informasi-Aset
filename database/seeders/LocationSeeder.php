<?php

namespace Database\Seeders;

use App\Models\Location;
use Illuminate\Database\Seeder;

class LocationSeeder extends Seeder
{
    public function run(): void
    {
        $locations = [
            [
                'name' => 'Ruang IT',
                'floor' => 'Lantai 1',
                'unit' => 'IT Department',
                'description' => 'Ruang server dan teknisi IT',
            ],
            [
                'name' => 'Ruang Administrasi',
                'floor' => 'Lantai 1',
                'unit' => 'Administrasi',
                'description' => 'Ruang administrasi dan keuangan',
            ],
            [
                'name' => 'Ruang IGD',
                'floor' => 'Lantai 1',
                'unit' => 'IGD',
                'description' => 'Instalasi Gawat Darurat',
            ],
            [
                'name' => 'Ruang Farmasi',
                'floor' => 'Lantai 1',
                'unit' => 'Farmasi',
                'description' => 'Bagian farmasi rumah sakit',
            ],
            [
                'name' => 'Ruang Rawat Inap A',
                'floor' => 'Lantai 2',
                'unit' => 'Rawat Inap',
                'description' => 'Ruang rawat inap kelas A',
            ],
            [
                'name' => 'Ruang Rawat Inap B',
                'floor' => 'Lantai 2',
                'unit' => 'Rawat Inap',
                'description' => 'Ruang rawat inap kelas B',
            ],
            [
                'name' => 'Ruang Laboratorium',
                'floor' => 'Lantai 2',
                'unit' => 'Laboratorium',
                'description' => 'Laboratorium medis',
            ],
            [
                'name' => 'Ruang Radiologi',
                'floor' => 'Lantai 2',
                'unit' => 'Radiologi',
                'description' => 'Bagian radiologi dan rontgen',
            ],
            [
                'name' => 'Ruang Direktur',
                'floor' => 'Lantai 3',
                'unit' => 'Manajemen',
                'description' => 'Ruang direktur rumah sakit',
            ],
            [
                'name' => 'Ruang Rapat',
                'floor' => 'Lantai 3',
                'unit' => 'Manajemen',
                'description' => 'Ruang rapat dan pertemuan',
            ],
        ];

        foreach ($locations as $location) {
            Location::create($location);
        }
    }
}

