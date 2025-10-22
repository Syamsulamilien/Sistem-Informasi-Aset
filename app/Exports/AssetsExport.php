<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class AssetsExport implements FromCollection, WithHeadings, WithMapping
{
    protected $query;

    public function __construct($query)
    {
        $this->query = $query;
    }

    public function collection()
    {
        // TAMBAHKAN: Load relasi assetType
        return $this->query->with('assetType', 'location')->get();
    }

    public function headings(): array
    {
        return [
            'Kode Aset',
            'Jenis', // UPDATE: Ganti header
            'Prefix', // TAMBAHKAN: Prefix untuk referensi
            'Merek',
            'Model',
            'Nomor Seri',
            'Deskripsi',
            'Tahun Pembelian',
            'Kondisi',
            'Status',
            'Lokasi',
            'Lokasi ID', // TAMBAHKAN: Untuk import
            'Garansi Berakhir',
            'Dibuat Pada',
        ];
    }

    public function map($asset): array
    {
        return [
            $asset->asset_code,
            $asset->assetType->name ?? '-', // FIX: Ganti $asset->type
            $asset->assetType->code_prefix ?? '-', // TAMBAHKAN
            $asset->brand,
            $asset->model,
            $asset->serial_number,
            $asset->description,
            $asset->purchase_year,
            $asset->condition,
            $asset->status,
            $asset->location->name ?? '-',
            $asset->location_id, // TAMBAHKAN: Untuk import
            $asset->warranty_expiry_date ? $asset->warranty_expiry_date->format('Y-m-d') : '-',
            $asset->created_at->format('Y-m-d H:i:s'),
        ];
    }
}