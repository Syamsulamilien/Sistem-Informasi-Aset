<?php
// ============================================================
// File: app/Imports/AssetsImport.php (UPDATED VERSION)
// ============================================================

namespace App\Imports;

use App\Models\Asset;
use App\Models\AssetType;
use App\Models\AssetHistory;
use App\Models\Location;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;

class AssetsImport implements ToModel, WithHeadingRow, WithValidation, SkipsEmptyRows
{
    public function isEmptyWhen(array $row): bool
    {
        // Abaikan baris komentar atau instruksi yang diawali tanda pagar (#)
        $firstVal = trim((string) reset($row));
        if (str_starts_with($firstVal, '#')) {
            return true;
        }

        // Cek format inventaris sekolah (kolom nama_barang / nama)
        if (isset($row['nama_barang']) || isset($row['nama'])) {
            $nama = $row['nama_barang'] ?? $row['nama'] ?? '';
            return empty(trim((string)$nama));
        }

        // Format template standar
        return empty($row['jenis']) && empty($row['brand']) && empty($row['model']) && empty($row['serial_number']);
    }

    public function prepareForValidation(array $data, int $index): array
    {
        // Trim semua nilai string
        foreach ($data as $key => $val) {
            if (is_string($val)) {
                $data[$key] = trim($val);
            }
        }

        // DUKUNG FORMAT DAFTAR INVENTARIS SEKOLAH: 'nama_barang', 'merk', 'jumlah', 'kondisi', 'lokasi'
        if ((isset($data['nama_barang']) || isset($data['nama'])) && !isset($data['jenis'])) {
            $namaBarang = trim((string) ($data['nama_barang'] ?? $data['nama'] ?? ''));
            $merk = trim((string) ($data['merk'] ?? ''));
            $namaLower = strtolower($namaBarang);

            // Deteksi jenis aset berdasarkan nama barang
            $detectedJenis = 'Perangkat Lain';
            if (str_contains($namaLower, 'server')) {
                $detectedJenis = 'Server';
            } elseif (str_contains($namaLower, 'laptop')) {
                $detectedJenis = 'Laptop';
            } elseif (str_contains($namaLower, 'komputer') || str_contains($namaLower, 'client') || str_contains($namaLower, 'pc')) {
                $detectedJenis = 'PC';
            } elseif (str_contains($namaLower, 'monitor') || str_contains($namaLower, 'layar')) {
                $detectedJenis = 'Monitor';
            } elseif (str_contains($namaLower, 'switch')) {
                $detectedJenis = 'Switch';
            } elseif (str_contains($namaLower, 'ups')) {
                $detectedJenis = 'UPS';
            } elseif (str_contains($namaLower, 'mouse')) {
                $detectedJenis = 'Mouse';
            } elseif (str_contains($namaLower, 'keyboard')) {
                $detectedJenis = 'Keyboard';
            } elseif (str_contains($namaLower, 'ac') || str_contains($namaLower, 'air conditioner')) {
                $detectedJenis = 'AC';
            } elseif (str_contains($namaLower, 'proyektor') || str_contains($namaLower, 'projector') || str_contains($namaLower, 'lcd')) {
                $detectedJenis = 'Projector';
            } elseif (str_contains($namaLower, 'cctv')) {
                $detectedJenis = 'CCTV';
            } elseif (str_contains($namaLower, 'printer')) {
                $detectedJenis = 'Printer';
            } elseif (str_contains($namaLower, 'scanner')) {
                $detectedJenis = 'scanner';
            } elseif (str_contains($namaLower, 'access point')) {
                $detectedJenis = 'Access Point';
            } elseif (
                str_contains($namaLower, 'meja') || 
                str_contains($namaLower, 'kursi') || 
                str_contains($namaLower, 'almari') || 
                str_contains($namaLower, 'lemari') || 
                str_contains($namaLower, 'karpet') || 
                str_contains($namaLower, 'gordyn') || 
                str_contains($namaLower, 'white board')
            ) {
                $detectedJenis = 'Furniture';
            }

            $data['jenis'] = $detectedJenis;
            $data['brand'] = !empty($merk) ? $merk : $namaBarang;
            $data['model'] = !empty($merk) ? $namaBarang : 'Standar';
            $data['description'] = $namaBarang . (!empty($merk) ? ' - ' . $merk : '');
            $data['purchase_year'] = (int) date('Y');
            
            // Lokasi
            $lokasiNama = $data['lokasi'] ?? 'Lab Komputer';
            $loc = null;
            try {
                $loc = Location::where('name', 'like', "%{$lokasiNama}%")->first();
            } catch (\Throwable $e) {
                // Abaikan jika database/tabel tidak tersedia di environment test
            }
            $data['location_id'] = $loc ? $loc->id : 200; // default Lab Komputer jika ada

            // Kondisi
            $kondisi = strtolower($data['kondisi'] ?? 'baik');
            if (str_contains($kondisi, 'rusak')) {
                $data['condition'] = 'Rusak Ringan';
            } else {
                $data['condition'] = 'Baik';
            }

            $data['status'] = 'Aktif';
            $data['serial_number'] = null;
        }

        // Jika location_id diisi nama lokasi (bukan angka), otomatis cari ID lokasinya
        if (!empty($data['location_id']) && !is_numeric($data['location_id'])) {
            try {
                $loc = Location::where('name', $data['location_id'])
                    ->orWhere('name', 'like', $data['location_id'])
                    ->first();
                if ($loc) {
                    $data['location_id'] = $loc->id;
                }
            } catch (\Throwable $e) {
                // Abaikan jika tabel belum ada di environment test
            }
        }

        // Normalisasi kondisi jika huruf kecil (misal: 'baik' -> 'Baik')
        if (!empty($data['condition'])) {
            $cond = strtolower($data['condition']);
            if ($cond === 'baik') $data['condition'] = 'Baik';
            elseif ($cond === 'rusak ringan') $data['condition'] = 'Rusak Ringan';
            elseif ($cond === 'rusak berat') $data['condition'] = 'Rusak Berat';
        }

        // Normalisasi status jika huruf kecil (misal: 'aktif' -> 'Aktif')
        if (!empty($data['status'])) {
            $status = strtolower($data['status']);
            if ($status === 'aktif') $data['status'] = 'Aktif';
            elseif ($status === 'nonaktif') $data['status'] = 'Nonaktif';
        }

        return $data;
    }

    public function model(array $row)
    {
        // FIX: Cari asset_type_id berdasarkan nama atau code_prefix
        $assetType = AssetType::where('name', $row['jenis'])
            ->orWhere('code_prefix', $row['jenis'])
            ->first();
        
        // Jika tidak ditemukan, fallback ke Perangkat Lain
        if (!$assetType) {
            $assetType = AssetType::where('name', 'Perangkat Lain')->first()
                ?? AssetType::first();
        }

        // Cek apakah aset termasuk kategori non-elektronik / batch (Furniture, meja, kursi, lemari, karpet, dll.)
        $isBatchAsset = ($assetType->name === 'Furniture') || (
            isset($row['nama_barang']) && (
                str_contains(strtolower($row['nama_barang']), 'meja') ||
                str_contains(strtolower($row['nama_barang']), 'kursi') ||
                str_contains(strtolower($row['nama_barang']), 'almari') ||
                str_contains(strtolower($row['nama_barang']), 'lemari') ||
                str_contains(strtolower($row['nama_barang']), 'karpet') ||
                str_contains(strtolower($row['nama_barang']), 'gordyn') ||
                str_contains(strtolower($row['nama_barang']), 'gorden') ||
                str_contains(strtolower($row['nama_barang']), 'white board') ||
                str_contains(strtolower($row['nama_barang']), 'whiteboard') ||
                str_contains(strtolower($row['nama_barang']), 'kipas')
            )
        );

        $rawQty = isset($row['jumlah']) && is_numeric($row['jumlah']) ? (int) $row['jumlah'] : 1;

        if ($isBatchAsset) {
            // KHUSUS FURNITURE / MEJA / KURSI / SARANA:
            // Cukup dibuat 1 baris saja (1 QR code), jumlahnya dicatat di nama model & deskripsi
            $qty = 1;
            $unitNote = $rawQty > 1 ? " ({$rawQty} Unit)" : "";
            $modelName = $row['model'] . $unitNote;
            $descNote = ($row['description'] ?? '') . ($rawQty > 1 ? " | Total: {$rawQty} Unit" : "");
        } else {
            // PERANGKAT ELEKTRONIK / TI (Laptop, PC, Server, AC, Proyektor, Switch, CCTV, dll.):
            // Dibuat per unit sesuai Jumlah (misal 18 Laptop -> 18 kode unik & 18 QR code)
            $qty = max(1, min($rawQty, 200));
            $modelName = $row['model'];
            $descNote = $row['description'] ?? null;
        }

        $created = null;
        for ($i = 0; $i < $qty; $i++) {
            // Generate asset code otomatis berdasarkan jenis
            $assetCode = (isset($row['asset_code']) && !empty($row['asset_code']) && $qty === 1 && !$isBatchAsset)
                ? $row['asset_code'] 
                : Asset::generateAssetCode($assetType->id);
            
            $asset = Asset::create([
                'asset_code' => $assetCode,
                'asset_type_id' => $assetType->id,
                'kategori' => $assetType->kategori ?? 'Asset TI',
                'brand' => $row['brand'],
                'model' => $modelName,
                'serial_number' => (!empty($row['serial_number']) && $qty === 1) ? $row['serial_number'] : null,
                'description' => $descNote,
                'purchase_year' => $row['purchase_year'] ?? (int) date('Y'),
                'price' => $row['price'] ?? null,
                'condition' => $row['condition'],
                'status' => $row['status'] ?? 'Aktif',
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

            if ($created === null) {
                $created = $asset;
            }
        }

        return $created;
    }

    public function rules(): array
    {
        return [
            'asset_code' => 'nullable|unique:assets',
            'jenis' => [
                'required',
                'string',
                function ($attribute, $value, $fail) {
                    $exists = AssetType::where('name', $value)
                        ->orWhere('code_prefix', $value)
                        ->exists();
                    if (!$exists) {
                        $fail("Jenis aset '{$value}' belum terdaftar. Silakan tambahkan terlebih dahulu di menu Master Aset.");
                    }
                },
            ], 
            'brand' => 'required',
            'model' => 'required',
            'serial_number' => 'nullable|unique:assets',
            'description' => 'nullable|string|max:1000',
            'purchase_year' => 'required|integer',
            'price' => 'nullable|numeric|min:0',
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