<x-app-layout :title="'Riwayat Pemeliharaan ' . $asset->asset_code">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <!-- Header -->
        <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center gap-3">
                <a href="{{ route('maintenance.index') }}" class="p-2 text-gray-500 hover:text-gray-800 hover:bg-gray-100 rounded-xl border border-gray-200 transition-colors shadow-sm" title="Kembali">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                </a>
                <div>
                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 tracking-tight">Riwayat Maintenance</h1>
                    <p class="text-xs sm:text-sm text-gray-500 mt-1">Aset: {{ $asset->asset_code }} • {{ $asset->brand }} {{ $asset->model }}</p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('assets.show', $asset) }}" class="inline-flex items-center justify-center px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-semibold shadow-sm transition-all active:scale-[0.98]">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                    Lihat Aset
                </a>
                <a href="{{ route('maintenance.index') }}" class="inline-flex items-center justify-center px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-sm font-semibold transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Semua Maintenance
                </a>
            </div>
        </div>
            
            <!-- Asset Summary Card -->
            <div class="bg-white overflow-hidden shadow-sm rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Asset Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Asset Code</label>
                            <div class="text-base text-gray-900 font-mono">{{ $asset->asset_code }}</div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Type</label>
                            <div class="text-base text-gray-900">{{ $asset->assetType->name ?? '-' }}</div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Location</label>
                            <div class="text-base text-gray-900">{{ $asset->location->name ?? '-' }}</div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Condition</label>
                            <div class="text-base text-gray-900">
                                @if ($asset->condition == 'Good')
                                    <span class="text-green-600">✓ Good</span>
                                @elseif ($asset->condition == 'Fair')
                                    <span class="text-yellow-600">⚠ Fair</span>
                                @else
                                    <span class="text-red-600">✗ Poor</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistics -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-white overflow-hidden shadow-sm rounded-lg p-6">
                    <div class="text-gray-500 text-sm">Total Maintenance</div>
                    <div class="text-2xl font-bold text-blue-600">{{ $maintenances->total() }}</div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm rounded-lg p-6">
                    <div class="text-gray-500 text-sm">Completed</div>
                    <div class="text-2xl font-bold text-green-600">
                        {{ $maintenances->where('status', 'Completed')->count() }}
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm rounded-lg p-6">
                    <div class="text-gray-500 text-sm">Scheduled</div>
                    <div class="text-2xl font-bold text-yellow-600">
                        {{ $maintenances->where('status', 'Scheduled')->count() }}
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm rounded-lg p-6">
                    <div class="text-gray-500 text-sm">Total Cost</div>
                    <div class="text-2xl font-bold text-purple-600">
                        Rp {{ number_format($maintenances->sum('cost'), 0, ',', '.') }}
                    </div>
                </div>
            </div>

            <!-- Maintenance Timeline -->
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">Maintenance Timeline</h3>
                        <a href="{{ route('maintenance.create') }}?asset_id={{ $asset->id }}" 
                           class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm">
                            + Add Maintenance
                        </a>
                    </div>

                    <div class="space-y-4">
                        @forelse ($maintenances as $maintenance)
                            <div class="border-l-4 {{ $maintenance->status == 'Completed' ? 'border-green-500' : ($maintenance->status == 'Scheduled' ? 'border-yellow-500' : 'border-red-500') }} pl-4 py-3 hover:bg-gray-50 rounded-r">
                                <div class="flex justify-between items-start">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-2 mb-2">
                                            @if ($maintenance->status == 'Scheduled')
                                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                    Scheduled
                                                </span>
                                            @elseif ($maintenance->status == 'Completed')
                                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                                    Completed
                                                </span>
                                            @else
                                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                                    Cancelled
                                                </span>
                                            @endif
                                            <span class="text-sm text-gray-500">
                                                {{ $maintenance->schedule_date->format('d M Y') }}
                                            </span>
                                            @if ($maintenance->performed_date)
                                                <span class="text-sm text-gray-400">
                                                    → Performed: {{ $maintenance->performed_date->format('d M Y') }}
                                                </span>
                                            @endif
                                        </div>

                                        <div class="text-sm text-gray-700 mb-1">
                                            <span class="font-medium">Technician:</span> {{ $maintenance->technician->name }}
                                        </div>

                                        <div class="text-sm text-gray-700 mb-1">
                                            <span class="font-medium">Cost:</span> Rp {{ number_format($maintenance->cost, 0, ',', '.') }}
                                        </div>

                                        @if ($maintenance->notes)
                                            <div class="text-sm text-gray-600 mt-2 bg-gray-50 p-2 rounded">
                                                <span class="font-medium">Notes:</span> {{ Str::limit($maintenance->notes, 150) }}
                                            </div>
                                        @endif
                                    </div>

                                    <div class="flex gap-2 ml-4">
                                        <a href="{{ route('maintenance.show', $maintenance) }}" 
                                           class="text-blue-600 hover:text-blue-900 text-sm">
                                            View
                                        </a>
                                        <a href="{{ route('maintenance.edit', $maintenance) }}" 
                                           class="text-indigo-600 hover:text-indigo-900 text-sm">
                                            Edit
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-8">
                                <div class="text-gray-400 mb-2">
                                    <svg class="w-12 h-12 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                    </svg>
                                </div>
                                <p class="text-gray-500 font-medium">No maintenance history found</p>
                                <p class="text-gray-400 text-sm">This asset has no maintenance records yet.</p>
                                <a href="{{ route('maintenance.create') }}?asset_id={{ $asset->id }}" 
                                   class="mt-4 inline-block bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm">
                                    Add First Maintenance Record
                                </a>
                            </div>
                        @endforelse
                    </div>

                    <div class="mt-6">
                        {{ $maintenances->links() }}
                    </div>
                </div>
            </div>
        </div>
</x-app-layout>