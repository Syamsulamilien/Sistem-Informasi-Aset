<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\Location;
use App\Models\AssetType;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AssetsExport;
use App\Imports\AssetsImport;

class ImportExportController extends Controller
{
    public function exportExcel(Request $request)
    {
        $query = Asset::with('location', 'assetType');
        
        // Apply filters
        if ($request->filled('asset_type_id')) {
            $query->where('asset_type_id', $request->asset_type_id);
        }
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('location_id')) {
            $query->where('location_id', $request->location_id);
        }
        
        if ($request->filled('condition')) {
            $query->where('condition', $request->condition);
        }
        
        return Excel::download(new AssetsExport($query), 'aset-' . date('Y-m-d') . '.xlsx');
    }

    public function importExcel(Request $request)
    {
        $this->authorize('create', Asset::class);
        
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:5120',
        ]);
        
        try {
            Excel::import(new AssetsImport, $request->file('file'));
            
            return redirect()->route('assets.index')
                ->with('success', 'Data berhasil diimport!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Import gagal: ' . $e->getMessage());
        }
    }

    public function downloadTemplate()
    {
        $headers = [
            'asset_code',
            'jenis',
            'brand',
            'model',
            'serial_number',
            'description', // ← UBAH dari 'ram_gb'
            'purchase_year',
            'price',
            'condition',
            'status',
            'location_id',
            'warranty_expiry_date'
        ];
        
        // Get sample data
        $firstAssetType = AssetType::active()->first();
        $firstLocation = Location::first();
        
        $exampleData = [
            '',
            $firstAssetType->name ?? 'PC Desktop',
            'Dell',
            'Optiplex 7090',
            'SN123456789',
            'RAM 16GB, Storage 512GB SSD, Processor Intel i7 Gen 11', // ← UBAH contoh
            '2024',
            '15000000',
            'Baik',
            'Aktif',
            $firstLocation->id ?? '1',
            '2026-12-31'
        ];
        
        // Daftar jenis yang valid
        $assetTypesList = AssetType::active()
            ->orderBy('name')
            ->get()
            ->map(function($type) {
                return $type->name . ' (' . $type->code_prefix . ')';
            })
            ->implode(', ');
        
        $callback = function() use ($headers, $exampleData, $assetTypesList) {
            $file = fopen('php://output', 'w');
            
            // Header
            fputcsv($file, $headers);
            
            // Comment: Daftar jenis yang valid
            fputcsv($file, ['# JENIS YANG VALID: ' . $assetTypesList]);
            fputcsv($file, ['# Gunakan nama lengkap atau prefix (contoh: PC Desktop atau PC)']);
            fputcsv($file, ['# Kosongkan asset_code untuk auto-generate']);
            fputcsv($file, ['# Description: Tambahkan spesifikasi atau catatan (opsional)']);
            fputcsv($file, ['']);
            
            // Example data
            fputcsv($file, $exampleData);
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="template-import-aset.csv"',
        ]);
    }
}