<x-app-layout>
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Tambah Aset Baru</h1>
        <p class="text-gray-600">Tambahkan aset IT baru ke sistem</p>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <form method="POST" action="{{ route('assets.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Jenis Aset -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Aset *</label>
                    <select name="asset_type_id" id="asset_type_id" required 
                        class="w-full px-4 py-2 rounded-lg focus:ring-2 focus:ring-blue-500 {{ $errors->has('asset_type_id') ? 'border-red-500' : 'border-gray-300' }} border">
                        <option value="">-- Pilih Jenis Aset --</option>
                        @foreach($assetTypes as $type)
                            <option value="{{ $type->id }}" 
                                data-prefix="{{ $type->code_prefix }}"
                                {{ old('asset_type_id') == $type->id ? 'selected' : '' }}>
                                {{ $type->name }} ({{ $type->code_prefix }})
                            </option>
                        @endforeach
                    </select>
                    @error('asset_type_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500">
                        💡 Kode aset akan otomatis dibuat berdasarkan jenis yang dipilih
                        @can('create', App\Models\AssetType::class)
                            | <a href="{{ route('asset-types.create') }}" class="text-blue-600 hover:underline" target="_blank">Tambah jenis baru</a>
                        @endcan
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
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nomor Seri *</label>
                    <input type="text" name="serial_number" value="{{ old('serial_number') }}" required 
                        placeholder="contoh: SN123456789"
                        class="w-full px-4 py-2 rounded-lg focus:ring-2 focus:ring-blue-500 {{ $errors->has('serial_number') ? 'border-red-500' : 'border-gray-300' }} border">
                    @error('serial_number')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

            <!-- Deskripsi -->
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
                <textarea name="description" rows="3" 
                    placeholder="Masukkan deskripsi atau spesifikasi aset (contoh: RAM 16GB, Storage 512GB SSD, Processor Intel i7)"
                    class="w-full px-4 py-2 rounded-lg focus:ring-2 focus:ring-blue-500 {{ $errors->has('description') ? 'border-red-500' : 'border-gray-300' }} border">{{ old('description') }}</textarea>
                @error('description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-xs text-gray-500">Opsional: Tambahkan detail spesifikasi atau catatan khusus</p>
            </div>

                <!-- Tahun Pembelian -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tahun Pembelian *</label>
                    <input type="number" name="purchase_year" value="{{ old('purchase_year', date('Y')) }}" required 
                        min="1900" max="{{ date('Y') + 1 }}"
                        class="w-full px-4 py-2 rounded-lg focus:ring-2 focus:ring-blue-500 {{ $errors->has('purchase_year') ? 'border-red-500' : 'border-gray-300' }} border">
                    @error('purchase_year')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- ✅ TAMBAHAN: Input Harga -->
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
                    <p class="mt-1 text-xs text-gray-500">Contoh: 15000000 untuk Rp 15.000.000</p>
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

                <!-- Lokasi -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Lokasi *</label>
                    <select name="location_id" required 
                        class="w-full px-4 py-2 rounded-lg focus:ring-2 focus:ring-blue-500 {{ $errors->has('location_id') ? 'border-red-500' : 'border-gray-300' }} border">
                        <option value="">Pilih Lokasi</option>
                        @foreach($locations as $location)
                            <option value="{{ $location->id }}" {{ old('location_id') == $location->id ? 'selected' : '' }}>
                                {{ $location->name }} - {{ $location->floor }}
                            </option>
                        @endforeach
                    </select>
                    @error('location_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
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
                    <p class="mt-1 text-xs text-gray-500">Opsional: Masukkan nomor invoice pembelian</p>
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
        document.getElementById('asset_type_id').addEventListener('change', function() {
            const assetTypeId = this.value;
            const assetCodeInput = document.getElementById('asset_code');
            const hintEl = document.getElementById('code-format-hint');
            const selectedOption = this.options[this.selectedIndex];
            const prefix = selectedOption.getAttribute('data-prefix');
            
            if (!assetTypeId) {
                assetCodeInput.value = '';
                hintEl.textContent = 'Pilih jenis aset untuk generate kode otomatis';
                return;
            }
            
            assetCodeInput.value = 'Generating...';
            hintEl.textContent = '⏳ Sedang membuat kode...';
            
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
                hintEl.innerHTML = `✅ Kode dibuat otomatis: <strong class="font-mono">${prefix}-YYYY-XXXX</strong>`;
            })
            .catch(error => {
                console.error('Error:', error);
                assetCodeInput.value = 'Error generating code';
                hintEl.textContent = '❌ Gagal generate kode, silakan refresh halaman';
            });
        });
    </script>
</x-app-layout>