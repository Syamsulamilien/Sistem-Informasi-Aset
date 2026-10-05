<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <!-- Header -->
        <div class="mb-6">
            <div class="flex flex-col gap-4">
                <div>
                    <h1 class="text-2xl lg:text-3xl font-bold text-gray-800">Detail Aset</h1>
                    <p class="text-gray-600 text-sm lg:text-base mt-1">{{ $asset->asset_code }}</p>
                </div>
                <!-- Action Buttons -->
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('assets.qrcode', $asset) }}" target="_blank" class="inline-flex items-center px-3 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 text-sm font-medium">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path>
                        </svg>
                        QR Code
                    </a>
                    @can('update', $asset)
                        <a href="{{ route('assets.edit', $asset) }}" class="inline-flex items-center px-3 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 text-sm font-medium">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            Edit
                        </a>
                    @endcan
                    <a href="{{ route('assets.index') }}" class="inline-flex items-center px-3 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 text-sm font-medium">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Kembali
                    </a>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 lg:gap-6">
            <!-- Main Info -->
            <div class="lg:col-span-2 space-y-4 lg:space-y-6">
                <!-- Basic Information -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 lg:p-6">
                    <h3 class="text-lg lg:text-xl font-semibold mb-4">Informasi Dasar</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- Jenis Aset -->
                        <div class="p-4 bg-gradient-to-br from-blue-50 to-blue-100 rounded-lg border-2 border-blue-200 shadow-sm">
                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <p class="text-xs text-blue-700 mb-1 font-semibold flex items-center">
                                        <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                        </svg>
                                        Jenis Aset
                                    </p>
                                    <p class="font-bold text-blue-900 text-lg mt-1">{{ $asset->assetType->name ?? 'Tidak ada data' }}</p>
                                    @if($asset->assetType && $asset->assetType->description)
                                        <p class="text-xs text-blue-700 mt-1">{{ $asset->assetType->description }}</p>
                                    @endif
                                </div>
                                <div class="p-2 bg-blue-200 rounded-full">
                                    <svg class="w-6 h-6 text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <!-- Kategori Asset -->
                        <div class="p-4 bg-gradient-to-br from-purple-50 to-purple-100 rounded-lg border-2 border-purple-200 shadow-sm">
                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <p class="text-xs text-purple-700 mb-1 font-semibold flex items-center">
                                        <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                        </svg>
                                        Kategori Asset
                                    </p>
                                    <p class="font-bold text-purple-900 text-lg mt-1">{{ $asset->kategori ?? 'Tidak ada data' }}</p>
                                </div>
                                <div class="p-2 bg-purple-200 rounded-full">
                                    <svg class="w-6 h-6 text-purple-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <!-- Merek & Model -->
                        <div class="p-3 bg-gray-50 rounded-lg">
                            <p class="text-xs text-gray-600 mb-1">Merek & Model</p>
                            <p class="font-semibold text-gray-900">{{ $asset->brand }}</p>
                            <p class="text-sm text-gray-600 mt-0.5">{{ $asset->model }}</p>
                        </div>

                        <!-- Serial Number -->
                        <div class="p-3 bg-gray-50 rounded-lg">
                            <p class="text-xs text-gray-600 mb-1">Nomor Seri</p>
                            <p class="font-mono font-semibold text-gray-900 text-sm">{{ $asset->serial_number }}</p>
                        </div>

                        <!-- Deskripsi -->
                        @if($asset->description)
                        <div class="sm:col-span-2 p-4 bg-gradient-to-br from-gray-50 to-gray-100 rounded-lg border border-gray-200">
                            <div class="flex items-start gap-2">
                                <svg class="w-5 h-5 text-gray-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <div class="flex-1">
                                    <p class="text-xs text-gray-600 mb-1 font-medium">Deskripsi</p>
                                    <p class="text-sm text-gray-900 whitespace-pre-line">{{ $asset->description }}</p>
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- ✅ TAHUN PEMBELIAN (FIXED) -->
                        <div class="p-3 bg-gray-50 rounded-lg">
                            <p class="text-xs text-gray-600 mb-1">Tahun Pembelian</p>
                            @if($asset->purchase_year == 0)
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-600 inline-block">
                                    Tidak Diketahui
                                </span>
                            @else
                                <p class="font-semibold text-gray-900">{{ $asset->purchase_year }}</p>
                            @endif
                        </div>

                        <!-- Harga Pembelian -->
                        <div class="p-4 bg-gradient-to-br from-emerald-50 to-emerald-100 rounded-lg border-2 border-emerald-200 shadow-sm">
                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <p class="text-xs text-emerald-700 mb-1 font-semibold flex items-center">
                                        <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Harga Pembelian
                                    </p>
                                    @if($asset->price)
                                        <p class="font-bold text-emerald-900 text-xl mt-1">Rp {{ number_format($asset->price, 0, ',', '.') }}</p>
                                    @else
                                        <p class="text-sm text-gray-500 italic mt-1">Tidak ada data</p>
                                    @endif
                                </div>
                                <div class="p-2 bg-emerald-200 rounded-full">
                                    <svg class="w-6 h-6 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <!-- Sumber Dana -->
                        <div class="p-3 bg-gray-50 rounded-lg">
                            <p class="text-xs text-gray-600 mb-1">Sumber Dana</p>
                            @if($asset->sumber_dana)
                                <p class="font-semibold text-gray-900">{{ $asset->sumber_dana }}</p>
                            @else
                                <p class="text-sm text-gray-500 italic">Tidak ada data</p>
                            @endif
                        </div>

                        <!-- Kondisi -->
                        <div class="p-3 bg-gray-50 rounded-lg">
                            <p class="text-xs text-gray-600 mb-1">Kondisi</p>
                            @php
                                $conditionClasses = match($asset->condition) {
                                    'Baik' => 'bg-green-100 text-green-800',
                                    'Rusak Ringan' => 'bg-yellow-100 text-yellow-800',
                                    default => 'bg-red-100 text-red-800'
                                };
                            @endphp
                            <span class="inline-flex px-2.5 py-1 text-xs font-semibold rounded-full {{ $conditionClasses }}">
                                {{ $asset->condition }}
                            </span>
                        </div>

                        <!-- Status -->
                        <div class="p-3 bg-gray-50 rounded-lg">
                            <p class="text-xs text-gray-600 mb-1">Status</p>
                            <span class="inline-flex px-2.5 py-1 text-xs font-semibold rounded-full {{ $asset->status == 'Aktif' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $asset->status }}
                            </span>
                        </div>

                        <!-- Lokasi -->
                        <div class="p-3 bg-gray-50 rounded-lg">
                            <p class="text-xs text-gray-600 mb-1">Lokasi</p>
                            <p class="font-semibold text-gray-900">{{ $asset->location->name }}</p>
                            @if($asset->location->description)
                                <p class="text-xs text-gray-500 mt-0.5">{{ Str::limit($asset->location->description, 50) }}</p>
                            @endif
                        </div>
                        
                        <!-- Penanggung Jawab -->
                        @if($asset->penanggung_jawab)
                        <div class="p-4 bg-gradient-to-br from-indigo-50 to-indigo-100 rounded-lg border-2 border-indigo-200 shadow-sm">
                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <p class="text-xs text-indigo-700 mb-1 font-semibold flex items-center">
                                        <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                        Penanggung Jawab
                                    </p>
                                    <p class="font-bold text-indigo-900 text-lg mt-1">{{ $asset->penanggung_jawab }}</p>
                                </div>
                                <div class="p-2 bg-indigo-200 rounded-full">
                                    <svg class="w-6 h-6 text-indigo-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- Intensitas Pemakaian -->
                        @if($asset->intensitas_pemakaian)
                        <div class="p-4 bg-gradient-to-br from-orange-50 to-orange-100 rounded-lg border-2 border-orange-200 shadow-sm">
                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <p class="text-xs text-orange-700 mb-1 font-semibold flex items-center">
                                        <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                        </svg>
                                        Intensitas Pemakaian
                                    </p>
                                    <p class="font-bold text-orange-900 text-lg mt-1">{{ $asset->intensitas_pemakaian }}</p>
                                </div>
                                <div class="p-2 bg-orange-200 rounded-full">
                                    <svg class="w-6 h-6 text-orange-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- Masa Pemakaian -->
                        @if($asset->masa_pemakaian)
                        <div class="p-4 bg-gradient-to-br from-teal-50 to-teal-100 rounded-lg border-2 border-teal-200 shadow-sm">
                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <p class="text-xs text-teal-700 mb-1 font-semibold flex items-center">
                                        <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Masa Pemakaian
                                    </p>
                                    <p class="font-bold text-teal-900 text-lg mt-1">{{ $asset->masa_pemakaian }} {{ $asset->masa_pemakaian_satuan ?? 'Bulan' }}</p>
                                </div>
                                <div class="p-2 bg-teal-200 rounded-full">
                                    <svg class="w-6 h-6 text-teal-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- Garansi -->
                        <div class="p-3 bg-gray-50 rounded-lg">
                            <p class="text-xs text-gray-600 mb-1">Garansi Berakhir</p>
                            <p class="font-semibold text-gray-900">{{ $asset->warranty_expiry_date ? $asset->warranty_expiry_date->format('d M Y') : '-' }}</p>
                            @if($asset->warranty_expiry_date && $asset->warranty_expiry_date->isPast())
                                <p class="text-xs text-red-600 mt-0.5">Sudah berakhir</p>
                            @elseif($asset->warranty_expiry_date)
                                <p class="text-xs text-green-600 mt-0.5">{{ $asset->warranty_expiry_date->diffForHumans() }}</p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Documents & Photos -->
                @if($asset->photo || $asset->invoice_number)
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 lg:p-6">
                    <h3 class="text-lg lg:text-xl font-semibold mb-4">Dokumen & Foto</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @if($asset->photo)
                        <div>
                            <p class="text-sm text-gray-600 mb-2">Foto Perangkat</p>
                            <img src="{{ asset('storage/' . $asset->photo) }}" alt="Foto Aset" class="w-full h-48 object-cover rounded-lg border border-gray-200">
                        </div>
                        @endif
                        
                        @if($asset->invoice_number)
                        <div>
                            <p class="text-sm text-gray-600 mb-2">Nomor Invoice</p>
                            <div class="p-4 border-2 border-blue-200 bg-blue-50 rounded-lg">
                                <div class="flex items-center gap-2">
                                    <svg class="w-5 h-5 text-blue-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    <p class="text-sm font-mono font-semibold text-gray-900">{{ $asset->invoice_number }}</p>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
                @endif

                <!-- History -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 lg:p-6">
                    <h3 class="text-lg lg:text-xl font-semibold mb-4">Riwayat Aktivitas</h3>
                    <div class="space-y-3">
                        @forelse($asset->histories as $history)
                        <div class="border-l-4 border-blue-500 pl-3 py-2 bg-blue-50 rounded-r-lg">
                            <div class="flex flex-col gap-1">
                                <div class="flex flex-wrap items-center gap-2">
                                    <span class="px-2 py-0.5 text-xs font-semibold rounded bg-blue-600 text-white">
                                        {{ $history->action }}
                                    </span>
                                    <span class="text-xs text-gray-600">
                                        {{ $history->created_at->timezone('Asia/Jakarta')->format('d M Y, H:i') }}
                                    </span>
                                </div>
                                <span class="text-xs text-gray-500">
                                    {{ $history->created_at->diffForHumans() }}
                                </span>
                                @if($history->action == 'Pindah Lokasi' || $history->action == 'Pindah Ruangan')
                                <p class="mt-1 text-sm text-gray-700">
                                    Dari: <strong class="text-gray-900">{{ $history->fromLocationData->name ?? '-' }}</strong> 
                                    → Ke: <strong class="text-gray-900">{{ $history->toLocationData->name ?? '-' }}</strong>
                                </p>
                                @endif
                                @if($history->notes)
                                <p class="mt-1 text-sm text-gray-700">{{ $history->notes }}</p>
                                @endif
                                @if($history->user)
                                <p class="mt-1 text-xs text-gray-500">
                                    <span class="inline-flex items-center">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                        {{ $history->user->name }}
                                    </span>
                                </p>
                                @endif
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-8">
                            <svg class="w-12 h-12 mx-auto text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <p class="text-gray-500 text-sm">Belum ada riwayat aktivitas</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-4 lg:space-y-6">
                <!-- Quick Stats -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 lg:p-6">
                    <h3 class="text-lg font-semibold mb-4">Informasi Tambahan</h3>
                    <div class="space-y-2">
                        <div class="flex justify-between items-center p-3 bg-gradient-to-r from-blue-50 to-blue-100 rounded-lg">
                            <span class="text-sm text-gray-700 font-medium">Total Maintenance</span>
                            <span class="font-bold text-blue-600 text-lg">{{ $asset->maintenanceRecords->count() }}</span>
                        </div>
                        <div class="flex justify-between items-center p-3 bg-gradient-to-r from-purple-50 to-purple-100 rounded-lg">
                            <span class="text-sm text-gray-700 font-medium">Total Riwayat</span>
                            <span class="font-bold text-purple-600 text-lg">{{ $asset->histories->count() }}</span>
                        </div>
                        <div class="flex justify-between items-center p-3 bg-gradient-to-r from-gray-50 to-gray-100 rounded-lg">
                            <span class="text-sm text-gray-700 font-medium">Ditambahkan</span>
                            <span class="text-xs text-gray-600 font-semibold">{{ $asset->created_at->format('d M Y') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Maintenance Records -->
                @if($asset->maintenanceRecords->count() > 0)
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 lg:p-6">
                    <h3 class="text-lg font-semibold mb-4">Maintenance Terakhir</h3>
                    <div class="space-y-3">
                        @foreach($asset->maintenanceRecords->take(3) as $maintenance)
                        <div class="p-3 bg-gray-50 rounded-lg border border-gray-200 hover:border-blue-300 transition-colors">
                            <div class="flex justify-between items-start mb-2">
                                <div class="flex-1">
                                    <p class="text-sm font-semibold text-gray-900">{{ $maintenance->schedule_date->format('d M Y') }}</p>
                                    <p class="text-xs text-gray-600 mt-0.5">
                                        <svg class="w-3 h-3 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                        @if($maintenance->technician_id && $maintenance->technician)
                                        {{ $maintenance->technician->name }}
                                    @elseif($maintenance->technician_name)
                                        {{ $maintenance->technician_name }}
                                    @else
                                        <span class="italic text-gray-500">Tidak ada teknisi</span>
                                    @endif

                                    </p>
                                </div>
                                <span class="px-2 py-1 text-xs font-semibold rounded-full whitespace-nowrap {{ $maintenance->status == 'Completed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                    {{ $maintenance->status }}
                                </span>
                            </div>
                            @if($maintenance->notes)
                            <p class="text-xs text-gray-600 mt-2 line-clamp-2">{{ $maintenance->notes }}</p>
                            @endif
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>