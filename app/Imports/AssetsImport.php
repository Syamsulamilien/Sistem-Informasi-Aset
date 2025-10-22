<?php
// ============================================================
// File: app/Imports/AssetsImport.php (UPDATED VERSION)
// ============================================================

namespace App\Imports;

use App\Models\Asset;
use App\Models\AssetType;
use App\Models\AssetHistory;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class AssetsImport implements ToModel, WithHeadingRow, WithValidation
{
    public function model(array $row)
    {
        // FIX: Cari asset_type_id berdasarkan nama atau code_prefix
        $assetType = AssetType::where('name', $row['jenis'])
            ->orWhere('code_prefix', $row['jenis'])
            ->first();
        
        // Jika tidak ditemukan, skip row ini
        if (!$assetType) {
            throw new \Exception("Jenis aset '{$row['jenis']}' tidak ditemukan. Gunakan nama atau prefix yang valid.");
        }
        
        // Generate asset code otomatis berdasarkan jenis
        $assetCode = isset($row['asset_code']) && !empty($row['asset_code']) 
            ? $row['asset_code'] 
            : Asset::generateAssetCode($assetType->id);
        
        $asset = Asset::create([
            'asset_code' => $assetCode,
            'asset_type_id' => $assetType->id,
            'brand' => $row['brand'],
            'model' => $row['model'],
            'serial_number' => $row['serial_number'],
            'description' => $row['description'] ?? null, // ✅ UBAH: dari ram_gb ke description
            'purchase_year' => $row['purchase_year'],
            'price' => $row['price'] ?? null, // ✅ TAMBAHAN: kolom price
            'condition' => $row['condition'],
            'status' => $row['status'],
            'location_id' => $row['location_id'],
            'warranty_expiry_date' => $row['warranty_expiry_date'] ?? null,
        ]);

        // Create history
        AssetHistory::create([
            'asset_id' => $asset->id,
            'action' => 'Tambah Aset',
            'to_location' => $asset->location_id,
            'notes' => 'Aset diimport dari Excel',
            'user_id' => auth()->id(),
        ]);

        return $asset;
    }

    public function rules(): array
    {
        return [
            'asset_code' => 'nullable|unique:assets',
            'jenis' => 'required|string', 
            'brand' => 'required',
            'model' => 'required',
            'serial_number' => 'required|unique:assets',
            'description' => 'nullable|string|max:1000', // ✅ UBAH: dari ram_gb ke description
            'purchase_year' => 'required|integer',
            'price' => 'nullable|numeric|min:0', // ✅ TAMBAHAN: validasi price
            'condition' => 'required|in:Baik,Rusak Ringan,Rusak Berat',
            'status' => 'required|in:Aktif,Nonaktif',
            'location_id' => 'required|exists:locations,id',
            'warranty_expiry_date' => 'nullable|date',
        ];
    }

    // Custom validation messages
    public function customValidationMessages()
    {
        return [
            'jenis.required' => 'Kolom jenis aset wajib diisi',
            'serial_number.unique' => 'Nomor seri sudah digunakan',
            'location_id.exists' => 'Lokasi tidak valid',
            'description.max' => 'Deskripsi maksimal 1000 karakter',
            'price.numeric' => 'Harga harus berupa angka',
            'price.min' => 'Harga tidak boleh negatif',
        ];
    }
}