<?php

namespace App\Http\Controllers;

use App\Models\MaintenanceRecord;
use App\Models\Asset;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MaintenanceController extends Controller
{
    /**
     * Display a listing of maintenance records.
     */
    public function index(Request $request)
    {
        $query = MaintenanceRecord::with(['asset.assetType', 'technician']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by asset
        if ($request->filled('asset_id')) {
            $query->where('asset_id', $request->asset_id);
        }

        // Filter by technician - BARU DITAMBAHKAN
        if ($request->filled('technician_id')) {
            $query->where('technician_id', $request->technician_id);
        }

        // Filter by date range
        if ($request->filled('start_date')) {
            $query->whereDate('schedule_date', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('schedule_date', '<=', $request->end_date);
        }

        // Search - Asset code, brand, or model
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('asset', function($q) use ($search) {
                $q->where('asset_code', 'like', "%{$search}%")
                  ->orWhere('brand', 'like', "%{$search}%")
                  ->orWhere('model', 'like', "%{$search}%");
            });
        }

        $maintenances = $query->latest('schedule_date')->paginate(15)->withQueryString();
        
        // Format assets untuk dropdown dengan label yang lebih informatif
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

        return view('maintenance.index', compact('maintenances', 'assets', 'technicians'));
    }

    /**
     * Show the form for creating a new maintenance record.
     */
    public function create()
    {
        // Format assets untuk dropdown
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

    /**
     * Store a newly created maintenance record.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'asset_id' => 'required|exists:assets,id',
            'schedule_date' => 'required|date',
            'performed_date' => 'nullable|date',
            'technician_id' => 'required|exists:users,id',
            'notes' => 'nullable|string',
            'cost' => 'nullable|numeric|min:0',
            'status' => 'required|in:Scheduled,Completed,Cancelled',
        ]);

        $validated['cost'] = $validated['cost'] ?? 0;

        MaintenanceRecord::create($validated);

        return redirect()->route('maintenance.index')
            ->with('success', 'Maintenance record created successfully.');
    }

    /**
     * Display the specified maintenance record.
     */
    public function show(MaintenanceRecord $maintenance)
    {
        $maintenance->load(['asset.assetType', 'technician']);
        
        return view('maintenance.show', compact('maintenance'));
    }

    /**
     * Show the form for editing the specified maintenance record.
     */
    public function edit(MaintenanceRecord $maintenance)
    {
        // Format assets untuk dropdown
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

    /**
     * Update the specified maintenance record.
     */
    public function update(Request $request, MaintenanceRecord $maintenance)
    {
        $validated = $request->validate([
            'asset_id' => 'required|exists:assets,id',
            'schedule_date' => 'required|date',
            'performed_date' => 'nullable|date',
            'technician_id' => 'required|exists:users,id',
            'notes' => 'nullable|string',
            'cost' => 'nullable|numeric|min:0',
            'status' => 'required|in:Scheduled,Completed,Cancelled',
        ]);

        $validated['cost'] = $validated['cost'] ?? 0;

        $maintenance->update($validated);

        return redirect()->route('maintenance.index')
            ->with('success', 'Maintenance record updated successfully.');
    }

    /**
     * Remove the specified maintenance record.
     */
    public function destroy(MaintenanceRecord $maintenance)
    {
        $maintenance->delete();

        return redirect()->route('maintenance.index')
            ->with('success', 'Maintenance record deleted successfully.');
    }

    /**
     * Get upcoming maintenance schedules
     */
    public function upcoming()
    {
        $upcomingMaintenances = MaintenanceRecord::with(['asset.assetType', 'technician'])
            ->where('status', 'Scheduled')
            ->whereDate('schedule_date', '>=', now())
            ->orderBy('schedule_date')
            ->paginate(15);

        return view('maintenance.upcoming', compact('upcomingMaintenances'));
    }

    /**
     * Get overdue maintenance schedules
     */
    public function overdue()
    {
        $overdueMaintenances = MaintenanceRecord::with(['asset.assetType', 'technician'])
            ->where('status', 'Scheduled')
            ->whereDate('schedule_date', '<', now())
            ->orderBy('schedule_date', 'desc')
            ->paginate(15);

        return view('maintenance.overdue', compact('overdueMaintenances'));
    }

    /**
     * Mark maintenance as completed
     */
    public function complete(MaintenanceRecord $maintenance)
    {
        $maintenance->update([
            'status' => 'Completed',
            'performed_date' => now(),
        ]);

        return redirect()->back()
            ->with('success', 'Maintenance marked as completed.');
    }

    /**
     * Get maintenance history for a specific asset
     */
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