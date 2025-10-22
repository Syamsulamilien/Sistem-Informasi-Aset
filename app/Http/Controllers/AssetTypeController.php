<?php

namespace App\Http\Controllers;

use App\Models\AssetType;
use Illuminate\Http\Request;

class AssetTypeController extends Controller
{
    public function index()
    {
        $assetTypes = AssetType::withCount('assets')->latest()->paginate(15);
        return view('asset-types.index', compact('assetTypes'));
    }

    public function create()
    {
        return view('asset-types.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:asset_types',
            'code_prefix' => 'required|string|max:10|unique:asset_types|regex:/^[A-Z0-9]+$/',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ], [
            'code_prefix.regex' => 'Prefix hanya boleh huruf kapital dan angka tanpa spasi',
        ]);

        AssetType::create($validated);

        return redirect()->route('asset-types.index')
            ->with('success', 'Jenis aset berhasil ditambahkan!');
    }

    public function edit(AssetType $assetType)
    {
        return view('asset-types.edit', compact('assetType'));
    }

    public function update(Request $request, AssetType $assetType)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:asset_types,name,' . $assetType->id,
            'code_prefix' => 'required|string|max:10|unique:asset_types,code_prefix,' . $assetType->id . '|regex:/^[A-Z0-9]+$/',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $assetType->update($validated);

        return redirect()->route('asset-types.index')
            ->with('success', 'Jenis aset berhasil diperbarui!');
    }

    public function destroy(AssetType $assetType)
    {
        if ($assetType->assets()->count() > 0) {
            return redirect()->route('asset-types.index')
                ->with('error', 'Jenis aset tidak dapat dihapus karena masih digunakan oleh ' . $assetType->assets()->count() . ' aset!');
        }

        $assetType->delete();

        return redirect()->route('asset-types.index')
            ->with('success', 'Jenis aset berhasil dihapus!');
    }
}