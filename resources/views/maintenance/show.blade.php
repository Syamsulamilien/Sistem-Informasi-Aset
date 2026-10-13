<x-app-layout :title="'Detail Pemeliharaan ' . ($maintenance->asset->asset_code ?? '')">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <!-- Header -->
        <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center gap-3">
                <a href="{{ route('maintenance.index') }}" class="p-2 text-gray-500 hover:text-gray-800 hover:bg-gray-100 rounded-xl border border-gray-200 transition-colors shadow-sm" title="Kembali">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                </a>
                <div>
                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 tracking-tight">Detail Maintenance</h1>
                    <p class="text-xs sm:text-sm text-gray-500 mt-1">Aset: {{ $maintenance->asset->asset_code ?? '-' }} • {{ $maintenance->asset->brand ?? '' }}</p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('maintenance.edit', $maintenance) }}" class="inline-flex items-center justify-center px-4 py-2.5 bg-yellow-600 hover:bg-yellow-700 text-white rounded-xl text-sm font-semibold shadow-sm transition-all active:scale-[0.98]">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Edit
                </a>
                <a href="{{ route('maintenance.index') }}" class="inline-flex items-center justify-center px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-sm font-semibold transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Kembali
                </a>
            </div>
        </div>
            
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

                <form id="deleteMaintenanceForm" action="{{ route('maintenance.destroy', $maintenance) }}" method="POST" class="w-full md:w-auto">
                    @csrf
                    @method('DELETE')
                    <button type="button" onclick="confirmDeleteMaintenance()" class="w-full md:w-auto bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition font-medium">
                        🗑️ Hapus Record
                    </button>
                </form>
            </div>
        </div>

    <script>
        function confirmDeleteMaintenance() {
            Swal.fire({
                title: 'Konfirmasi Hapus Maintenance',
                text: 'Apakah Anda yakin ingin menghapus record maintenance ini? Tindakan ini tidak dapat dibatalkan.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Hapus',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#e11d48',
                cancelButtonColor: '#94a3b8',
                reverseButtons: true,
                customClass: {
                    popup: 'rounded-3xl shadow-2xl border border-gray-100 font-sans p-6',
                    title: 'text-lg font-black text-gray-900',
                    confirmButton: 'rounded-xl font-bold px-5 py-2.5 shadow-md shadow-rose-500/20 text-sm',
                    cancelButton: 'rounded-xl font-semibold px-5 py-2.5 text-sm'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('deleteMaintenanceForm').submit();
                }
            });
        }
    </script>
</x-app-layout>