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
                'name' => 'Ruang IT & Server',
                'floor' => 'Lantai 1',
                'unit' => 'IT Department',
                'description' => 'Ruang server dan teknisi IT sekolah',
            ],
            [
                'name' => 'Ruang Tata Usaha (TU)',
                'floor' => 'Lantai 1',
                'unit' => 'Tata Usaha',
                'description' => 'Ruang administrasi dan tata usaha sekolah',
            ],
            [
                'name' => 'Ruang UKS',
                'floor' => 'Lantai 1',
                'unit' => 'UKS',
                'description' => 'Unit Kesehatan Sekolah SMK Kesehatan Binatama',
            ],
            [
                'name' => 'Laboratorium Farmasi',
                'floor' => 'Lantai 1',
                'unit' => 'Farmasi',
                'description' => 'Laboratorium Praktik Farmasi',
            ],
            [
                'name' => 'Laboratorium Keperawatan',
                'floor' => 'Lantai 2',
                'unit' => 'Keperawatan',
                'description' => 'Laboratorium Praktik Keperawatan',
            ],
            [
                'name' => 'Laboratorium Komputer',
                'floor' => 'Lantai 2',
                'unit' => 'Lab Komputer',
                'description' => 'Laboratorium Komputer & CBT',
            ],
            [
                'name' => 'Perpustakaan',
                'floor' => 'Lantai 2',
                'unit' => 'Perpustakaan',
                'description' => 'Perpustakaan sekolah',
            ],
            [
                'name' => 'Ruang Guru',
                'floor' => 'Lantai 2',
                'unit' => 'Akademik',
                'description' => 'Ruang kerja guru dan tenaga pendidik',
            ],
            [
                'name' => 'Ruang Kepala Sekolah',
                'floor' => 'Lantai 3',
                'unit' => 'Manajemen',
                'description' => 'Ruang Kepala Sekolah SMK Kesehatan Binatama',
            ],
            [
                'name' => 'Ruang Rapat / Aula',
                'floor' => 'Lantai 3',
                'unit' => 'Manajemen',
                'description' => 'Ruang rapat yayasan dan aula pertemuan',
            ],
        ];

        foreach ($locations as $location) {
            Location::create($location);
        }
    }
}

