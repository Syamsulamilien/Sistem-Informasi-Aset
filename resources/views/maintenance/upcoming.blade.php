<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Jadwal Maintenance Mendatang') }}
            </h2>
            <a href="{{ route('maintenance.index') }}" class="w-full sm:w-auto bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-lg inline-flex items-center justify-center transition">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Kembali ke Semua Maintenance
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Info Banner -->
            <div class="mb-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
                <div class="flex items-start">
                    <svg class="w-6 h-6 text-blue-600 mr-3 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <div>
                        <h3 class="text-blue-900 font-medium">Jadwal Maintenance yang Akan Datang</h3>
                        <p class="text-blue-700 text-sm mt-1">Berikut adalah daftar maintenance yang dijadwalkan dalam waktu dekat atau sudah jatuh tempo.</p>
                        <p class="text-blue-600 text-xs mt-2 font-medium">🔄 Halaman ini diperbarui secara otomatis setiap 30 detik</p>
                    </div>
                </div>
            </div>

            <!-- Desktop View -->
            <div class="hidden lg:block bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aset</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Terjadwal</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu Tersisa</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Teknisi</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Biaya</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($upcomingMaintenances as $maintenance)
                                    @php
                                        $daysUntil = now()->diffInDays($maintenance->schedule_date, false);
                                        $isUrgent = $daysUntil <= 7 && $daysUntil >= 0;
                                        $isOverdue = $daysUntil < 0;
                                    @endphp
                                    <tr class="hover:bg-gray-50 {{ $isOverdue ? 'bg-red-50' : ($isUrgent ? 'bg-yellow-50' : '') }}" data-schedule-date="{{ $maintenance->schedule_date->toIso8601String() }}" data-row-id="{{ $maintenance->id }}">
                                        <td class="px-6 py-4">
                                            <div class="font-medium text-gray-900">{{ $maintenance->asset->asset_code }}</div>
                                            <div class="text-sm text-gray-500">{{ $maintenance->asset->brand }} {{ $maintenance->asset->model }}</div>
                                            <div class="text-xs text-gray-400">{{ $maintenance->asset->assetType->name ?? 'N/A' }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $maintenance->schedule_date->format('d M Y') }}
                                            <div class="text-xs text-gray-500">{{ $maintenance->schedule_date->format('H:i') }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm countdown-cell" data-schedule-date="{{ $maintenance->schedule_date->toIso8601String() }}">
                                            @if ($daysUntil == 0)
                                                <span class="text-orange-600 font-semibold">Hari Ini!</span>
                                            @elseif ($daysUntil == 1)
                                                <span class="text-orange-600 font-semibold">Besok</span>
                                            @elseif ($daysUntil < 0)
                                                <span class="text-red-600 font-semibold">Terlambat {{ abs($daysUntil) }} hari</span>
                                            @elseif ($daysUntil <= 7)
                                                <span class="text-yellow-600 font-semibold">{{ $daysUntil }} hari lagi</span>
                                            @else
                                                <span class="text-gray-600">{{ $daysUntil }} hari lagi</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $maintenance->technician->name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            Rp {{ number_format($maintenance->cost, 0, ',', '.') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex gap-2">
                                                <a href="{{ route('maintenance.show', $maintenance) }}" class="text-blue-600 hover:text-blue-900">Lihat</a>
                                                <a href="{{ route('maintenance.edit', $maintenance) }}" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                                <button type="button" onclick="openCompleteModal({{ $maintenance->id }}, '{{ $maintenance->asset->asset_code }}')" class="text-green-600 hover:text-green-900">Selesai</button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-12 text-center">
                                            <div class="text-gray-400 mb-2">
                                                <svg class="w-16 h-16 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                            </div>
                                            <p class="text-gray-500 font-medium text-lg">Tidak Ada Jadwal Maintenance Mendatang</p>
                                            <p class="text-gray-400 text-sm mt-2">Semua maintenance sudah terjadwal dengan baik!</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $upcomingMaintenances->links() }}
                    </div>
                </div>
            </div>

            <!-- Mobile View -->
            <div class="lg:hidden space-y-4">
                @forelse ($upcomingMaintenances as $maintenance)
                    @php
                        $daysUntil = now()->diffInDays($maintenance->schedule_date, false);
                        $isUrgent = $daysUntil <= 7 && $daysUntil >= 0;
                        $isOverdue = $daysUntil < 0;
                    @endphp
                    <div class="bg-white rounded-xl shadow-sm border {{ $isOverdue ? 'border-red-300 bg-red-50' : ($isUrgent ? 'border-yellow-300 bg-yellow-50' : 'border-gray-200') }} p-4" data-schedule-date="{{ $maintenance->schedule_date->toIso8601String() }}" data-row-id="{{ $maintenance->id }}">
                        <!-- Header -->
                        <div class="flex justify-between items-start mb-3">
                            <div>
                                <p class="text-sm font-bold text-gray-900">{{ $maintenance->asset->asset_code }}</p>
                                <p class="text-xs text-gray-500">{{ $maintenance->asset->brand }} {{ $maintenance->asset->model }}</p>
                                <p class="text-xs text-gray-400 mt-1">{{ $maintenance->asset->assetType->name ?? 'N/A' }}</p>
                            </div>
                            <div class="text-right">
                                <div class="countdown-cell text-sm font-semibold" data-schedule-date="{{ $maintenance->schedule_date->toIso8601String() }}">
                                    @if ($daysUntil == 0)
                                        <span class="text-orange-600">Hari Ini!</span>
                                    @elseif ($daysUntil == 1)
                                        <span class="text-orange-600">Besok</span>
                                    @elseif ($daysUntil < 0)
                                        <span class="text-red-600">Terlambat {{ abs($daysUntil) }} hari</span>
                                    @elseif ($daysUntil <= 7)
                                        <span class="text-yellow-600">{{ $daysUntil }} hari</span>
                                    @else
                                        <span class="text-gray-600">{{ $daysUntil }} hari</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Details -->
                        <div class="space-y-2 mb-3">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Tanggal Terjadwal:</span>
                                <span class="font-medium text-gray-900">{{ $maintenance->schedule_date->format('d M Y H:i') }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Teknisi:</span>
                                <span class="font-medium text-gray-900">{{ $maintenance->technician->name }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Biaya:</span>
                                <span class="font-semibold text-green-700">Rp {{ number_format($maintenance->cost, 0, ',', '.') }}</span>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="flex flex-col xs:flex-row gap-2 pt-3 border-t border-gray-200">
                            <a href="{{ route('maintenance.show', $maintenance) }}" class="flex-1 text-center px-3 py-2 text-sm font-medium text-blue-600 bg-blue-50 rounded-lg hover:bg-blue-100 transition">
                                Lihat
                            </a>
                            <a href="{{ route('maintenance.edit', $maintenance) }}" class="flex-1 text-center px-3 py-2 text-sm font-medium text-indigo-600 bg-indigo-50 rounded-lg hover:bg-indigo-100 transition">
                                Edit
                            </a>
                            <button onclick="openCompleteModal({{ $maintenance->id }}, '{{ $maintenance->asset->asset_code }}')" class="flex-1 px-3 py-2 text-sm font-medium text-green-600 bg-green-50 rounded-lg hover:bg-green-100 transition">
                                Selesai
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
                        <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <p class="text-gray-500 font-medium">Tidak Ada Jadwal Maintenance Mendatang</p>
                        <p class="text-gray-400 text-sm mt-2">Semua maintenance sudah terjadwal dengan baik!</p>
                    </div>
                @endforelse

                <!-- Pagination for Mobile -->
                <div class="mt-6">
                    {{ $upcomingMaintenances->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Complete Confirmation Modal -->
    <div id="completeModal" style="display: none;" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-xl shadow-xl max-w-md w-full">
            <div class="p-6">
                <div class="flex items-center justify-center w-12 h-12 mx-auto bg-green-100 rounded-full mb-4">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 text-center mb-2">Tandai Selesai</h3>
                <p class="text-sm text-gray-600 text-center mb-6">
                    Tandai maintenance untuk aset <strong id="completeAssetCode"></strong> sebagai selesai? Tanggal pelaksanaan akan diatur ke hari ini.
                </p>
                <form id="completeForm" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="flex flex-col sm:flex-row gap-3">
                        <button type="button" onclick="closeCompleteModal()" class="flex-1 px-4 py-2.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors font-medium">
                            Batal
                        </button>
                        <button type="submit" class="flex-1 px-4 py-2.5 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-medium">
                            Ya, Selesai
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Complete Modal Functions
        function openCompleteModal(id, assetCode) {
            document.getElementById('completeAssetCode').textContent = assetCode;
            document.getElementById('completeForm').action = `/maintenance/${id}/complete`;
            document.getElementById('completeModal').style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }

        function closeCompleteModal() {
            document.getElementById('completeModal').style.display = 'none';
            document.body.style.overflow = '';
        }

        // Close modal when clicking outside
        document.getElementById('completeModal').addEventListener('click', function(e) {
            if (e.target === this) closeCompleteModal();
        });

        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeCompleteModal();
            }
        });

        // Real-time countdown update
        function updateCountdowns() {
            const now = new Date();
            const countdownCells = document.querySelectorAll('.countdown-cell');
            
            countdownCells.forEach(cell => {
                const scheduleDate = new Date(cell.getAttribute('data-schedule-date'));
                const diffTime = scheduleDate - now;
                const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
                
                let html = '';
                let colorClass = '';
                
                if (diffDays < 0) {
                    html = `Terlambat ${Math.abs(diffDays)} hari`;
                    colorClass = 'text-red-600 font-semibold';
                } else if (diffDays === 0) {
                    html = 'Hari Ini!';
                    colorClass = 'text-orange-600 font-semibold';
                } else if (diffDays === 1) {
                    html = 'Besok';
                    colorClass = 'text-orange-600 font-semibold';
                } else if (diffDays <= 7) {
                    html = `${diffDays} hari lagi`;
                    colorClass = 'text-yellow-600 font-semibold';
                } else {
                    html = `${diffDays} hari lagi`;
                    colorClass = 'text-gray-600';
                }
                
                cell.innerHTML = `<span class="${colorClass}">${html}</span>`;
                
                // Update row background color
                const row = cell.closest('tr');
                if (row) {
                    row.classList.remove('bg-red-50', 'bg-yellow-50');
                    if (diffDays < 0) {
                        row.classList.add('bg-red-50');
                    } else if (diffDays <= 7 && diffDays >= 0) {
                        row.classList.add('bg-yellow-50');
                    }
                }
                
                // Update card background for mobile
                const card = cell.closest('[data-row-id]');
                if (card && card.tagName === 'DIV') {
                    card.classList.remove('border-red-300', 'bg-red-50', 'border-yellow-300', 'bg-yellow-50', 'border-gray-200');
                    if (diffDays < 0) {
                        card.classList.add('border-red-300', 'bg-red-50');
                    } else if (diffDays <= 7 && diffDays >= 0) {
                        card.classList.add('border-yellow-300', 'bg-yellow-50');
                    } else {
                        card.classList.add('border-gray-200');
                    }
                }
            });
        }

        // Update every second for real-time feel
        setInterval(updateCountdowns, 1000);
        
        // Initial update
        updateCountdowns();

        // Auto-refresh page every 30 seconds to get new data
        setInterval(function() {
            location.reload();
        }, 30000);
    </script>
</x-app-layout>