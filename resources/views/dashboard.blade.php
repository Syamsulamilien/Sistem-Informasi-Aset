<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">

        {{-- ===== Top bar: sapaan user ===== --}}
        <div class="flex justify-end pt-12 lg:pt-0 mb-2">
            <div class="flex items-center gap-3">
                <div class="text-right leading-tight">
                    <p class="text-sm text-gray-700">Hallo, <span class="font-bold text-gray-900">{{ Auth::user()->name }}</span></p>
                    <p class="text-[10px] text-gray-500 capitalize">{{ Auth::user()->role }}</p>
                </div>
                <div class="w-9 h-9 rounded-full bg-gradient-to-br from-pink-400 to-orange-300 flex items-center justify-center text-white text-sm font-bold">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
            </div>
        </div>

        {{-- ===== Judul ===== --}}
        <div class="mb-6">
            <h1 class="text-3xl font-extrabold text-gray-900">Dashboard Overview</h1>
            <p class="text-[11px] text-gray-500 mt-1">Sistem Pendataan dan Pencarian Aset SMK Kesehatan Bosatama</p>
        </div>

        {{-- ===== Baris 1: kartu statistik ===== --}}
        @php
            $stats = [
                ['label' => 'Total Asset',    'value' => $totalAssets,     'tint' => 'bg-blue-50 text-blue-600',
                 'icon'  => 'M21 7.5l-9-5.25L3 7.5m18 0l-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9'],
                ['label' => 'Asset Aktif',    'value' => $activeAssets,    'tint' => 'bg-green-50 text-green-600',
                 'icon'  => 'M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
                ['label' => 'Asset Nonaktif', 'value' => $inactiveAssets,  'tint' => 'bg-gray-100 text-gray-500',
                 'icon'  => 'M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z'],
                ['label' => 'Garansi Habis',  'value' => $expiredWarranty, 'tint' => 'bg-orange-50 text-orange-500',
                 'icon'  => 'M12 9v3.75m0-10.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.75c0 5.592 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285zm0 13.036h.008v.008H12v-.008z'],
                ['label' => 'Dipinjam',       'value' => $borrowedCount,   'tint' => 'bg-purple-50 text-purple-600',
                 'icon'  => 'M7.5 21L3 16.5m0 0L7.5 12M3 16.5h13.5m0-13.5L21 7.5m0 0L16.5 12M21 7.5H7.5'],
            ];
        @endphp
        <div class="grid grid-cols-2 lg:grid-cols-5 gap-4">
            @foreach($stats as $stat)
                <div class="flex items-center gap-4 bg-white rounded-xl border border-gray-100 shadow-sm p-4 {{ $loop->last ? 'col-span-2 lg:col-span-1' : '' }}">
                    <span class="flex-none w-11 h-11 rounded-xl flex items-center justify-center {{ $stat['tint'] }}">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="{{ $stat['icon'] }}"/>
                        </svg>
                    </span>
                    <div class="min-w-0">
                        <p class="text-xs font-medium text-gray-500 truncate">{{ $stat['label'] }}</p>
                        <p class="text-2xl font-extrabold text-gray-900 tabular-nums leading-tight">{{ $stat['value'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- ===== Baris 2: kiri (stack) + kanan (donut) ===== --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mt-4">

            {{-- KIRI --}}
            <div class="space-y-4">
                {{-- Aset Terbaru --}}
                <div class="bg-white rounded-xl shadow-sm p-5">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-sm font-bold text-gray-900">Aset Terbaru</h3>
                        <a href="{{ route('assets.index') }}" class="text-[11px] font-semibold text-blue-600 hover:underline">Lihat Semua &gt;</a>
                    </div>
                    <div class="space-y-2">
                        @forelse($recentAssets as $asset)
                            <a href="{{ route('assets.show', $asset) }}" class="flex items-center justify-between bg-gray-100 hover:bg-gray-200 transition-colors rounded-lg px-3 py-2">
                                <div class="flex items-center gap-3">
                                    <span class="w-7 h-7 rounded-full bg-blue-100 flex items-center justify-center">
                                        <span class="w-3 h-3 rounded-full border-2 border-blue-500"></span>
                                    </span>
                                    <div class="leading-tight">
                                        <p class="text-xs font-bold text-gray-900">{{ $asset->asset_code }}</p>
                                        <p class="text-[10px] text-gray-500">{{ $asset->brand }} {{ $asset->model }}</p>
                                    </div>
                                </div>
                                <span class="px-2 py-0.5 text-[10px] font-semibold rounded {{ $asset->status === 'Aktif' ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600' }}">
                                    {{ $asset->status }}
                                </span>
                            </a>
                        @empty
                            <p class="text-xs text-gray-400 text-center py-4">Belum ada aset</p>
                        @endforelse
                    </div>
                </div>

                {{-- Garansi Berakhir --}}
                <div class="bg-white rounded-xl shadow-sm p-5">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-sm font-bold text-gray-900">Garansi Berakhir</h3>
                        <span class="w-5 h-5 rounded-full bg-yellow-100 text-yellow-700 text-[10px] font-bold flex items-center justify-center">
                            {{ $expiringAssets->count() }}
                        </span>
                    </div>
                    <div class="space-y-2">
                        @forelse($expiringAssets as $asset)
                            <div class="flex items-center justify-between bg-orange-50 border border-orange-100 rounded-lg px-3 py-2.5">
                                <div class="flex items-center gap-3">
                                    <span class="w-8 h-8 rounded-lg bg-orange-100 text-orange-500 flex items-center justify-center">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                    </span>
                                    <div class="leading-tight">
                                        <p class="text-xs font-bold text-gray-900">{{ $asset->asset_code }}</p>
                                        <p class="text-[10px] text-gray-500">{{ $asset->location->name ?? '-' }}</p>
                                    </div>
                                </div>
                                <div class="text-right leading-tight">
                                    <p class="text-xs font-bold text-orange-500">{{ $asset->warranty_expiry_date->format('d M') }}</p>
                                    <p class="text-[10px] text-gray-500">{{ $asset->warranty_expiry_date->diffForHumans() }}</p>
                                </div>
                            </div>
                        @empty
                            <p class="text-xs text-gray-400 text-center py-4">Tidak ada garansi yang akan berakhir</p>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- KANAN: Barang Dipinjam (donut) --}}
            @php $chartColors = ['#3B82F6', '#22C55E', '#F59E0B', '#EF4444', '#8B5CF6']; @endphp
            <div class="bg-white rounded-xl shadow-sm p-5 flex flex-col">
                <div class="flex items-center justify-between mb-2">
                    <h3 class="text-sm font-bold text-gray-900">Barang Dipinjam</h3>
                    <a href="{{ route('borrowings.index') }}" class="text-[11px] font-semibold text-blue-600 hover:underline">Lihat Semua &gt;</a>
                </div>

                @if($borrowedByType->count() > 0)
                    <div class="flex-1 flex items-center justify-center py-4">
                        <div class="relative w-52 h-52">
                            <canvas id="borrowedChart"></canvas>
                        </div>
                    </div>
                    <div class="space-y-2">
                        @foreach($borrowedByType as $i => $row)
                            <div class="flex items-center justify-between text-xs">
                                <div class="flex items-center gap-2">
                                    <span class="w-2.5 h-2.5 rounded-full" style="background: {{ $chartColors[$i % count($chartColors)] }}"></span>
                                    <span class="text-gray-700">{{ $row->label }}</span>
                                </div>
                                <span class="font-semibold text-gray-900">{{ $row->total }}</span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="flex-1 flex items-center justify-center">
                        <p class="text-xs text-gray-400">Belum ada barang yang dipinjam</p>
                    </div>
                @endif
            </div>
        </div>

        {{-- ===== Stok Barang Habis Pakai ===== --}}
        <div class="bg-white rounded-xl shadow-sm p-5 mt-4">
            <div class="flex flex-wrap items-start justify-between gap-3 mb-4">
                <div>
                    <h3 class="text-sm font-bold text-gray-900">Stok Barang Habis Pakai</h3>
                    <p class="text-[11px] text-gray-500">Lima barang dengan stok paling sedikit, perlu diisi ulang lebih dulu</p>
                </div>
                <a href="{{ route('consumables.index') }}" class="text-[11px] font-semibold text-blue-600 hover:underline">Lihat Semua &gt;</a>
            </div>

            {{-- Ringkasan --}}
            <div class="grid grid-cols-3 gap-3 mb-5">
                <div class="rounded-lg border border-gray-200 bg-gray-50 px-4 py-3">
                    <p class="text-xs text-gray-500">Total barang</p>
                    <p class="mt-0.5 text-2xl font-extrabold text-gray-900 tabular-nums">{{ $totalConsumables }}</p>
                </div>
                <div class="rounded-lg border border-yellow-200 bg-yellow-50 px-4 py-3">
                    <p class="flex items-center gap-1.5 text-xs text-yellow-800">
                        <span class="w-1.5 h-1.5 rounded-full bg-yellow-500"></span> Menipis
                    </p>
                    <p class="mt-0.5 text-2xl font-extrabold text-yellow-800 tabular-nums">{{ $lowCount }}</p>
                </div>
                <div class="rounded-lg border border-red-200 bg-red-50 px-4 py-3">
                    <p class="flex items-center gap-1.5 text-xs text-red-700">
                        <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span> Habis
                    </p>
                    <p class="mt-0.5 text-2xl font-extrabold text-red-700 tabular-nums">{{ $emptyCount }}</p>
                </div>
            </div>

            {{-- Daftar --}}
            <div class="overflow-x-auto">
                <table class="min-w-full text-left">
                    <thead>
                        <tr class="text-xs font-semibold text-gray-500 border-b border-gray-200">
                            <th scope="col" class="pb-2 pr-4">Barang</th>
                            <th scope="col" class="pb-2 pr-4 hidden sm:table-cell">Lokasi</th>
                            <th scope="col" class="pb-2 pr-4">Stok</th>
                            <th scope="col" class="pb-2 text-right">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($consumables as $item)
                            @php
                                if ($item->stock <= 0) {
                                    [$chip, $dot, $label] = ['bg-red-50 text-red-700 ring-red-600/20', 'bg-red-500', 'Habis'];
                                } elseif ($item->stock <= $lowStockLimit) {
                                    [$chip, $dot, $label] = ['bg-yellow-50 text-yellow-800 ring-yellow-600/20', 'bg-yellow-500', 'Menipis'];
                                } else {
                                    [$chip, $dot, $label] = ['bg-green-50 text-green-700 ring-green-600/20', 'bg-green-500', 'Aman'];
                                }
                                $pct = $item->stock <= 0 ? 0 : min(100, round($item->stock / max($lowStockLimit, 1) * 100));
                            @endphp
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="py-3 pr-4">
                                    <div class="flex items-center gap-3">
                                        @if($item->photo)
                                            <img src="{{ asset('storage/' . $item->photo) }}" alt="" class="w-9 h-9 flex-none rounded-lg object-cover ring-1 ring-gray-200">
                                        @else
                                            <span class="w-9 h-9 flex-none rounded-lg bg-gray-100 ring-1 ring-gray-200 text-gray-400 flex items-center justify-center">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                                            </span>
                                        @endif
                                        <div class="min-w-0">
                                            <a href="{{ route('consumables.show', $item) }}" class="block text-sm font-semibold text-gray-900 hover:text-blue-600 truncate">{{ $item->name }}</a>
                                            <p class="text-xs text-gray-500 truncate">{{ $item->kategori ?? 'Umum' }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3 pr-4 hidden sm:table-cell text-sm text-gray-700 whitespace-nowrap">
                                    {{ $item->location->name ?? '-' }}
                                </td>
                                <td class="py-3 pr-4">
                                    <div class="flex items-center gap-3">
                                        <span class="min-w-[4.5rem] text-sm font-semibold text-gray-900 tabular-nums whitespace-nowrap">
                                            {{ $item->stock }} <span class="font-normal text-gray-500">{{ $item->unit }}</span>
                                        </span>
                                        <div class="hidden md:block w-24 h-1.5 rounded-full bg-gray-100 overflow-hidden" aria-hidden="true">
                                            <div class="h-full rounded-full {{ $dot }}" style="width: {{ $pct }}%"></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3 text-right whitespace-nowrap">
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-semibold rounded-full ring-1 ring-inset {{ $chip }}">
                                        <span class="w-1.5 h-1.5 rounded-full {{ $dot }}"></span>
                                        {{ $label }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-8 text-center text-xs text-gray-400">Belum ada barang habis pakai</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- ===== Aset per Lokasi (bar chart) ===== --}}
        <div class="bg-white rounded-xl shadow-sm p-5 mt-4">
            <h3 class="text-sm font-bold text-gray-900">Aset per Lokasi</h3>
            <p class="text-[11px] text-gray-500 mb-3">Distribusi aset berdasarkan ruangan</p>
            <div class="relative h-56">
                <canvas id="locationChart"></canvas>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            const colors = @json($chartColors);

            // Donut: Barang Dipinjam
            const borrowedEl = document.getElementById('borrowedChart');
            if (borrowedEl) {
                new Chart(borrowedEl, {
                    type: 'doughnut',
                    data: {
                        labels: @json($borrowedByType->pluck('label')),
                        datasets: [{
                            data: @json($borrowedByType->pluck('total')),
                            backgroundColor: colors,
                            borderWidth: 0
                        }]
                    },
                    options: {
                        cutout: '62%',
                        plugins: { legend: { display: false } }
                    }
                });
            }

            // Bar: Aset per Lokasi
            new Chart(document.getElementById('locationChart'), {
                type: 'bar',
                data: {
                    labels: @json($locationLabels),
                    datasets: [{
                        data: @json($locationTotals),
                        backgroundColor: '#3B82F6',
                        borderRadius: 3,
                        maxBarThickness: 56
                    }]
                },
                options: {
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { precision: 0, font: { size: 10 } },
                            grid: { color: '#F1F5F9' }
                        },
                        x: {
                            grid: { display: false },
                            ticks: { font: { size: 10 }, maxRotation: 45, minRotation: 45 }
                        }
                    }
                }
            });
        </script>
    @endpush
</x-app-layout>