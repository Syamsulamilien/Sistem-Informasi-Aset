<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Edit Aset</h1>
            <p class="mt-1 text-sm text-gray-600">Update informasi aset {{ $asset->asset_code }}</p>
        </div>

        <!-- Form Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <form method="POST" action="{{ route('assets.update', $asset) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="p-6 sm:p-8">
                    <!-- Informasi Dasar -->
                    <div class="mb-8">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-200">
                            Informasi Dasar
                        </h2>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
                            <!-- Kode Aset (Readonly) -->
                            <div class="sm:col-span-2 lg:col-span-1">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Kode Aset <span class="text-red-500">*</span>
                                </label>
                                <input type="hidden" name="asset_code" value="{{ $asset->asset_code }}">
                                <input type="text" value="{{ $asset->asset_code }}" disabled 
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg bg-gray-100 text-gray-600 cursor-not-allowed">
                                <p class="mt-1.5 text-xs text-gray-500">Kode aset tidak dapat diubah</p>
                            </div>

                            <!-- Jenis (Readonly) -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Jenis <span class="text-red-500">*</span>
                                </label>
                                <input type="hidden" name="asset_type_id" value="{{ $asset->asset_type_id }}">
                                <input type="text" value="{{ $asset->assetType->name ?? 'Tidak ada data' }}" disabled
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg bg-gray-100 text-gray-600 cursor-not-allowed">
                                <p class="mt-1.5 text-xs text-gray-500">Jenis tidak dapat diubah</p>
                            </div>

                            <!-- Merek (Editable) -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Merek <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="brand" value="{{ old('brand', $asset->brand) }}" required 
                                    class="w-full px-4 py-2.5 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors {{ $errors->has('brand') ? 'border-red-500' : 'border-gray-300' }}">
                                @error('brand')
                                    <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Model (Editable) -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Model <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="model" value="{{ old('model', $asset->model) }}" required 
                                    class="w-full px-4 py-2.5 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors {{ $errors->has('model') ? 'border-red-500' : 'border-gray-300' }}">
                                @error('model')
                                    <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Serial Number (Editable) -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Nomor Seri <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="serial_number" value="{{ old('serial_number', $asset->serial_number) }}" required 
                                    class="w-full px-4 py-2.5 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors {{ $errors->has('serial_number') ? 'border-red-500' : 'border-gray-300' }}">
                                @error('serial_number')
                                    <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Deskripsi -->
                            <div class="sm:col-span-2 lg:col-span-3">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Deskripsi
                                </label>
                                <textarea name="description" rows="3" 
                                    placeholder="Masukkan deskripsi atau spesifikasi aset"
                                    class="w-full px-4 py-2.5 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors {{ $errors->has('description') ? 'border-red-500' : 'border-gray-300' }}">{{ old('description', $asset->description) }}</textarea>
                                @error('description')
                                    <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-1.5 text-xs text-gray-500">Opsional: Detail spesifikasi atau catatan khusus</p>
                            </div>
                        </div>
                    </div>

                    <!-- Status & Kondisi -->
                    <div class="mb-8">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-200">
                            Status & Kondisi
                        </h2>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
                            <!-- Tahun Pembelian -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Tahun Pembelian <span class="text-red-500">*</span>
                                </label>
                                <input type="number" name="purchase_year" value="{{ old('purchase_year', $asset->purchase_year) }}" required 
                                    class="w-full px-4 py-2.5 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors {{ $errors->has('purchase_year') ? 'border-red-500' : 'border-gray-300' }}">
                                @error('purchase_year')
                                    <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Harga Pembelian -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Harga Pembelian
                                </label>
                                <div class="relative">
                                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 font-medium">Rp</span>
                                    <input type="number" name="price" value="{{ old('price', $asset->price) }}" 
                                        class="w-full pl-12 pr-4 py-2.5 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors {{ $errors->has('price') ? 'border-red-500' : 'border-gray-300' }}"
                                        placeholder="0">
                                </div>
                                @error('price')
                                    <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-1.5 text-xs text-gray-500">Masukkan harga tanpa titik</p>
                            </div>

                            <!-- Kondisi -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Kondisi <span class="text-red-500">*</span>
                                </label>
                                <select name="condition" required 
                                    class="w-full px-4 py-2.5 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors {{ $errors->has('condition') ? 'border-red-500' : 'border-gray-300' }}">
                                    <option value="">Pilih Kondisi</option>
                                    <option value="Baik" {{ old('condition', $asset->condition) == 'Baik' ? 'selected' : '' }}>Baik</option>
                                    <option value="Rusak Ringan" {{ old('condition', $asset->condition) == 'Rusak Ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                                    <option value="Rusak Berat" {{ old('condition', $asset->condition) == 'Rusak Berat' ? 'selected' : '' }}>Rusak Berat</option>
                                </select>
                                @error('condition')
                                    <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Status -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Status <span class="text-red-500">*</span>
                                </label>
                                <select name="status" required 
                                    class="w-full px-4 py-2.5 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors {{ $errors->has('status') ? 'border-red-500' : 'border-gray-300' }}">
                                    <option value="Aktif" {{ old('status', $asset->status) == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                                    <option value="Nonaktif" {{ old('status', $asset->status) == 'Nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                                </select>
                                @error('status')
                                    <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Lokasi -->
                            <div class="sm:col-span-2 lg:col-span-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Lokasi <span class="text-red-500">*</span>
                                </label>
                                <select name="location_id" required 
                                    class="w-full px-4 py-2.5 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors {{ $errors->has('location_id') ? 'border-red-500' : 'border-gray-300' }}">
                                    <option value="">Pilih Lokasi</option>
                                    @foreach($locations as $location)
                                        <option value="{{ $location->id }}" {{ old('location_id', $asset->location_id) == $location->id ? 'selected' : '' }}>
                                            {{ $location->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('location_id')
                                    <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Garansi & Dokumen -->
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-200">
                            Garansi & Dokumen
                        </h2>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
                            <!-- Tanggal Garansi Berakhir -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Tanggal Garansi Berakhir
                                </label>
                                <input type="date" name="warranty_expiry_date" value="{{ old('warranty_expiry_date', $asset->warranty_expiry_date ? $asset->warranty_expiry_date->format('Y-m-d') : '') }}" 
                                    class="w-full px-4 py-2.5 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors {{ $errors->has('warranty_expiry_date') ? 'border-red-500' : 'border-gray-300' }}">
                                @error('warranty_expiry_date')
                                    <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- ✅ GANTI: Nomor Invoice (Text Input) -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Nomor Invoice
                                </label>
                                <input type="text" name="invoice_number" value="{{ old('invoice_number', $asset->invoice_number) }}" 
                                    placeholder="Contoh: INV/2025/001"
                                    class="w-full px-4 py-2.5 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors {{ $errors->has('invoice_number') ? 'border-red-500' : 'border-gray-300' }}">
                                @error('invoice_number')
                                    <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-1.5 text-xs text-gray-500">Opsional: Nomor invoice pembelian</p>
                            </div>

                            <!-- Foto -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Foto Perangkat
                                </label>
                                @if($asset->photo)
                                    <div class="mb-3">
                                        <img src="{{ asset('storage/' . $asset->photo) }}" alt="Current Photo" class="w-32 h-32 object-cover rounded-lg border-2 border-gray-200">
                                        <p class="mt-1 text-xs text-gray-500">Foto saat ini</p>
                                    </div>
                                @endif
                                <input type="file" name="photo" accept="image/*" 
                                    class="w-full px-4 py-2.5 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 {{ $errors->has('photo') ? 'border-red-500' : 'border-gray-300' }}">
                                @error('photo')
                                    <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-1.5 text-xs text-gray-500">Max: 2MB. Biarkan kosong jika tidak ingin mengubah.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="bg-gray-50 px-6 sm:px-8 py-4 border-t border-gray-200">
                    <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-3">
                        <a href="{{ route('assets.index') }}" 
                            class="w-full sm:w-auto px-6 py-2.5 text-center bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors font-medium">
                            Batal
                        </a>
                        <button type="submit" 
                            class="w-full sm:w-auto px-6 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors font-medium">
                            Update Aset
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>