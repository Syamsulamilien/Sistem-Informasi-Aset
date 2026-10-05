<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Maintenance Record Details') }}
            </h2>
            <div class="flex gap-2">
                <a href="{{ route('maintenance.edit', $maintenance) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                    Edit
                </a>
                <a href="{{ route('maintenance.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-lg">
                    ← Back
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Status Badge -->
            <div class="mb-6">
                @if ($maintenance->status == 'Scheduled')
                    <span class="px-4 py-2 inline-flex text-sm leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                        📅 Scheduled
                    </span>
                @elseif ($maintenance->status == 'Completed')
                    <span class="px-4 py-2 inline-flex text-sm leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                        ✅ Completed
                    </span>
                @else
                    <span class="px-4 py-2 inline-flex text-sm leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                        ❌ Cancelled
                    </span>
                @endif

                @if ($maintenance->status == 'Scheduled' && $maintenance->schedule_date->isPast())
                    <span class="ml-2 px-4 py-2 inline-flex text-sm leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                        ⚠️ Overdue
                    </span>
                @endif
            </div>

            <!-- Main Info Card -->
            <div class="bg-white overflow-hidden shadow-sm rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Maintenance Information</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Asset Info -->
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Asset</label>
                            <div class="text-base text-gray-900">
                                <a href="{{ route('assets.show', $maintenance->asset) }}" class="text-blue-600 hover:underline font-medium">
                                    {{ $maintenance->asset->asset_code }}
                                </a>
                            </div>
                            <div class="text-sm text-gray-600">
                                {{ $maintenance->asset->brand }} {{ $maintenance->asset->model }}
                            </div>
                            <div class="text-xs text-gray-500">{{ $maintenance->asset->assetType->name ?? 'N/A' }}</div>
                        </div>

                        <!-- ✅ PERBAIKAN: Technician dengan null check -->
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Technician</label>
                            <div class="text-base text-gray-900">
                                @if($maintenance->technician_id && $maintenance->technician)
                                    {{ $maintenance->technician->name }}
                                    <div class="text-sm text-gray-500">{{ $maintenance->technician->email }}</div>
                                @elseif($maintenance->technician_name)
                                    {{ $maintenance->technician_name }}
                                    <div class="text-sm text-gray-500 italic">(Teknisi Manual)</div>
                                @else
                                    <span class="text-gray-400 italic">Belum ditentukan</span>
                                @endif
                            </div>
                        </div>

                        <!-- Schedule Date -->
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Schedule Date</label>
                            <div class="text-base text-gray-900">{{ $maintenance->schedule_date->format('d F Y') }}</div>
                            <div class="text-sm text-gray-500">{{ $maintenance->schedule_date->diffForHumans() }}</div>
                        </div>

                        <!-- Performed Date -->
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Performed Date</label>
                            <div class="text-base text-gray-900">
                                @if ($maintenance->performed_date)
                                    {{ $maintenance->performed_date->format('d F Y') }}
                                    <div class="text-sm text-gray-500">{{ $maintenance->performed_date->diffForHumans() }}</div>
                                @else
                                    <span class="text-gray-400">Not yet performed</span>
                                @endif
                            </div>
                        </div>

                        <!-- Tanggal Penerimaan Barang -->
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Tanggal Penerimaan Barang</label>
                            <div class="text-base text-gray-900">
                                @if ($maintenance->tanggal_penerimaan_barang)
                                    <div class="flex items-center">
                                        <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <div>
                                            {{ $maintenance->tanggal_penerimaan_barang->format('d F Y') }}
                                            <div class="text-sm text-gray-500">{{ $maintenance->tanggal_penerimaan_barang->diffForHumans() }}</div>
                                        </div>
                                    </div>
                                @else
                                    <span class="text-gray-400">Belum diterima</span>
                                @endif
                            </div>
                        </div>

                        <!-- Cost -->
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Maintenance Cost</label>
                            <div class="text-xl font-bold text-gray-900">
                                Rp {{ number_format($maintenance->cost, 0, ',', '.') }}
                            </div>
                        </div>

                        <!-- Status -->
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Status</label>
                            <div class="text-base text-gray-900">{{ $maintenance->status }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Notes Card -->
            @if ($maintenance->notes)
            <div class="bg-white overflow-hidden shadow-sm rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Maintenance Notes</h3>
                    <div class="text-gray-700 whitespace-pre-wrap">{{ $maintenance->notes }}</div>
                </div>
            </div>
            @endif

            <!-- Asset Details Card -->
            <div class="bg-white overflow-hidden shadow-sm rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Asset Details</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Asset Type</label>
                            <div class="text-base text-gray-900">{{ $maintenance->asset->assetType->name ?? '-' }}</div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Location</label>
                            <div class="text-base text-gray-900">{{ $maintenance->asset->location->name ?? '-' }}</div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Condition</label>
                            <div class="text-base text-gray-900">
                                @if ($maintenance->asset->condition == 'Baik')
                                    <span class="text-green-600">✓ Baik</span>
                                @elseif ($maintenance->asset->condition == 'Rusak Ringan')
                                    <span class="text-yellow-600">⚠ Rusak Ringan</span>
                                @else
                                    <span class="text-red-600">✗ Rusak Berat</span>
                                @endif
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Purchase Year</label>
                            <div class="text-base text-gray-900">{{ $maintenance->asset->purchase_year ?? '-' }}</div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <a href="{{ route('maintenance.asset-history', $maintenance->asset_id) }}" 
                           class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                            View Full Maintenance History for this Asset →
                        </a>
                    </div>
                </div>
            </div>

            <!-- Timestamps -->
            <div class="bg-gray-50 overflow-hidden shadow-sm rounded-lg">
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-600">
                        <div>
                            <span class="font-medium">Created:</span> 
                            {{ $maintenance->created_at->format('d F Y, H:i') }}
                        </div>
                        <div>
                            <span class="font-medium">Last Updated:</span> 
                            {{ $maintenance->updated_at->format('d F Y, H:i') }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="mt-6 flex flex-col md:flex-row justify-between items-stretch md:items-center gap-3">
                <div class="flex flex-col sm:flex-row gap-2 w-full md:w-auto">
                    @if ($maintenance->status == 'Scheduled')
                        <form action="{{ route('maintenance.complete', $maintenance) }}" method="POST" class="w-full sm:w-auto">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="w-full sm:w-auto bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition">
                                ✓ Mark as Completed</button>
                        </form>
                    @endif
                    
                    <a href="{{ route('maintenance.index') }}" class="w-full sm:w-auto text-center bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition">
                        ← Back to Maintenance List
                    </a>
                </div>

                <form action="{{ route('maintenance.destroy', $maintenance) }}" method="POST" class="w-full md:w-auto" onsubmit="return confirm('Are you sure you want to delete this maintenance record?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full md:w-auto bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition">
                        🗑️ Delete Record
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>