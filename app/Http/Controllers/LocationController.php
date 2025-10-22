<?php

namespace App\Http\Controllers;

use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Imports\LocationsImport;
use Maatwebsite\Excel\Facades\Excel;

class LocationController extends Controller
{
    use AuthorizesRequests;

    /**
     * Tampilkan daftar lokasi
     */
    public function index(Request $request)
    {
        $search = $request->get('search');

        $locations = Location::query()
            ->withCount('assets')
            ->when($search, function ($query, $search) {
                return $query->where('name', 'like', "%{$search}%")
                            ->orWhere('floor', 'like', "%{$search}%")
                            ->orWhere('description', 'like', "%{$search}%");
            })
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('locations.index', compact('locations'));
    }

    /**
     * Detail lokasi
     */
    public function show(Location $location)
    {
        $location->load(['assets.assetType']);
        $statistics = $location->getDetailedStatistics();
        $assets = $location->assets()
            ->with(['assetType'])
            ->latest()
            ->paginate(10);

        return view('locations.show', compact('location', 'statistics', 'assets'));
    }

    /**
     * Form tambah lokasi
     */
    public function create()
    {
        $this->authorize('create', Location::class);
        return view('locations.create');
    }

    /**
     * Simpan lokasi baru
     */
    public function store(Request $request)
    {
        $this->authorize('create', Location::class);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'floor' => 'nullable|string|max:50',
            'description' => 'nullable|string',
        ]);

        Location::create($validated);

        return redirect()->route('locations.index')->with('success', 'Unit berhasil ditambahkan!');
    }

    /**
     * Form edit lokasi
     */
    public function edit(Location $location)
    {
        $this->authorize('update', $location);
        return view('locations.edit', compact('location'));
    }

    /**
     * Update lokasi
     */
    public function update(Request $request, Location $location)
    {
        $this->authorize('update', $location);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'floor' => 'nullable|string|max:50',
            'description' => 'nullable|string',
        ]);

        $location->update($validated);

        return redirect()->route('locations.index')->with('success', 'Lokasi berhasil diperbarui!');
    }

    /**
     * Hapus lokasi
     */
    public function destroy(Location $location)
    {
        $this->authorize('delete', $location);

        if ($location->assets()->count() > 0) {
            return redirect()->route('locations.index')->with('error', 'Lokasi tidak dapat dihapus karena masih memiliki aset!');
        }

        $location->delete();

        return redirect()->route('locations.index')->with('success', 'Lokasi berhasil dihapus!');
    }

    /**
     * Download template CSV untuk import lokasi
     */
    public function downloadTemplate()
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="template_import_lokasi.csv"',
        ];

        $columns = ['nama', 'kel', 'kode'];

        $callback = function() use ($columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            // Contoh data
            fputcsv($file, ['ADMIN DAN LEGAL', 'ADM', '174']);
            fputcsv($file, ['AL ARAF', 'PEL', '168']);
            fputcsv($file, ['EEG', 'POLY', '222']);

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Import lokasi dari Excel/CSV
     */
    public function import(Request $request)
    {
        $this->authorize('create', Location::class);

        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:2048',
        ]);

        try {
            Excel::import(new LocationsImport, $request->file('file'));

            return redirect()->route('locations.index')
                ->with('success', 'Data lokasi berhasil diimport!');
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
            $errors = [];

            foreach ($failures as $failure) {
                $errors[] = "Baris {$failure->row()}: " . implode(', ', $failure->errors());
            }

            return redirect()->route('locations.index')
                ->with('error', 'Import gagal: ' . implode(' | ', $errors));
        } catch (\Exception $e) {
            return redirect()->route('locations.index')
                ->with('error', 'Import gagal: ' . $e->getMessage());
        }
    }
}
