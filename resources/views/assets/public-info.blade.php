<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="theme-color" content="#3B82F6">
    <title>Info Aset - {{ $asset->asset_code }}</title>
    @vite(['resources/css/app.css'])
    <style>
        body {
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            touch-action: manipulation;
        }
        svg { flex-shrink: 0; }
        img { max-width: 100%; height: auto; }
        html { scroll-behavior: smooth; }
        body { overflow-x: hidden; }
    </style>
</head>
<body class="bg-gradient-to-br from-blue-50 to-indigo-100 min-h-screen">
    <div class="min-h-screen px-3 py-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto">
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
                    <!-- Header dengan Status -->
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
                            <img src="{{ asset('storage/' . $asset->photo) }}" alt="Foto Aset" class="w-full h-36 sm:h-64 object-cover rounded-lg border-2 border-gray-200">
                        </div>
                        @endif
                    </div>

                    <!-- Detail Informasi Grid -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
                        
                        <!-- Kode Aset -->
                        <div class="sm:col-span-2 p-3 sm:p-4 bg-gradient-to-br from-blue-50 to-blue-100 rounded-lg border-2 border-blue-200">
                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <p class="text-xs text-blue-700 mb-1 font-semibold">Kode Aset</p>
                                    <p class="font-bold text-blue-900 text-base sm:text-xl break-all">{{ $asset->asset_code }}</p>
                                </div>
                                <div class="p-2 bg-blue-200 rounded-full">
                                    <svg class="w-5 h-5 sm:w-6 sm:h-6 text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <!-- Jenis Aset -->
                        <div class="p-3 sm:p-4 bg-gradient-to-br from-purple-50 to-purple-100 rounded-lg border-2 border-purple-200">
                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <p class="text-xs text-purple-700 mb-1 font-semibold">Jenis Aset</p>
                                    <p class="font-bold text-purple-900 text-sm sm:text-base">{{ $asset->assetType->name ?? 'N/A' }}</p>
                                </div>
                                <div class="p-2 bg-purple-200 rounded-full">
                                    <svg class="w-5 h-5 text-purple-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <!-- Kategori Asset -->
                        @if($asset->kategori)
                        <div class="p-3 sm:p-4 bg-gradient-to-br from-pink-50 to-pink-100 rounded-lg border-2 border-pink-200">
                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <p class="text-xs text-pink-700 mb-1 font-semibold">Kategori</p>
                                    <p class="font-bold text-pink-900 text-sm sm:text-base">{{ $asset->kategori }}</p>
                                </div>
                                <div class="p-2 bg-pink-200 rounded-full">
                                    <svg class="w-5 h-5 text-pink-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- Merek & Model -->
                        <div class="sm:col-span-2 p-3 sm:p-4 bg-gray-50 rounded-lg border border-gray-200">
                            <p class="text-xs text-gray-600 mb-1 font-medium">Merek & Model</p>
                            <p class="font-bold text-gray-900 text-sm sm:text-lg break-words">{{ $asset->brand }} {{ $asset->model }}</p>
                            <p class="text-xs text-gray-500 mt-1 break-all">S/N: {{ $asset->serial_number }}</p>
                        </div>

                        <!-- Deskripsi -->
                        @if($asset->description)
                        <div class="sm:col-span-2 p-3 sm:p-4 bg-gradient-to-br from-gray-50 to-gray-100 rounded-lg border border-gray-200">
                            <div class="flex items-start gap-2">
                                <svg class="w-5 h-5 text-gray-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <div class="flex-1">
                                    <p class="text-xs text-gray-600 mb-1 font-medium">Deskripsi</p>
                                    <p class="text-xs sm:text-sm text-gray-900 whitespace-pre-line">{{ $asset->description }}</p>
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- Harga Pembelian -->
                        @if($asset->price)
                        <div class="sm:col-span-2 p-3 sm:p-4 bg-gradient-to-br from-emerald-50 to-emerald-100 rounded-lg border-2 border-emerald-200">
                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <p class="text-xs text-emerald-700 mb-1 font-semibold">Harga Pembelian</p>
                                    <p class="font-bold text-emerald-900 text-base sm:text-2xl">Rp {{ number_format($asset->price, 0, ',', '.') }}</p>
                                </div>
                                <div class="p-2 bg-emerald-200 rounded-full">
                                    <svg class="w-5 h-5 sm:w-6 sm:h-6 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- Lokasi -->
                        <div class="sm:col-span-2 p-3 sm:p-4 bg-gradient-to-br from-red-50 to-red-100 rounded-lg border-2 border-red-200">
                            <div class="flex items-start justify-between gap-2">
                                <div class="flex-1">
                                    <p class="text-xs text-red-700 mb-1 font-semibold">Lokasi Saat Ini</p>
                                    <p class="font-bold text-red-900 text-sm sm:text-lg">{{ $asset->location->name }}</p>
                                    @if($asset->location->floor || $asset->location->unit)
                                    <p class="text-xs text-red-700 mt-1">
                                        {{ $asset->location->floor }}{{ $asset->location->unit ? ' - ' . $asset->location->unit : '' }}
                                    </p>
                                    @endif
                                </div>
                                <div class="p-2 bg-red-200 rounded-full">
                                    <svg class="w-5 h-5 text-red-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <!-- Penanggung Jawab -->
                        @if($asset->penanggung_jawab)
                        <div class="sm:col-span-2 p-3 sm:p-4 bg-gradient-to-br from-indigo-50 to-indigo-100 rounded-lg border-2 border-indigo-200">
                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <p class="text-xs text-indigo-700 mb-1 font-semibold">Penanggung Jawab</p>
                                    <p class="font-bold text-indigo-900 text-sm sm:text-lg">{{ $asset->penanggung_jawab }}</p>
                                </div>
                                <div class="p-2 bg-indigo-200 rounded-full">
                                    <svg class="w-5 h-5 sm:w-6 sm:h-6 text-indigo-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- Tahun & Kondisi -->
                        <div class="p-3 sm:p-4 bg-gray-50 rounded-lg text-center border border-gray-200">
                            <p class="text-xs text-gray-600 mb-1 font-medium">Tahun Pembelian</p>
                            @if($asset->purchase_year == 0)
                                <span class="inline-block px-3 py-1 text-xs sm:text-sm font-semibold rounded-full bg-gray-100 text-gray-600">
                                    Tidak Diketahui
                                </span>
                            @else
                                <p class="text-lg sm:text-2xl font-bold text-gray-900">{{ $asset->purchase_year }}</p>
                            @endif
                        </div>

                        <div class="p-3 sm:p-4 bg-gray-50 rounded-lg text-center border border-gray-200">
                            <p class="text-xs text-gray-600 mb-1 font-medium">Kondisi</p>
                            @php
                                $conditionClasses = match($asset->condition) {
                                    'Baik' => 'bg-green-100 text-green-800',
                                    'Rusak Ringan' => 'bg-yellow-100 text-yellow-800',
                                    default => 'bg-red-100 text-red-800'
                                };
                            @endphp
                            <span class="inline-block px-2.5 py-1 rounded-full text-xs sm:text-sm font-semibold {{ $conditionClasses }}">
                                {{ $asset->condition }}
                            </span>
                        </div>

                        <!-- Invoice Number -->
                        @if($asset->invoice_number)
                        <div class="sm:col-span-2 p-3 sm:p-4 border-2 border-blue-200 bg-blue-50 rounded-lg">
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-blue-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <div class="flex-1">
                                    <p class="text-xs text-blue-700 mb-0.5 font-medium">Nomor Invoice</p>
                                    <p class="text-xs sm:text-sm font-mono font-semibold text-gray-900 break-all">{{ $asset->invoice_number }}</p>
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- Garansi -->
                        @if($asset->warranty_expiry_date)
                        @php
                            $warrantyExpired = $asset->warranty_expiry_date->isPast();
                            $warrantyBgClass = $warrantyExpired ? 'from-red-50 to-red-100 border-red-200' : 'from-blue-50 to-blue-100 border-blue-200';
                            $warrantyIconClass = $warrantyExpired ? 'bg-red-200' : 'bg-blue-200';
                            $warrantyIconColor = $warrantyExpired ? 'text-red-700' : 'text-blue-700';
                            $warrantyTextClass = $warrantyExpired ? 'text-red-700' : 'text-blue-700';
                        @endphp
                        <div class="sm:col-span-2 p-3 sm:p-4 bg-gradient-to-br {{ $warrantyBgClass }} border-2 rounded-lg">
                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <p class="text-xs {{ $warrantyTextClass }} font-semibold mb-1">
                                        Garansi {{ $warrantyExpired ? 'Telah Berakhir' : 'Berakhir' }}
                                    </p>
                                    <p class="text-sm sm:text-base font-bold {{ $warrantyTextClass }}">
                                        {{ $asset->warranty_expiry_date->format('d F Y') }}
                                    </p>
                                    @if(!$warrantyExpired)
                                        <p class="text-xs {{ $warrantyTextClass }} mt-0.5">({{ $asset->warranty_expiry_date->diffForHumans() }})</p>
                                    @endif
                                </div>
                                <div class="p-2 {{ $warrantyIconClass }} rounded-full">
                                    <svg class="w-5 h-5 sm:w-6 sm:h-6 {{ $warrantyIconColor }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>

                    <!-- ✅ RIWAYAT MAINTENANCE TERAKHIR -->
                    @if($asset->maintenanceRecords && $asset->maintenanceRecords->count() > 0)
                    <div class="mt-4 sm:mt-6">
                        <h3 class="text-sm sm:text-lg font-semibold text-gray-800 mb-2 sm:mb-3 flex items-center">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            Riwayat Maintenance
                        </h3>
                        <div class="space-y-2">
                            @foreach($asset->maintenanceRecords as $maintenance)
                            <div class="p-2 sm:p-3 bg-purple-50 rounded-lg border-l-4 border-purple-500">
                                <div class="flex justify-between items-start gap-2 mb-1">
                                    <div class="flex-1 min-w-0">
                                        <div class="flex flex-wrap items-center gap-2 mb-1">
                                            <span class="px-2 py-0.5 text-xs font-semibold rounded {{ $maintenance->status == 'Completed' ? 'bg-green-600 text-white' : ($maintenance->status == 'In Progress' ? 'bg-blue-600 text-white' : 'bg-yellow-600 text-white') }}">
                                                {{ $maintenance->status }}
                                            </span>
                                            <span class="text-xs text-gray-600">
                                                {{ $maintenance->schedule_date->format('d M Y') }}
                                            </span>
                                        </div>
                                        @if($maintenance->notes)
                                        <p class="text-xs sm:text-sm text-gray-700 break-words mt-1">{{ $maintenance->notes }}</p>
                                        @endif
                                        <div class="flex flex-wrap gap-2 mt-2">
                                            @if($maintenance->technician)
                                            <p class="text-xs text-gray-600 flex items-center">
                                                <svg class="w-3 h-3 mr-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                                </svg>
                                                <span class="font-medium">{{ $maintenance->technician->name }}</span>
                                            </p>
                                            @endif
                                            @if($maintenance->cost)
                                            <p class="text-xs text-gray-600 flex items-center font-semibold">
                                                <svg class="w-3 h-3 mr-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                Rp {{ number_format($maintenance->cost, 0, ',', '.') }}
                                            </p>
                                            @endif
                                        </div>
                                        @if($maintenance->performed_date)
                                        <p class="text-xs text-gray-500 mt-1">
                                            Dikerjakan: {{ $maintenance->performed_date->format('d M Y') }}
                                        </p>
                                        @endif
                                        @if($maintenance->tanggal_penerimaan_barang)
                                        <p class="text-xs text-gray-500">
                                            Diterima: {{ $maintenance->tanggal_penerimaan_barang->format('d M Y') }}
                                        </p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- Riwayat Aktivitas -->
                    @if($asset->histories->count() > 0)
                    <div class="mt-4 sm:mt-6">
                        <h3 class="text-sm sm:text-lg font-semibold text-gray-800 mb-2 sm:mb-3 flex items-center">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Riwayat Aktivitas
                        </h3>
                        <div class="space-y-2">
                            @foreach($asset->histories as $history)
                            <div class="flex items-start p-2 sm:p-3 bg-blue-50 rounded-lg border-l-4 border-blue-500">
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-2 mb-1 flex-wrap">
                                        <span class="px-2 py-0.5 text-xs font-semibold rounded bg-blue-600 text-white">
                                            {{ $history->action }}
                                        </span>
                                        <span class="text-xs text-gray-600">
                                            {{ $history->created_at->format('d M Y, H:i') }}
                                        </span>
                                    </div>
                                    @if($history->notes)
                                    <p class="text-xs sm:text-sm text-gray-700 break-words">{{ $history->notes }}</p>
                                    @endif
                                    @if($history->user)
                                    <p class="text-xs text-gray-500 mt-1">
                                        👤 {{ $history->user->name }}
                                    </p>
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