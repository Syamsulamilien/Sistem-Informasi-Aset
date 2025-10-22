<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Dashboard Overview</h1>
                    <p class="mt-1 text-sm text-gray-600">Sistem Pendataan dan Pemantauan Aset IT RSU PKU Muhammadiyah Bantul</p>
                </div>
                <div class="flex items-center gap-3">
                    <div class="flex items-center bg-blue-50 px-3 py-2 rounded-lg">
                        <svg class="w-4 h-4 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <span class="text-sm font-medium text-blue-700">{{ now()->format('d M Y') }}</span>
                    </div>
                    <a href="{{ route('assets.index') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-all duration-200 text-sm font-medium shadow-sm hover:shadow-md">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Kelola Aset
                    </a>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
            <!-- Total Aset -->
            <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-5 text-white relative overflow-hidden group hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                <div class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-white opacity-10 rounded-full"></div>
                <div class="absolute bottom-0 left-0 -mb-4 -ml-4 w-20 h-20 bg-white opacity-10 rounded-full"></div>
                <div class="relative">
                    <div class="flex items-center justify-between mb-3">
                        <div class="p-2.5 bg-white bg-opacity-20 rounded-lg group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                        </div>
                        <span class="text-xs font-medium bg-white bg-opacity-20 px-2.5 py-0.5 rounded-full">+12%</span>
                    </div>
                    <h3 class="text-xs font-medium opacity-90 mb-1">Total Aset</h3>
                    <p class="text-2xl font-bold">{{ $totalAssets }}</p>
                    <p class="text-xs opacity-75 mt-1">Seluruh aset terdaftar</p>
                </div>
            </div>

            <!-- Aset Aktif -->
            <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg p-5 text-white relative overflow-hidden group hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                <div class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-white opacity-10 rounded-full"></div>
                <div class="absolute bottom-0 left-0 -mb-4 -ml-4 w-20 h-20 bg-white opacity-10 rounded-full"></div>
                <div class="relative">
                    <div class="flex items-center justify-between mb-3">
                        <div class="p-2.5 bg-white bg-opacity-20 rounded-lg group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <span class="text-xs font-medium bg-white bg-opacity-20 px-2.5 py-0.5 rounded-full">{{ $totalAssets > 0 ? round(($activeAssets / $totalAssets) * 100) : 0 }}%</span>
                    </div>
                    <h3 class="text-xs font-medium opacity-90 mb-1">Aset Aktif</h3>
                    <p class="text-2xl font-bold">{{ $activeAssets }}</p>
                    <p class="text-xs opacity-75 mt-1">Sedang digunakan</p>
                </div>
            </div>

            <!-- Aset Nonaktif -->
            <div class="bg-gradient-to-br from-red-500 to-red-600 rounded-xl shadow-lg p-5 text-white relative overflow-hidden group hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                <div class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-white opacity-10 rounded-full"></div>
                <div class="absolute bottom-0 left-0 -mb-4 -ml-4 w-20 h-20 bg-white opacity-10 rounded-full"></div>
                <div class="relative">
                    <div class="flex items-center justify-between mb-3">
                        <div class="p-2.5 bg-white bg-opacity-20 rounded-lg group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <span class="text-xs font-medium bg-white bg-opacity-20 px-2.5 py-0.5 rounded-full">{{ $totalAssets > 0 ? round(($inactiveAssets / $totalAssets) * 100) : 0 }}%</span>
                    </div>
                    <h3 class="text-xs font-medium opacity-90 mb-1">Aset Nonaktif</h3>
                    <p class="text-2xl font-bold">{{ $inactiveAssets }}</p>
                    <p class="text-xs opacity-75 mt-1">Tidak digunakan</p>
                </div>
            </div>

            <!-- Garansi Berakhir -->
            <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl shadow-lg p-5 text-white relative overflow-hidden group hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                <div class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-white opacity-10 rounded-full"></div>
                <div class="absolute bottom-0 left-0 -mb-4 -ml-4 w-20 h-20 bg-white opacity-10 rounded-full"></div>
                <div class="relative">
                    <div class="flex items-center justify-between mb-3">
                        <div class="p-2.5 bg-white bg-opacity-20 rounded-lg group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 15.5c-.77.833.192 2.5 1.732 2.5z"></path>
                            </svg>
                        </div>
                        <span class="text-xs font-medium bg-white bg-opacity-20 px-2.5 py-0.5 rounded-full">Alert</span>
                    </div>
                    <h3 class="text-xs font-medium opacity-90 mb-1">Garansi Habis</h3>
                    <p class="text-2xl font-bold">{{ $expiringWarranties->count() }}</p>
                    <p class="text-xs opacity-75 mt-1">Dalam 30 hari</p>
                </div>
            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            <!-- Left Column: Charts and Lists (8 cols) -->
            <div class="lg:col-span-8 space-y-6">
                <!-- Asset Location Chart -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 transition-all duration-300 hover:shadow-md">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Aset per Lokasi</h3>
                            <p class="text-sm text-gray-600 mt-1">Distribusi aset berdasarkan ruangan</p>
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        <div style="min-width: 600px; height: 280px;">
                            <canvas id="assetLocationChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Bottom Row: Recent Assets & Warranties -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <!-- Recent Assets -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 transition-all duration-300 hover:shadow-md">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-900">Aset Terbaru</h3>
                            <a href="{{ route('assets.index') }}" class="text-sm text-blue-600 hover:text-blue-700 font-medium flex items-center">
                                Lihat Semua
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        </div>
                        <div class="space-y-3 overflow-y-auto" style="max-height: 300px;">
                            @forelse($recentAssets->take(6) as $asset)
                                <div class="flex items-center justify-between p-3 bg-gray-50 hover:bg-blue-50 rounded-lg transition-all duration-200 group cursor-pointer border border-transparent hover:border-blue-200">
                                    <div class="flex items-center gap-3 min-w-0 flex-1">
                                        <div class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center flex-shrink-0 group-hover:bg-blue-200 transition-colors">
                                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path>
                                            </svg>
                                        </div>
                                        <div class="min-w-0">
                                            <p class="text-sm font-semibold text-gray-900 truncate">{{ $asset->asset_code }}</p>
                                            <p class="text-xs text-gray-600 truncate">{{ $asset->brand }}</p>
                                        </div>
                                    </div>
                                    <span class="px-2.5 py-1 text-xs font-semibold rounded-full whitespace-nowrap ml-2 {{ $asset->status == 'Aktif' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $asset->status }}
                                    </span>
                                </div>
                            @empty
                                <div class="text-center py-8">
                                    <svg class="w-12 h-12 mx-auto text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7"></path>
                                    </svg>
                                    <p class="text-sm text-gray-500">Belum ada aset</p>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- Expiring Warranties -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 transition-all duration-300 hover:shadow-md">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-900">Garansi Berakhir</h3>
                            <span class="px-2.5 py-1 text-xs font-semibold bg-orange-100 text-orange-800 rounded-full">{{ $expiringWarranties->count() }}</span>
                        </div>
                        <div class="space-y-3 overflow-y-auto" style="max-height: 300px;">
                            @forelse($expiringWarranties->take(6) as $asset)
                                <div class="flex items-center justify-between p-3 bg-orange-50 hover:bg-orange-100 rounded-lg transition-all duration-200 border border-orange-200 group cursor-pointer">
                                    <div class="flex items-center gap-3 min-w-0 flex-1">
                                        <div class="w-10 h-10 rounded-lg bg-orange-100 flex items-center justify-center flex-shrink-0 group-hover:bg-orange-200 transition-colors">
                                            <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 15.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                            </svg>
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <p class="text-sm font-semibold text-gray-900 truncate">{{ $asset->asset_code }}</p>
                                            <p class="text-xs text-gray-600 truncate">{{ $asset->location->name }}</p>
                                        </div>
                                    </div>
                                    <div class="text-right flex-shrink-0 ml-2">
                                        <p class="text-sm font-semibold text-orange-800">{{ $asset->warranty_expiry_date->format('d M') }}</p>
                                        <p class="text-xs text-orange-600">{{ $asset->warranty_expiry_date->diffForHumans() }}</p>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-8">
                                    <svg class="w-12 h-12 mx-auto text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <p class="text-sm text-gray-500">Semua garansi aman</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Asset Type Chart (4 cols) -->
            <div class="lg:col-span-4">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 transition-all duration-300 hover:shadow-md" style="height: fit-content;">
                    <h3 class="text-lg font-semibold text-gray-900 mb-6">Distribusi Aset</h3>
                    <div style="height: 220px; margin-bottom: 24px;">
                        <canvas id="assetTypeChart"></canvas>
                    </div>
                    <div class="space-y-3 mt-4">
                        @php
                            $colors = [
                                'bg-blue-500', 'bg-green-500', 'bg-orange-500', 'bg-red-500',
                                'bg-purple-500', 'bg-pink-500', 'bg-indigo-500', 'bg-yellow-500',
                                'bg-teal-500', 'bg-cyan-500', 'bg-lime-500', 'bg-amber-500',
                                'bg-emerald-500', 'bg-rose-500', 'bg-violet-500', 'bg-fuchsia-500',
                                'bg-sky-500', 'bg-blue-600', 'bg-green-600', 'bg-red-600'
                            ];
                            $i = 0;
                        @endphp
                        @foreach($assetsByType as $type => $count)
                            <div class="flex items-center justify-between p-2 hover:bg-gray-50 rounded-lg transition-colors">
                                <div class="flex items-center gap-3">
                                    <div class="w-3 h-3 rounded-full {{ $colors[$i % count($colors)] }} flex-shrink-0"></div>
                                    <span class="text-sm text-gray-700">{{ $type }}</span>
                                </div>
                                <span class="text-sm font-semibold text-gray-900">{{ $count }}</span>
                            </div>
                            @php $i++; @endphp
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
        // Asset Type Doughnut Chart
        const typeCtx = document.getElementById('assetTypeChart').getContext('2d');
        
        // Generate colors untuk banyak jenis aset
        const chartColors = [
            '#3B82F6', '#10B981', '#F59E0B', '#EF4444',
            '#A855F7', '#EC4899', '#6366F1', '#EAB308',
            '#14B8A6', '#06B6D4', '#84CC16', '#F59E0B',
            '#10B981', '#F43F5E', '#8B5CF6', '#D946EF',
            '#0EA5E9', '#2563EB', '#059669', '#DC2626'
        ];
        
        const assetTypesCount = {!! json_encode($assetsByType->keys()) !!}.length;
        const selectedColors = chartColors.slice(0, assetTypesCount);
        
        new Chart(typeCtx, {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($assetsByType->keys()) !!},
                datasets: [{
                    data: {!! json_encode($assetsByType->values()) !!},
                    backgroundColor: selectedColors,
                    borderWidth: 0,
                    hoverOffset: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#1F2937',
                        padding: 10,
                        cornerRadius: 6,
                        titleFont: { size: 12 },
                        bodyFont: { size: 11 }
                    }
                },
                cutout: '70%'
            }
        });

        // Asset Location Bar Chart
        const locationCtx = document.getElementById('assetLocationChart').getContext('2d');
        new Chart(locationCtx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($assetsByLocation->pluck('name')) !!},
                datasets: [{
                    label: 'Jumlah Aset',
                    data: {!! json_encode($assetsByLocation->pluck('assets_count')) !!},
                    backgroundColor: '#3B82F6',
                    borderRadius: 6,
                    barThickness: 30
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#1F2937',
                        padding: 10,
                        cornerRadius: 6,
                        titleFont: { size: 12 },
                        bodyFont: { size: 11 }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { 
                            stepSize: 1, 
                            font: { size: 11 }, 
                            color: '#6B7280' 
                        },
                        grid: { color: '#F3F4F6', drawBorder: false }
                    },
                    x: {
                        ticks: { 
                            font: { size: 10 }, 
                            color: '#6B7280',
                            maxRotation: 45,
                            minRotation: 45,
                            autoSkip: false
                        },
                        grid: { display: false }
                    }
                }
            }
        });
    </script>
</x-app-layout>