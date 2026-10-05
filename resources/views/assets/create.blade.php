<x-app-layout>
    <style>
        /* Custom styling untuk dropdown searchable */
        .custom-dropdown {
            position: relative;
        }
        
        .custom-dropdown-button {
            width: 100%;
            padding: 0.5rem 1rem;
            border: 1px solid #d1d5db;
            border-radius: 0.5rem;
            background: white;
            text-align: left;
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: all 0.2s;
        }
        
        .custom-dropdown-button:hover {
            border-color: #3b82f6;
        }
        
        .custom-dropdown-button:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }
        
        .custom-dropdown-button.error {
            border-color: #ef4444;
        }
        
        .custom-dropdown-menu {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            margin-top: 0.25rem;
            background: white;
            border: 1px solid #d1d5db;
            border-radius: 0.5rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            z-index: 50;
            max-height: 320px;
            display: none;
        }
        
        .custom-dropdown-menu.active {
            display: block;
        }
        
        .custom-dropdown-search {
            padding: 0.75rem;
            border-bottom: 1px solid #e5e7eb;
            position: sticky;
            top: 0;
            background: white;
            z-index: 10;
        }
        
        .custom-dropdown-search input {
            width: 100%;
            padding: 0.5rem 0.75rem;
            padding-left: 2.5rem;
            border: 1px solid #d1d5db;
            border-radius: 0.375rem;
            font-size: 0.875rem;
        }
        
        .custom-dropdown-search input:focus {
            outline: none;
            border-color: #3b82f6;
        }
        
        .custom-dropdown-search-icon {
            position: absolute;
            left: 1.5rem;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
        }
        
        .custom-dropdown-list {
            max-height: 240px;
            overflow-y: auto;
            padding: 0.25rem;
        }
        
        .custom-dropdown-item {
            padding: 0.625rem 1rem;
            cursor: pointer;
            border-radius: 0.25rem;
            font-size: 0.875rem;
            transition: background 0.15s;
        }
        
        .custom-dropdown-item:hover {
            background: #f3f4f6;
        }
        
        .custom-dropdown-item.selected {
            background: #eff6ff;
            color: #3b82f6;
            font-weight: 500;
        }
        
        .custom-dropdown-header {
            padding: 0.5rem 1rem;
            font-size: 0.75rem;
            font-weight: 600;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        
        .custom-dropdown-empty {
            padding: 2rem 1rem;
            text-align: center;
            color: #9ca3af;
            font-size: 0.875rem;
        }
    </style>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="mb-8">
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Tambah Aset Baru</h1>
            <p class="mt-1 text-sm text-gray-600">Lengkapi formulir di bawah untuk mendaftarkan aset baru ke sistem.</p>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="p-6 sm:p-8">
        <form method="POST" action="{{ route('assets.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Jenis Aset dengan Custom Searchable Dropdown -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Aset *</label>
                    
                    <!-- Hidden Input untuk form submission -->
                    <input type="hidden" name="asset_type_id" id="asset_type_id_hidden" value="{{ old('asset_type_id') }}">
                    
                    <!-- Custom Dropdown -->
                    <div class="custom-dropdown" id="asset_type_dropdown">
                        <button type="button" class="custom-dropdown-button {{ $errors->has('asset_type_id') ? 'error' : '' }}" id="asset_type_button">
                            <span class="text-gray-500" id="asset_type_selected">-- Pilih Jenis Aset --</span>
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        
                        <div class="custom-dropdown-menu" id="asset_type_menu">
                            <div class="custom-dropdown-search">
                                <div class="relative">
                                    <svg class="w-4 h-4 custom-dropdown-search-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                    <input type="text" placeholder="Cari kategori..." id="asset_type_search" autocomplete="off">
                                </div>
                            </div>
                            
                            <div class="custom-dropdown-list" id="asset_type_list">
                                <div class="custom-dropdown-header">Semua Kategori</div>
                                @foreach($assetTypes as $type)
                                    <div class="custom-dropdown-item" 
                                         data-value="{{ $type->id }}"
                                         data-prefix="{{ $type->code_prefix }}"
                                         data-kategori="{{ $type->kategori }}"
                                         data-search="{{ strtolower($type->name . ' ' . $type->code_prefix . ' ' . $type->kategori) }}">
                                        {{ $type->name }} ({{ $type->code_prefix }}) - {{ $type->kategori }}
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    
                    @error('asset_type_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500">
                        💡 Kode aset dan kategori akan otomatis dibuat berdasarkan jenis yang dipilih
                    </p>
                </div>

                <!-- Kategori Asset (Read-only) -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Kategori Asset *</label>
                    <input type="hidden" name="kategori" id="kategori_hidden" value="{{ old('kategori') }}">
                    <div class="relative">
                        <input type="text" id="kategori_display" readonly 
                            value="{{ old('kategori') }}"
                            placeholder="Pilih jenis aset untuk melihat kategori"
                            class="w-full px-4 py-2 rounded-lg bg-gray-50 cursor-not-allowed border {{ $errors->has('kategori') ? 'border-red-500' : 'border-gray-300' }}">
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                            </svg>
                        </div>
                    </div>
                    @error('kategori')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-blue-600 font-medium" id="kategori-hint">
                        📌 Kategori akan otomatis terisi dari jenis aset yang dipilih
                    </p>
                </div>

                <!-- Kode Aset -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Kode Aset *</label>
                    <div class="relative">
                        <input type="text" id="asset_code" value="{{ old('asset_code') }}" readonly 
                            class="w-full px-4 py-2 rounded-lg bg-gray-50 cursor-not-allowed font-mono text-lg {{ $errors->has('asset_code') ? 'border-red-500' : 'border-gray-300' }} border">
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                        </div>
                    </div>
                    <p class="mt-1 text-xs text-blue-600 font-medium" id="code-format-hint">
                        Pilih jenis aset untuk generate kode otomatis
                    </p>
                </div>

                <!-- Merek -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Merek *</label>
                    <input type="text" name="brand" value="{{ old('brand') }}" required 
                        placeholder="contoh: Dell, HP, Cisco"
                        class="w-full px-4 py-2 rounded-lg focus:ring-2 focus:ring-blue-500 {{ $errors->has('brand') ? 'border-red-500' : 'border-gray-300' }} border">
                    @error('brand')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Model -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Model *</label>
                    <input type="text" name="model" value="{{ old('model') }}" required 
                        placeholder="contoh: Optiplex 7090"
                        class="w-full px-4 py-2 rounded-lg focus:ring-2 focus:ring-blue-500 {{ $errors->has('model') ? 'border-red-500' : 'border-gray-300' }} border">
                    @error('model')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Serial Number -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nomor Seri</label>
                    <input type="text" name="serial_number" value="{{ old('serial_number') }}"
                        placeholder="contoh: SN123456789 (opsional)"
                        class="w-full px-4 py-2 rounded-lg focus:ring-2 focus:ring-blue-500 {{ $errors->has('serial_number') ? 'border-red-500' : 'border-gray-300' }} border">
                    @error('serial_number')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500">Opsional: Biarkan kosong jika tidak ada serial number</p>
                </div>

                <!-- Deskripsi -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
                    <textarea name="description" rows="3" 
                        placeholder="Masukkan deskripsi atau spesifikasi aset"
                        class="w-full px-4 py-2 rounded-lg focus:ring-2 focus:ring-blue-500 {{ $errors->has('description') ? 'border-red-500' : 'border-gray-300' }} border">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tahun Pembelian -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tahun Pembelian *</label>
                    <input type="number" name="purchase_year" value="{{ old('purchase_year') }}" required 
                        placeholder="Ketik 0 jika tidak diketahui atau {{ date('Y') }}"
                        class="w-full px-4 py-2 rounded-lg focus:ring-2 focus:ring-blue-500 {{ $errors->has('purchase_year') ? 'border-red-500' : 'border-gray-300' }} border">
                    @error('purchase_year')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500">
                        💡 Ketik <strong class="font-semibold">0</strong> jika tahun tidak diketahui, atau tahun antara 1900 - {{ date('Y') + 1 }}
                    </p>
                </div>

                <!-- Harga Pembelian -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Harga Pembelian (Rp)</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500 font-medium">Rp</span>
                        <input type="number" name="price" value="{{ old('price') }}" 
                            placeholder="0" min="0" step="0.01"
                            class="w-full pl-10 pr-4 py-2 rounded-lg focus:ring-2 focus:ring-blue-500 {{ $errors->has('price') ? 'border-red-500' : 'border-gray-300' }} border">
                    </div>
                    @error('price')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Sumber Dana -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Sumber Dana</label>
                    <select name="sumber_dana" class="w-full px-4 py-2 rounded-lg focus:ring-2 focus:ring-blue-500 {{ $errors->has('sumber_dana') ? 'border-red-500' : 'border-gray-300' }} border">
                        <option value="">Pilih Sumber Dana</option>
                        <option value="Dana Sekolah" {{ old('sumber_dana') == 'Dana Sekolah' ? 'selected' : '' }}>Dana Sekolah</option>
                        <option value="Dana BOS" {{ old('sumber_dana') == 'Dana BOS' ? 'selected' : '' }}>Dana BOS</option>
                        <option value="Yayasan" {{ old('sumber_dana') == 'Yayasan' ? 'selected' : '' }}>Yayasan</option>
                        <option value="Lainnya" {{ old('sumber_dana') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                    </select>
                    @error('sumber_dana')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Kondisi -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Kondisi *</label>
                    <select name="condition" required 
                        class="w-full px-4 py-2 rounded-lg focus:ring-2 focus:ring-blue-500 {{ $errors->has('condition') ? 'border-red-500' : 'border-gray-300' }} border">
                        <option value="">Pilih Kondisi</option>
                        <option value="Baik" {{ old('condition') == 'Baik' ? 'selected' : '' }}>Baik</option>
                        <option value="Rusak Ringan" {{ old('condition') == 'Rusak Ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                        <option value="Rusak Berat" {{ old('condition') == 'Rusak Berat' ? 'selected' : '' }}>Rusak Berat</option>
                    </select>
                    @error('condition')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status *</label>
                    <select name="status" required 
                        class="w-full px-4 py-2 rounded-lg focus:ring-2 focus:ring-blue-500 {{ $errors->has('status') ? 'border-red-500' : 'border-gray-300' }} border">
                        <option value="Aktif" {{ old('status', 'Aktif') == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="Nonaktif" {{ old('status') == 'Nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                    @error('status')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Lokasi dengan Custom Searchable Dropdown -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Lokasi *</label>
                    
                    <!-- Hidden Input untuk form submission -->
                    <input type="hidden" name="location_id" id="location_id_hidden" value="{{ old('location_id') }}">
                    
                    <!-- Custom Dropdown -->
                    <div class="custom-dropdown" id="location_dropdown">
                        <button type="button" class="custom-dropdown-button {{ $errors->has('location_id') ? 'error' : '' }}" id="location_button">
                            <span class="text-gray-500" id="location_selected">-- Pilih Lokasi --</span>
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        
                        <div class="custom-dropdown-menu" id="location_menu">
                            <div class="custom-dropdown-search">
                                <div class="relative">
                                    <svg class="w-4 h-4 custom-dropdown-search-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                    <input type="text" placeholder="Cari lokasi..." id="location_search" autocomplete="off">
                                </div>
                            </div>
                            
                            <div class="custom-dropdown-list" id="location_list">
                                <div class="custom-dropdown-header">Semua Lokasi</div>
                                @foreach($locations as $location)
                                    <div class="custom-dropdown-item" 
                                         data-value="{{ $location->id }}"
                                         data-search="{{ strtolower($location->name . ' ' . $location->floor) }}">
                                        {{ $location->name }} - {{ $location->floor }}
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    
                    @error('location_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Penanggung Jawab -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Penanggung Jawab</label>
                    <input type="text" name="penanggung_jawab" value="{{ old('penanggung_jawab') }}"
                        placeholder="Masukkan nama penanggung jawab"
                        class="w-full px-4 py-2 rounded-lg focus:ring-2 focus:ring-blue-500 {{ $errors->has('penanggung_jawab') ? 'border-red-500' : 'border-gray-300' }} border">
                    @error('penanggung_jawab')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Intensitas Pemakaian -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Intensitas Pemakaian</label>
                    <input type="text" name="intensitas_pemakaian" value="{{ old('intensitas_pemakaian') }}"
                        placeholder="Contoh: 24Jam Nyala, 6Jam Nyala, 3Jam Nyala"
                        class="w-full px-4 py-2 rounded-lg focus:ring-2 focus:ring-blue-500 {{ $errors->has('intensitas_pemakaian') ? 'border-red-500' : 'border-gray-300' }} border">
                    @error('intensitas_pemakaian')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500">
                        💡 Tingkat seberapa sering aset digunakan
                    </p>
                </div>

                <!-- Masa Pemakaian -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Masa Pemakaian</label>
                    <div class="flex gap-2">
                        <input type="number" name="masa_pemakaian" value="{{ old('masa_pemakaian') }}"
                            placeholder="0" min="0" step="1"
                            class="w-full px-4 py-2 rounded-lg focus:ring-2 focus:ring-blue-500 {{ $errors->has('masa_pemakaian') ? 'border-red-500' : 'border-gray-300' }} border">
                        <select name="masa_pemakaian_satuan"
                            class="px-4 py-2 rounded-lg focus:ring-2 focus:ring-blue-500 border-gray-300 border">
                            <option value="Bulan" {{ old('masa_pemakaian_satuan', 'Bulan') == 'Bulan' ? 'selected' : '' }}>Bulan</option>
                            <option value="Tahun" {{ old('masa_pemakaian_satuan') == 'Tahun' ? 'selected' : '' }}>Tahun</option>
                        </select>
                    </div>
                    @error('masa_pemakaian')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    @error('masa_pemakaian_satuan')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500">
                        💡 Estimasi masa pakai aset sejak dibeli
                    </p>
                </div>

                <!-- Tanggal Garansi Berakhir -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Garansi Berakhir</label>
                    <input type="date" name="warranty_expiry_date" value="{{ old('warranty_expiry_date') }}" 
                        class="w-full px-4 py-2 rounded-lg focus:ring-2 focus:ring-blue-500 {{ $errors->has('warranty_expiry_date') ? 'border-red-500' : 'border-gray-300' }} border">
                    @error('warranty_expiry_date')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Foto -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Foto Perangkat</label>
                    <input type="file" name="photo" accept="image/*" 
                        class="w-full px-4 py-2 rounded-lg focus:ring-2 focus:ring-blue-500 {{ $errors->has('photo') ? 'border-red-500' : 'border-gray-300' }} border">
                    @error('photo')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500">Max: 2MB</p>
                </div>

                <!-- Nomor Invoice -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nomor Invoice</label>
                    <input type="text" name="invoice_number" value="{{ old('invoice_number') }}" 
                        placeholder="Contoh: INV/2025/001"
                        class="w-full px-4 py-2 rounded-lg focus:ring-2 focus:ring-blue-500 {{ $errors->has('invoice_number') ? 'border-red-500' : 'border-gray-300' }} border">
                    @error('invoice_number')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- MAINTENANCE RUTIN SECTION -->
                <div class="md:col-span-2 border-t border-gray-200 pt-6 mt-4">
                    <div class="flex items-center mb-4">
                        <input type="checkbox" id="enable_maintenance" name="enable_maintenance" value="1" 
                            {{ old('enable_maintenance') ? 'checked' : '' }}
                            onchange="toggleMaintenanceOptions()"
                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2">
                        <label for="enable_maintenance" class="ml-3 text-sm font-semibold text-gray-900">
                            🔧 Buat Jadwal Maintenance Rutin
                        </label>
                    </div>
                    
                    <div id="maintenance_options" style="display: {{ old('enable_maintenance') ? 'block' : 'none' }};" class="ml-7 space-y-4 p-4 bg-blue-50 rounded-lg border border-blue-200">
                        <p class="text-xs text-blue-700 mb-3">
                            💡 Sistem akan otomatis membuat jadwal maintenance berkala untuk aset ini
                        </p>
                        
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <!-- Mulai Dari -->
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Mulai Dari</label>
                                <select name="maintenance_start_from" id="maintenance_start_from" class="w-full px-3 py-2 text-sm rounded-lg border-gray-300 focus:ring-2 focus:ring-blue-500 border">
                                    <option value="next_month" {{ old('maintenance_start_from', 'next_month') == 'next_month' ? 'selected' : '' }}>Bulan Depan</option>
                                    <option value="this_month" {{ old('maintenance_start_from') == 'this_month' ? 'selected' : '' }}>Bulan Ini</option>
                                    <option value="custom" {{ old('maintenance_start_from') == 'custom' ? 'selected' : '' }}>Pilih Tanggal</option>
                                </select>
                            </div>

                            <!-- Custom Start Date -->
                            <div id="custom_start_date_wrapper" style="display: {{ old('maintenance_start_from') == 'custom' ? 'block' : 'none' }};">
                                <label class="block text-xs font-medium text-gray-700 mb-1">Tanggal Mulai</label>
                                <input type="date" name="maintenance_custom_date" value="{{ old('maintenance_custom_date') }}"
                                    class="w-full px-3 py-2 text-sm rounded-lg border-gray-300 focus:ring-2 focus:ring-blue-500 border">
                            </div>

                            <!-- Interval Maintenance -->
                            <div class="sm:col-span-2">
                                <label class="block text-xs font-medium text-gray-700 mb-2">Frekuensi Maintenance</label>
                                <select name="maintenance_interval" id="maintenance_interval" class="w-full px-3 py-2 text-sm rounded-lg border-gray-300 focus:ring-2 focus:ring-blue-500 border">
                                    <option value="3" {{ old('maintenance_interval') == '3' ? 'selected' : '' }}>Setiap 3 Bulan (Quarterly)</option>
                                    <option value="6" {{ old('maintenance_interval', '6') == '6' ? 'selected' : '' }}>Setiap 6 Bulan (Semesteran)</option>
                                    <option value="12" {{ old('maintenance_interval') == '12' ? 'selected' : '' }}>Setiap 12 Bulan (Tahunan)</option>
                                    <option value="24" {{ old('maintenance_interval') == '24' ? 'selected' : '' }}>Setiap 24 Bulan (2 Tahunan)</option>
                                </select>
                                <p class="text-xs text-gray-500 mt-1">Maintenance akan dilakukan secara berkala sesuai interval yang dipilih</p>
                            </div>

                            <!-- Teknisi -->
                            <div class="sm:col-span-2">
                                <label class="block text-xs font-medium text-gray-700 mb-2">
                                    Teknisi Default 
                                    <span class="text-gray-500">(Opsional - Bisa dikosongkan)</span>
                                </label>
                                
                                <!-- Toggle antara pilih dari user atau input manual -->
                                <div class="mb-2 flex gap-3">
                                    <label class="inline-flex items-center cursor-pointer">
                                        <input type="radio" name="technician_type" value="existing" 
                                            {{ old('technician_type', 'existing') == 'existing' ? 'checked' : '' }}
                                            onchange="toggleTechnicianInput()" class="w-4 h-4 text-blue-600">
                                        <span class="ml-2 text-xs text-gray-700">Pilih dari User</span>
                                    </label>
                                    <label class="inline-flex items-center cursor-pointer">
                                        <input type="radio" name="technician_type" value="manual" 
                                            {{ old('technician_type') == 'manual' ? 'checked' : '' }}
                                            onchange="toggleTechnicianInput()" class="w-4 h-4 text-blue-600">
                                        <span class="ml-2 text-xs text-gray-700">Input Manual</span>
                                    </label>
                                </div>

                                <!-- Dropdown User -->
                                <div id="technician_select_wrapper">
                                    <select name="maintenance_technician_id" id="maintenance_technician_id" 
                                        class="w-full px-3 py-2 text-sm rounded-lg border-gray-300 focus:ring-2 focus:ring-blue-500 border">
                                        <option value="">-- Tidak Ada / Belum Ditentukan --</option>
                                        @foreach(\App\Models\User::orderBy('name')->get() as $user)
                                            <option value="{{ $user->id }}" {{ old('maintenance_technician_id') == $user->id ? 'selected' : '' }}>
                                                {{ $user->name }} ({{ $user->role }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Input Manual -->
                                <div id="technician_manual_wrapper" style="display: none;">
                                    <input type="text" name="maintenance_technician_name" 
                                        value="{{ old('maintenance_technician_name') }}"
                                        placeholder="Contoh: John Doe - Teknisi Komputer (atau kosongkan)"
                                        class="w-full px-3 py-2 text-sm rounded-lg border-gray-300 focus:ring-2 focus:ring-blue-500 border">
                                </div>

                                <p class="text-xs text-blue-600 mt-1">
                                    💡 <strong>Opsional:</strong> Biarkan kosong jika teknisi belum ditentukan. Bisa diisi kemudian saat maintenance dijadwalkan.
                                </p>
                            </div>

                            <!-- Preview Info -->
                            <div class="bg-white p-3 rounded border border-blue-200 sm:col-span-2">
                                <p class="text-xs font-semibold text-gray-700 mb-1">📅 Preview Jadwal:</p>
                                <p class="text-xs text-gray-600" id="maintenance_preview">
                                    Maintenance akan dilakukan setiap 6 bulan secara berkala
                                </p>
                                <p class="text-xs text-green-600 mt-2 font-medium">
                                    ✅ Jadwal maintenance akan dibuat otomatis untuk 10 tahun ke depan
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-6 flex justify-end gap-2">
                <a href="{{ route('assets.index') }}" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400">
                    Batal
                </a>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Simpan Aset
                </button>
            </div>
        </form>
    </div>

    <script>
        // ======================
        // CUSTOM DROPDOWN LOGIC
        // ======================
        
        // Asset Type Dropdown
        const assetTypeDropdown = document.getElementById('asset_type_dropdown');
        const assetTypeButton = document.getElementById('asset_type_button');
        const assetTypeMenu = document.getElementById('asset_type_menu');
        const assetTypeSearch = document.getElementById('asset_type_search');
        const assetTypeList = document.getElementById('asset_type_list');
        const assetTypeHidden = document.getElementById('asset_type_id_hidden');
        const assetTypeSelected = document.getElementById('asset_type_selected');
        
        // Location Dropdown
        const locationDropdown = document.getElementById('location_dropdown');
        const locationButton = document.getElementById('location_button');
        const locationMenu = document.getElementById('location_menu');
        const locationSearch = document.getElementById('location_search');
        const locationList = document.getElementById('location_list');
        const locationHidden = document.getElementById('location_id_hidden');
        const locationSelected = document.getElementById('location_selected');
        
        // Toggle dropdown visibility
        function toggleDropdown(button, menu) {
            const isActive = menu.classList.contains('active');
            
            // Close all dropdowns
            document.querySelectorAll('.custom-dropdown-menu').forEach(m => m.classList.remove('active'));
            
            if (!isActive) {
                menu.classList.add('active');
                // Focus search input
                const searchInput = menu.querySelector('input[type="text"]');
                if (searchInput) {
                    setTimeout(() => searchInput.focus(), 100);
                }
            }
        }
        
        // Asset Type Button Click
        assetTypeButton.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            toggleDropdown(assetTypeButton, assetTypeMenu);
        });
        
        // Location Button Click
        locationButton.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            toggleDropdown(locationButton, locationMenu);
        });
        
        // Search functionality for Asset Type
        assetTypeSearch.addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const items = assetTypeList.querySelectorAll('.custom-dropdown-item');
            let hasResults = false;
            
            items.forEach(item => {
                const searchData = item.getAttribute('data-search');
                if (searchData.includes(searchTerm)) {
                    item.style.display = 'block';
                    hasResults = true;
                } else {
                    item.style.display = 'none';
                }
            });
            
            // Show/hide "no results" message
            let noResultsDiv = assetTypeList.querySelector('.custom-dropdown-empty');
            if (!hasResults) {
                if (!noResultsDiv) {
                    noResultsDiv = document.createElement('div');
                    noResultsDiv.className = 'custom-dropdown-empty';
                    noResultsDiv.textContent = 'Tidak ada hasil ditemukan';
                    assetTypeList.appendChild(noResultsDiv);
                }
            } else {
                if (noResultsDiv) {
                    noResultsDiv.remove();
                }
            }
        });
        
        // Search functionality for Location
        locationSearch.addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const items = locationList.querySelectorAll('.custom-dropdown-item');
            let hasResults = false;
            
            items.forEach(item => {
                const searchData = item.getAttribute('data-search');
                if (searchData.includes(searchTerm)) {
                    item.style.display = 'block';
                    hasResults = true;
                } else {
                    item.style.display = 'none';
                }
            });
            
            // Show/hide "no results" message
            let noResultsDiv = locationList.querySelector('.custom-dropdown-empty');
            if (!hasResults) {
                if (!noResultsDiv) {
                    noResultsDiv = document.createElement('div');
                    noResultsDiv.className = 'custom-dropdown-empty';
                    noResultsDiv.textContent = 'Tidak ada hasil ditemukan';
                    locationList.appendChild(noResultsDiv);
                }
            } else {
                if (noResultsDiv) {
                    noResultsDiv.remove();
                }
            }
        });
        
        // Select item for Asset Type
        assetTypeList.addEventListener('click', function(e) {
            const item = e.target.closest('.custom-dropdown-item');
            if (!item) return;
            
            const value = item.getAttribute('data-value');
            const text = item.textContent.trim();
            const prefix = item.getAttribute('data-prefix');
            const kategori = item.getAttribute('data-kategori');
            
            // Update hidden input and display
            assetTypeHidden.value = value;
            assetTypeSelected.textContent = text;
            assetTypeSelected.classList.remove('text-gray-500');
            assetTypeSelected.classList.add('text-gray-900');
            
            // Update selected state
            assetTypeList.querySelectorAll('.custom-dropdown-item').forEach(i => {
                i.classList.remove('selected');
            });
            item.classList.add('selected');
            
            // Close dropdown
            assetTypeMenu.classList.remove('active');
            
            // Clear search
            assetTypeSearch.value = '';
            assetTypeList.querySelectorAll('.custom-dropdown-item').forEach(i => {
                i.style.display = 'block';
            });
            
            // Trigger asset code generation
            handleAssetTypeChange(value, prefix, kategori);
        });
        
        // Select item for Location
        locationList.addEventListener('click', function(e) {
            const item = e.target.closest('.custom-dropdown-item');
            if (!item) return;
            
            const value = item.getAttribute('data-value');
            const text = item.textContent.trim();
            
            // Update hidden input and display
            locationHidden.value = value;
            locationSelected.textContent = text;
            locationSelected.classList.remove('text-gray-500');
            locationSelected.classList.add('text-gray-900');
            
            // Update selected state
            locationList.querySelectorAll('.custom-dropdown-item').forEach(i => {
                i.classList.remove('selected');
            });
            item.classList.add('selected');
            
            // Close dropdown
            locationMenu.classList.remove('active');
            
            // Clear search
            locationSearch.value = '';
            locationList.querySelectorAll('.custom-dropdown-item').forEach(i => {
                i.style.display = 'block';
            });
        });
        
        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            if (!assetTypeDropdown.contains(e.target)) {
                assetTypeMenu.classList.remove('active');
            }
            if (!locationDropdown.contains(e.target)) {
                locationMenu.classList.remove('active');
            }
        });
        
        // Prevent dropdown from closing when clicking inside menu
        assetTypeMenu.addEventListener('click', function(e) {
            e.stopPropagation();
        });
        
        locationMenu.addEventListener('click', function(e) {
            e.stopPropagation();
        });
        
        // Initialize selected values from old input (for validation errors)
        document.addEventListener('DOMContentLoaded', function() {
            // Asset Type
            const oldAssetTypeId = assetTypeHidden.value;
            if (oldAssetTypeId) {
                const selectedItem = assetTypeList.querySelector(`[data-value="${oldAssetTypeId}"]`);
                if (selectedItem) {
                    assetTypeSelected.textContent = selectedItem.textContent.trim();
                    assetTypeSelected.classList.remove('text-gray-500');
                    assetTypeSelected.classList.add('text-gray-900');
                    selectedItem.classList.add('selected');
                }
            }
            
            // Location
            const oldLocationId = locationHidden.value;
            if (oldLocationId) {
                const selectedItem = locationList.querySelector(`[data-value="${oldLocationId}"]`);
                if (selectedItem) {
                    locationSelected.textContent = selectedItem.textContent.trim();
                    locationSelected.classList.remove('text-gray-500');
                    locationSelected.classList.add('text-gray-900');
                    selectedItem.classList.add('selected');
                }
            }
            
            // Initialize technician input toggle
            const manualInput = document.querySelector('input[name="maintenance_technician_name"]');
            if (manualInput && manualInput.value) {
                document.querySelector('input[name="technician_type"][value="manual"]').checked = true;
                toggleTechnicianInput();
            }
        });
        
        // ======================
        // ASSET CODE GENERATION
        // ======================
        
        function handleAssetTypeChange(assetTypeId, prefix, kategori) {
            const assetCodeInput = document.getElementById('asset_code');
            const codeHintEl = document.getElementById('code-format-hint');
            const kategoriDisplay = document.getElementById('kategori_display');
            const kategoriHidden = document.getElementById('kategori_hidden');
            const kategoriHint = document.getElementById('kategori-hint');
            
            if (!assetTypeId) {
                assetCodeInput.value = '';
                kategoriDisplay.value = '';
                kategoriHidden.value = '';
                codeHintEl.textContent = 'Pilih jenis aset untuk generate kode otomatis';
                kategoriHint.textContent = '📌 Kategori akan otomatis terisi dari jenis aset yang dipilih';
                return;
            }
            
            // Update kategori display
            kategoriDisplay.value = kategori;
            kategoriHidden.value = kategori;
            
            // Color badge based on kategori
            const badgeColors = {
                'Asset TI': 'bg-purple-100 border-purple-300 text-purple-800',
                'Asset Rumah Tangga': 'bg-green-100 border-green-300 text-green-800',
                'Asset Transportasi': 'bg-orange-100 border-orange-300 text-orange-800',
                'Asset Gizi': 'bg-pink-100 border-pink-300 text-pink-800',
                'Asset Lainnya': 'bg-gray-100 border-gray-300 text-gray-800'
            };
            
            kategoriDisplay.className = 'w-full px-4 py-2 rounded-lg border-2 cursor-not-allowed font-medium ' + (badgeColors[kategori] || 'bg-gray-50 border-gray-300');
            kategoriHint.innerHTML = `✅ Kategori otomatis: <strong class="font-semibold">${kategori}</strong>`;
            
            assetCodeInput.value = 'Generating...';
            codeHintEl.textContent = '⏳ Sedang membuat kode...';
            
            fetch('{{ route('assets.generate-code') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ asset_type_id: assetTypeId })
            })
            .then(response => response.json())
            .then(data => {
                assetCodeInput.value = data.asset_code;
                codeHintEl.innerHTML = `✅ Kode dibuat otomatis: <strong class="font-mono">${prefix}-YYYY-XXXX</strong>`;
            })
            .catch(error => {
                console.error('Error:', error);
                assetCodeInput.value = 'Error generating code';
                codeHintEl.textContent = '❌ Gagal generate kode, silakan refresh halaman';
            });
        }
        
        // ======================
        // MAINTENANCE FUNCTIONS
        // ======================
        
        function toggleMaintenanceOptions() {
            const checkbox = document.getElementById('enable_maintenance');
            const options = document.getElementById('maintenance_options');
            options.style.display = checkbox.checked ? 'block' : 'none';
            updateMaintenancePreview();
        }
        
        document.querySelector('select[name="maintenance_start_from"]')?.addEventListener('change', function() {
            const customDateWrapper = document.getElementById('custom_start_date_wrapper');
            customDateWrapper.style.display = this.value === 'custom' ? 'block' : 'none';
            updateMaintenancePreview();
        });
        
        function updateMaintenancePreview() {
            const checkbox = document.getElementById('enable_maintenance');
            if (!checkbox.checked) return;

            const startFrom = document.querySelector('select[name="maintenance_start_from"]').value;
            const interval = parseInt(document.querySelector('select[name="maintenance_interval"]').value);

            const startText = {
                'next_month': 'bulan depan',
                'this_month': 'bulan ini',
                'custom': 'tanggal yang dipilih'
            }[startFrom] || 'bulan depan';

            const intervalText = {
                3: 'setiap 3 bulan (quarterly)',
                6: 'setiap 6 bulan (semesteran)',
                12: 'setiap 12 bulan (tahunan)',
                24: 'setiap 24 bulan (2 tahunan)'
            }[interval] || `setiap ${interval} bulan`;

            document.getElementById('maintenance_preview').textContent = 
                `Maintenance akan dilakukan ${intervalText} secara berkala (dimulai dari ${startText})`;
        }
        
        document.querySelector('select[name="maintenance_interval"]')?.addEventListener('change', updateMaintenancePreview);
        
        function toggleTechnicianInput() {
            const type = document.querySelector('input[name="technician_type"]:checked').value;
            const selectWrapper = document.getElementById('technician_select_wrapper');
            const manualWrapper = document.getElementById('technician_manual_wrapper');
            const selectInput = document.getElementById('maintenance_technician_id');
            const manualInput = document.querySelector('input[name="maintenance_technician_name"]');

            if (type === 'manual') {
                selectWrapper.style.display = 'none';
                manualWrapper.style.display = 'block';
                selectInput.disabled = true;
                manualInput.disabled = false;
            } else {
                selectWrapper.style.display = 'block';
                manualWrapper.style.display = 'none';
                selectInput.disabled = false;
                manualInput.disabled = true;
            }
        }
    </script>
            </div>
        </div>
    </div>
</x-app-layout>