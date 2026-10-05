<?php

namespace App\Http\Controllers;

use App\Models\MaintenanceRecord;
use App\Models\Asset;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MaintenanceController extends Controller
{
    public function index(Request $request)
    {
        $query = MaintenanceRecord::with(['asset.assetType', 'technician']);
        $statsQuery = MaintenanceRecord::query();

        // Filter Status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
            $statsQuery->where('status', $request->status);
        }

        // Filter Asset
        if ($request->filled('asset_id')) {
            $query->where('asset_id', $request->asset_id);
            $statsQuery->where('asset_id', $request->asset_id);
        }

        // Filter Technician
        if ($request->filled('technician_id')) {
            $query->where('technician_id', $request->technician_id);
            $statsQuery->where('technician_id', $request->technician_id);
        }

        // Filter Tanggal Mulai
        if ($request->filled('start_date')) {
            $query->whereDate('schedule_date', '>=', $request->start_date);
            $statsQuery->whereDate('schedule_date', '>=', $request->start_date);
        }

        // Filter Tanggal Akhir
        if ($request->filled('end_date')) {
            $query->whereDate('schedule_date', '<=', $request->end_date);
            $statsQuery->whereDate('schedule_date', '<=', $request->end_date);
        }

        // Filter Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('asset', function($q) use ($search) {
                $q->where('asset_code', 'like', "%{$search}%")
                  ->orWhere('brand', 'like', "%{$search}%")
                  ->orWhere('model', 'like', "%{$search}%");
            });
            $statsQuery->whereHas('asset', function($q) use ($search) {
                $q->where('asset_code', 'like', "%{$search}%")
                  ->orWhere('brand', 'like', "%{$search}%")
                  ->orWhere('model', 'like', "%{$search}%");
            });
        }

        // Hitung statistik berdasarkan filter
        $stats = [
            'scheduled' => (clone $statsQuery)->where('status', 'Scheduled')->count(),
            'completed' => (clone $statsQuery)->where('status', 'Completed')->count(),
            'overdue' => (clone $statsQuery)->where('status', 'Scheduled')
                ->whereDate('schedule_date', '<', now())
                ->count(),
            'cost_this_month' => (clone $statsQuery)
                ->whereMonth('performed_date', now()->month)
                ->whereYear('performed_date', now()->year)
                ->sum('cost'),
        ];

        // Ambil data untuk tabel
        $maintenances = $query->latest('schedule_date')->paginate(15)->withQueryString();
        
        // Data untuk dropdown
        $assets = Asset::with('assetType')
            ->orderBy('asset_code')
            ->get()
            ->map(function($asset) {
                return (object)[
                    'id' => $asset->id,
                    'label' => $asset->asset_code . ' - ' . $asset->brand . ' ' . $asset->model
                ];
            });
        
        $technicians = User::orderBy('name')->get();

        return view('maintenance.index', compact('maintenances', 'assets', 'technicians', 'stats'));
    }

    public function create()
    {
        $assets = Asset::with('assetType')
            ->orderBy('asset_code')
            ->get()
            ->map(function($asset) {
                return (object)[
                    'id' => $asset->id,
                    'label' => $asset->asset_code . ' - ' . $asset->brand . ' ' . $asset->model
                ];
            });
        
        $technicians = User::orderBy('name')->get();
        
        return view('maintenance.create', compact('assets', 'technicians'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'asset_id' => 'required|exists:assets,id',
            'schedule_date' => 'required|date',
            'performed_date' => 'nullable|date',
            'tanggal_penerimaan_barang' => 'nullable|date',
            'technician_id' => 'nullable|exists:users,id',
            'technician_name' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'cost' => 'nullable|numeric|min:0',
            'status' => 'required|in:Scheduled,Proses,Completed,Cancelled',
        ]);

        $validated['cost'] = $validated['cost'] ?? 0;

        MaintenanceRecord::create($validated);

        return redirect()->route('maintenance.index')
            ->with('success', 'Maintenance record created successfully.');
    }

    public function show(MaintenanceRecord $maintenance)
    {
        $maintenance->load(['asset.assetType', 'technician']);
        
        return view('maintenance.show', compact('maintenance'));
    }

    public function edit(MaintenanceRecord $maintenance)
    {
        $assets = Asset::with('assetType')
            ->orderBy('asset_code')
            ->get()
            ->map(function($asset) {
                return (object)[
                    'id' => $asset->id,
                    'label' => $asset->asset_code . ' - ' . $asset->brand . ' ' . $asset->model
                ];
            });
        
        $technicians = User::orderBy('name')->get();
        
        return view('maintenance.edit', compact('maintenance', 'assets', 'technicians'));
    }

    public function update(Request $request, MaintenanceRecord $maintenance)
    {
        $validated = $request->validate([
            'asset_id' => 'required|exists:assets,id',
            'schedule_date' => 'required|date',
            'performed_date' => 'nullable|date',
            'tanggal_penerimaan_barang' => 'nullable|date',
            'technician_id' => 'nullable|exists:users,id',
            'technician_name' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'cost' => 'nullable|numeric|min:0',
            'status' => 'required|in:Scheduled,Proses,Completed,Cancelled', 
        ]);

        $validated['cost'] = $validated['cost'] ?? 0;

        $maintenance->update($validated);

        return redirect()->route('maintenance.index')
            ->with('success', 'Maintenance record updated successfully.');
    }

    public function destroy(MaintenanceRecord $maintenance)
    {
        $maintenance->delete();

        return redirect()->route('maintenance.index')
            ->with('success', 'Maintenance record deleted successfully.');
    }

   public function upcoming()
{
    $year = request('year'); // Ambil filter tahun dari URL

    $upcomingMaintenances = MaintenanceRecord::with(['asset.assetType', 'technician'])
        ->where('status', 'Scheduled')
        ->when($year, function ($query) use ($year) {
            $query->whereYear('schedule_date', $year);
        })
        ->whereDate('schedule_date', '>=', now())
        ->orderBy('schedule_date')
        ->paginate(15)
        ->withQueryString(); // biar pagination mempertahankan filter

    return view('maintenance.upcoming', compact('upcomingMaintenances'));
}


    public function overdue()
    {
        $overdueMaintenances = MaintenanceRecord::with(['asset.assetType', 'technician'])
            ->where('status', 'Scheduled')
            ->whereDate('schedule_date', '<', now())
            ->orderBy('schedule_date', 'desc')
            ->paginate(15);

        return view('maintenance.overdue', compact('overdueMaintenances'));
    }

    public function markInProgress(MaintenanceRecord $maintenance)
    {
        $maintenance->update([
            'status' => 'Proses',
            'performed_date' => $maintenance->performed_date ?? now(),
        ]);

        return redirect()->back()
            ->with('success', 'Maintenance marked as in progress.');
    }

    public function complete(MaintenanceRecord $maintenance)
    {
        $maintenance->update([
            'status' => 'Completed',
            'performed_date' => $maintenance->performed_date ?? now(),
        ]);

        return redirect()->back()
            ->with('success', 'Maintenance marked as completed.');
    }

    public function assetHistory($assetId)
    {
        $asset = Asset::findOrFail($assetId);
        $maintenances = MaintenanceRecord::with(['technician'])
            ->where('asset_id', $assetId)
            ->orderBy('schedule_date', 'desc')
            ->paginate(15);

        return view('maintenance.asset-history', compact('asset', 'maintenances'));
    }
}