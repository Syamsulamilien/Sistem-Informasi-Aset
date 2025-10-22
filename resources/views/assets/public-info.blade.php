<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="theme-color" content="#3B82F6">
    <title>Info Aset - {{ $asset->asset_code }}</title>
    @vite(['resources/css/app.css'])
    <style>
        /* Tambahan CSS untuk fix mobile */
        body {
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            touch-action: manipulation;
        }
        
        /* Pastikan icon SVG tidak terlalu besar */
        svg {
            flex-shrink: 0;
        }
        
        /* Fix untuk gambar */
        img {
            max-width: 100%;
            height: auto;
        }

        /* Smooth scroll */
        html {
            scroll-behavior: smooth;
        }

        /* Prevent horizontal scroll */
        body {
            overflow-x: hidden;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-blue-50 to-indigo-100 min-h-screen">
    <div class="min-h-screen px-3 py-4 sm:px-6 lg:px-8">
        <div class="max-w-2xl mx-auto">
            <!-- Header -->
            <div class="bg-white rounded-t-xl sm:rounded-t-2xl shadow-lg p-4 sm:p-6 text-center border-b-4 border-blue-600">
                <div class="w-14 h-14 sm:w-20 sm:h-20 bg-blue-600 rounded-full mx-auto mb-3 sm:mb-4 flex items-center justify-center">
                    <svg class="w-7 h-7 sm:w-10 sm:h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path>
                    </svg>
                </div>
                <h1 class="text-base sm:text-2xl font-bold text-gray-800 leading-tight">RSU PKU Muhammadiyah Bantul</h1>
                <p class="text-xs sm:text-base text-gray-600 mt-1">Sistem Informasi Aset IT</p>
            </div>

            <!-- Asset Info Card -->
            <div class="bg-white shadow-lg">
                <div class="p-3 sm:p-6">
                    <div class="mb-3 sm:mb-6">
                        <div class="flex items-center justify-between mb-3 sm:mb-4 flex-wrap gap-2">
                            <h2 class="text-base sm:text-xl font-bold text-gray-800">Informasi Aset</h2>
                            @php
                                $statusClasses = $asset->status == 'Aktif' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800';
                            @endphp
                            <span class="px-2.5 py-1 sm:px-4 sm:py-2 rounded-full text-xs sm:text-sm font-semibold {{ $statusClasses }}">
                                {{ $asset->status }}
                            </span>
                        </div>
                        
                        @if($asset->photo)
                        <div class="mb-3 sm:mb-4">
                            <img src="{{ asset('storage/' . $asset->photo) }}" alt="Foto Aset" class="w-full h-36 sm:h-48 object-cover rounded-lg border-2 border-gray-200">
                        </div>
                        @endif
                    </div>

                    <!-- Detail Informasi -->
                    <div class="space-y-2 sm:space-y-3">
                        <!-- Kode Aset -->
                        <div class="flex items-start p-2.5 sm:p-4 bg-gray-50 rounded-lg">
                            <div class="flex-shrink-0 w-8 h-8 sm:w-10 sm:h-10 bg-blue-100 rounded-full flex items-center justify-center mr-2 sm:mr-3">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"></path>
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs sm:text-sm text-gray-600">Kode Aset</p>
                                <p class="text-sm sm:text-lg font-bold text-gray-900 break-all">{{ $asset->asset_code }}</p>
                            </div>
                        </div>

                        <!-- Jenis Perangkat -->
                        <div class="flex items-start p-2.5 sm:p-4 bg-gray-50 rounded-lg">
                            <div class="flex-shrink-0 w-8 h-8 sm:w-10 sm:h-10 bg-purple-100 rounded-full flex items-center justify-center mr-2 sm:mr-3">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs sm:text-sm text-gray-600">Jenis Perangkat</p>
                                <p class="text-sm sm:text-lg font-semibold text-gray-900">{{ $asset->assetType->name }}</p>
                            </div>
                        </div>

                        <!-- Merek & Model -->
                        <div class="flex items-start p-2.5 sm:p-4 bg-gray-50 rounded-lg">
                            <div class="flex-shrink-0 w-8 h-8 sm:w-10 sm:h-10 bg-green-100 rounded-full flex items-center justify-center mr-2 sm:mr-3">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs sm:text-sm text-gray-600">Merek & Model</p>
                                <p class="text-sm sm:text-lg font-semibold text-gray-900 break-words">{{ $asset->brand }} {{ $asset->model }}</p>
                                <p class="text-xs text-gray-500 mt-0.5 sm:mt-1 break-all">S/N: {{ $asset->serial_number }}</p>
                            </div>
                        </div>

                        @if($asset->ram_gb)
                        <!-- RAM -->
                        <div class="flex items-start p-2.5 sm:p-4 bg-gray-50 rounded-lg">
                            <div class="flex-shrink-0 w-8 h-8 sm:w-10 sm:h-10 bg-yellow-100 rounded-full flex items-center justify-center mr-2 sm:mr-3">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path>
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs sm:text-sm text-gray-600">RAM</p>
                                <p class="text-sm sm:text-lg font-semibold text-gray-900">{{ $asset->ram_gb }} GB</p>
                            </div>
                        </div>
                        @endif

                        <!-- Lokasi -->
                        <div class="flex items-start p-2.5 sm:p-4 bg-gray-50 rounded-lg">
                            <div class="flex-shrink-0 w-8 h-8 sm:w-10 sm:h-10 bg-red-100 rounded-full flex items-center justify-center mr-2 sm:mr-3">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs sm:text-sm text-gray-600">Lokasi Saat Ini</p>
                                <p class="text-sm sm:text-lg font-semibold text-gray-900">{{ $asset->location->name }}</p>
                                <p class="text-xs text-gray-500 mt-0.5 sm:mt-1">{{ $asset->location->floor }} - {{ $asset->location->unit }}</p>
                            </div>
                        </div>

                        <!-- Tahun & Kondisi -->
                        <div class="grid grid-cols-2 gap-2 sm:gap-3">
                            <div class="p-2.5 sm:p-4 bg-gray-50 rounded-lg text-center">
                                <p class="text-xs text-gray-600 mb-1">Tahun Beli</p>
                                <p class="text-base sm:text-xl font-bold text-gray-900">{{ $asset->purchase_year }}</p>
                            </div>
                            <div class="p-2.5 sm:p-4 bg-gray-50 rounded-lg text-center">
                                <p class="text-xs text-gray-600 mb-1">Kondisi</p>
                                @php
                                    $conditionClasses = match($asset->condition) {
                                        'Baik' => 'bg-green-100 text-green-800',
                                        'Rusak Ringan' => 'bg-yellow-100 text-yellow-800',
                                        default => 'bg-red-100 text-red-800'
                                    };
                                @endphp
                                <span class="inline-block px-2 py-1 rounded-full text-xs font-semibold {{ $conditionClasses }}">
                                    {{ $asset->condition }}
                                </span>
                            </div>
                        </div>

                        @if($asset->warranty_expiry_date)
                        <!-- Garansi -->
                        @php
                            $warrantyExpired = $asset->warranty_expiry_date->isPast();
                            $warrantyBgClass = $warrantyExpired ? 'bg-red-50 border-red-200' : 'bg-blue-50 border-blue-200';
                            $warrantyIconClass = $warrantyExpired ? 'text-red-600' : 'text-blue-600';
                            $warrantyTextClass = $warrantyExpired ? 'text-red-800' : 'text-blue-800';
                            $warrantyDateClass = $warrantyExpired ? 'text-red-600' : 'text-blue-600';
                        @endphp
                        <div class="p-2.5 sm:p-4 {{ $warrantyBgClass }} border-2 rounded-lg">
                            <div class="flex items-center">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5 {{ $warrantyIconClass }} mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                </svg>
                                <div class="flex-1 min-w-0">
                                    <p class="text-xs sm:text-sm {{ $warrantyTextClass }} font-medium">
                                        Garansi {{ $warrantyExpired ? 'Telah Berakhir' : 'Berakhir' }}
                                    </p>
                                    <p class="text-xs {{ $warrantyDateClass }}">
                                        {{ $asset->warranty_expiry_date->format('d F Y') }}
                                        @if(!$warrantyExpired)
                                            ({{ $asset->warranty_expiry_date->diffForHumans() }})
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>

                    <!-- Riwayat Singkat -->
                    @if($asset->histories->count() > 0)
                    <div class="mt-4 sm:mt-6">
                        <h3 class="text-sm sm:text-lg font-semibold text-gray-800 mb-2 sm:mb-3 flex items-center">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Riwayat Terakhir
                        </h3>
                        <div class="space-y-2">
                            @foreach($asset->histories->take(3) as $history)
                            <div class="flex items-start p-2 sm:p-3 bg-gray-50 rounded-lg border-l-4 border-blue-500">
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-2 mb-1 flex-wrap">
                                        <span class="px-2 py-0.5 text-xs font-semibold rounded bg-blue-100 text-blue-800">
                                            {{ $history->action }}
                                        </span>
                                        <span class="text-xs text-gray-500">
                                            {{ $history->created_at->diffForHumans() }}
                                        </span>
                                    </div>
                                    @if($history->notes)
                                    <p class="text-xs sm:text-sm text-gray-600 break-words">{{ $history->notes }}</p>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Footer -->
            <div class="bg-white rounded-b-xl sm:rounded-b-2xl shadow-lg p-3 sm:p-6 text-center">
                <p class="text-xs sm:text-sm text-gray-600 mb-2 sm:mb-3">
                    Informasi terakhir diperbarui: {{ $asset->updated_at->format('d F Y H:i') }}
                </p>
                <div class="flex items-center justify-center text-xs text-gray-500">
                    <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 mr-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                    Data ini bersifat publik dan hanya untuk informasi
                </div>
            </div>

            <!-- Scan Again Button -->
            <div class="mt-3 sm:mt-6 text-center pb-4 sm:pb-6">
                <button onclick="window.location.reload()" class="w-full sm:w-auto px-5 py-2.5 sm:px-6 sm:py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 shadow-lg text-sm sm:text-base font-medium active:bg-blue-800 transition-colors">
                    🔄 Refresh Informasi
                </button>
            </div>
        </div>
    </div>
</body>
</html>