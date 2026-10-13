<x-app-layout :title="'Edit Aset ' . $asset->asset_code">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <!-- Header -->
        <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center gap-3">
                <a href="{{ route('assets.show', $asset) }}" class="p-2 text-gray-500 hover:text-gray-800 hover:bg-gray-100 rounded-xl border border-gray-200 transition-colors shadow-sm" title="Kembali">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                </a>
                <div>
                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 tracking-tight">Edit Aset</h1>
                    <p class="text-xs sm:text-sm text-gray-500 mt-1">Update informasi aset: {{ $asset->asset_code }}</p>
                </div>
            </div>
            <a href="{{ route('assets.show', $asset) }}" class="inline-flex items-center justify-center px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-sm font-semibold transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali ke Detail
            </a>
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

                            <!-- Kategori Asset (Read-only) -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Kategori <span class="text-red-500">*</span>
                                </label>
                                <input type="hidden" name="kategori" value="{{ old('kategori', $asset->kategori) }}">
                                @php
                                    $badgeColors = [
                                        'Asset TI' => 'bg-purple-100 border-purple-300 text-purple-800',
                                        'Asset Rumah Tangga' => 'bg-green-100 border-green-300 text-green-800',
                                        'Asset Transportasi' => 'bg-orange-100 border-orange-300 text-orange-800',
                                        'Asset Gizi' => 'bg-pink-100 border-pink-300 text-pink-800',
                                        'Asset Lainnya' => 'bg-gray-100 border-gray-300 text-gray-800',
                                    ];
                                    $colorClass = $badgeColors[$asset->kategori] ?? 'bg-gray-100 border-gray-300 text-gray-800';
                                @endphp
                                <input type="text" value="{{ old('kategori', $asset->kategori) }}" disabled
                                    class="w-full px-4 py-2.5 border-2 rounded-lg cursor-not-allowed font-medium {{ $colorClass }}">
                                <p class="mt-1.5 text-xs text-gray-500">Kategori tidak dapat diubah (mengikuti jenis aset)</p>
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
                                    Nomor Seri
                                </label>
                                <input type="text" name="serial_number" value="{{ old('serial_number', $asset->serial_number) }}"
                                    placeholder="Opsional: biarkan kosong jika tidak ada"
                                    class="w-full px-4 py-2.5 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors {{ $errors->has('serial_number') ? 'border-red-500' : 'border-gray-300' }}">
                                @error('serial_number')
                                    <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-1.5 text-xs text-gray-500">Opsional: Biarkan kosong jika tidak ada serial number</p>
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
                                    placeholder="Ketik 0 jika tidak diketahui atau {{ date('Y') }}"
                                    class="w-full px-4 py-2.5 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors {{ $errors->has('purchase_year') ? 'border-red-500' : 'border-gray-300' }}">
                                @error('purchase_year')
                                    <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-1.5 text-xs text-gray-500">
                                    💡 Ketik <strong class="font-semibold">0</strong> jika tahun tidak diketahui, atau tahun antara 1900 - {{ date('Y') + 1 }}
                                </p>
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
                                            placeholder="0" step="0.01">
                                    </div>
                                    @error('price')
                                        <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                    <p class="mt-1.5 text-xs text-gray-500">Masukkan harga tanpa titik</p>
                                </div>

                                <!-- Sumber Dana -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Sumber Dana
                                    </label>
                                    <select name="sumber_dana" class="w-full px-4 py-2.5 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors {{ $errors->has('sumber_dana') ? 'border-red-500' : 'border-gray-300' }}">
                                        <option value="">Pilih Sumber Dana</option>
                                        <option value="Dana Sekolah" {{ old('sumber_dana', $asset->sumber_dana) == 'Dana Sekolah' ? 'selected' : '' }}>Dana Sekolah</option>
                                        <option value="Dana BOS" {{ old('sumber_dana', $asset->sumber_dana) == 'Dana BOS' ? 'selected' : '' }}>Dana BOS</option>
                                        <option value="Yayasan" {{ old('sumber_dana', $asset->sumber_dana) == 'Yayasan' ? 'selected' : '' }}>Yayasan</option>
                                        <option value="Lainnya" {{ old('sumber_dana', $asset->sumber_dana) == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                                    </select>
                                    @error('sumber_dana')
                                        <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
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

                    <!-- Informasi Tambahan -->
                    <div class="mb-8">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-200">
                            Informasi Tambahan
                        </h2>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
                            <!-- Penanggung Jawab -->
                            <div class="sm:col-span-2 lg:col-span-3">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Penanggung Jawab
                                </label>
                                <input type="text" name="penanggung_jawab" value="{{ old('penanggung_jawab', $asset->penanggung_jawab) }}"
                                    placeholder="Masukkan nama penanggung jawab"
                                    class="w-full px-4 py-2.5 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors {{ $errors->has('penanggung_jawab') ? 'border-red-500' : 'border-gray-300' }}">
                                @error('penanggung_jawab')
                                    <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-1.5 text-xs text-gray-500">Opsional: Nama orang yang bertanggung jawab atas aset ini</p>
                            </div>

                            <!-- Intensitas Pemakaian -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Intensitas Pemakaian
                                </label>
                                <input type="text" name="intensitas_pemakaian"
                                    value="{{ old('intensitas_pemakaian', $asset->intensitas_pemakaian) }}"
                                    placeholder="Contoh: Rendah, Sedang, Tinggi"
                                    class="w-full px-4 py-2.5 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors {{ $errors->has('intensitas_pemakaian') ? 'border-red-500' : 'border-gray-300' }}">
                                @error('intensitas_pemakaian')
                                    <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-1.5 text-xs text-gray-500">Opsional: Seberapa sering aset digunakan</p>
                            </div>

                            <!-- Masa Pemakaian -->
                            <div class="sm:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Masa Pemakaian
                                </label>
                                <div class="flex gap-2">
                                    <input type="number" name="masa_pemakaian"
                                        value="{{ old('masa_pemakaian', $asset->masa_pemakaian) }}"
                                        placeholder="0" min="0"
                                        class="w-2/3 px-4 py-2.5 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors {{ $errors->has('masa_pemakaian') ? 'border-red-500' : 'border-gray-300' }}">

                                    <select name="masa_pemakaian_satuan"
                                        class="w-1/3 px-4 py-2.5 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors {{ $errors->has('masa_pemakaian_satuan') ? 'border-red-500' : 'border-gray-300' }}">
                                        <option value="Bulan" {{ old('masa_pemakaian_satuan', $asset->masa_pemakaian_satuan ?? 'Bulan') == 'Bulan' ? 'selected' : '' }}>Bulan</option>
                                        <option value="Tahun" {{ old('masa_pemakaian_satuan', $asset->masa_pemakaian_satuan ?? 'Bulan') == 'Tahun' ? 'selected' : '' }}>Tahun</option>
                                    </select>
                                </div>
                                @error('masa_pemakaian')
                                    <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                @error('masa_pemakaian_satuan')
                                    <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-1.5 text-xs text-gray-500">Opsional: Berapa lama aset sudah digunakan</p>
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

                            <!-- Foto Perangkat (Modern Dropzone & Delete Preview) -->
                            <div class="sm:col-span-2 lg:col-span-3" x-data="{
                                hasExistingPhoto: {{ $asset->photo ? 'true' : 'false' }},
                                existingPhotoUrl: '{{ $asset->photo ? asset('storage/' . $asset->photo) : '' }}',
                                removePhoto: false,
                                newPreviewUrl: null,
                                newFileName: '',
                                newFileSize: '',
                                isDragging: false,
                                handleFileSelect(e) {
                                    const file = e.target.files[0];
                                    if (file) this.processFile(file);
                                },
                                handleFileDrop(e) {
                                    this.isDragging = false;
                                    const file = e.dataTransfer.files[0];
                                    if (file) {
                                        this.$refs.photoInput.files = e.dataTransfer.files;
                                        this.processFile(file);
                                    }
                                },
                                processFile(file) {
                                    if (!file.type.startsWith('image/')) {
                                        Swal.fire({
                                            icon: 'warning',
                                            title: 'Format Tidak Sesuai',
                                            text: 'Mohon unggah file format gambar (JPG, PNG, WEBP, GIF).'
                                        });
                                        this.resetNewPhoto();
                                        return;
                                    }
                                    if (file.size > 2 * 1024 * 1024) {
                                        Swal.fire({
                                            icon: 'warning',
                                            title: 'Ukuran Terlalu Besar',
                                            text: 'Ukuran gambar maksimal adalah 2MB.'
                                        });
                                        this.resetNewPhoto();
                                        return;
                                    }
                                    this.newFileName = file.name;
                                    this.newFileSize = (file.size / (1024 * 1024)).toFixed(2) + ' MB';
                                    this.removePhoto = false;
                                    const reader = new FileReader();
                                    reader.onload = (e) => {
                                        this.newPreviewUrl = e.target.result;
                                    };
                                    reader.readAsDataURL(file);
                                },
                                markRemoveExisting() {
                                    this.removePhoto = true;
                                    this.resetNewPhoto();
                                },
                                cancelRemoveExisting() {
                                    this.removePhoto = false;
                                },
                                resetNewPhoto() {
                                    this.newPreviewUrl = null;
                                    this.newFileName = '';
                                    this.newFileSize = '';
                                    if (this.$refs.photoInput) {
                                        this.$refs.photoInput.value = '';
                                    }
                                }
                            }">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Foto Perangkat
                                </label>
                                <input type="file" name="photo" x-ref="photoInput" @change="handleFileSelect" accept="image/*" class="hidden">
                                <input type="hidden" name="remove_photo" :value="removePhoto ? '1' : '0'">

                                <!-- Kasus A: Ada Foto Baru yang Sedang Dipilih -->
                                <div x-show="newPreviewUrl" x-cloak class="p-4 bg-white border border-gray-200 rounded-2xl shadow-sm flex flex-col sm:flex-row items-center justify-between gap-4">
                                    <div class="flex items-center gap-4 w-full sm:w-auto">
                                        <div class="relative w-20 h-20 sm:w-24 sm:h-24 rounded-xl overflow-hidden border border-gray-200 bg-gray-100 flex-shrink-0 shadow-sm">
                                            <img :src="newPreviewUrl" alt="Preview Foto Baru" class="w-full h-full object-cover">
                                        </div>
                                        <div class="min-w-0">
                                            <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200 mb-1">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                                Foto Baru Terpilih
                                            </div>
                                            <p class="text-sm font-bold text-gray-800 truncate" x-text="newFileName"></p>
                                            <p class="text-xs text-gray-400 mt-0.5" x-text="newFileSize"></p>
                                        </div>
                                    </div>

                                    <div class="flex items-center gap-2 w-full sm:w-auto justify-end">
                                        <button type="button" @click="$refs.photoInput.click()" 
                                                class="px-3.5 py-2 text-xs font-semibold text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-xl transition-colors inline-flex items-center gap-1.5">
                                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                            </svg>
                                            Ganti
                                        </button>
                                        <button type="button" @click="resetNewPhoto" 
                                                class="px-3.5 py-2 text-xs font-bold text-rose-600 bg-rose-50 hover:bg-rose-100 rounded-xl transition-colors inline-flex items-center gap-1.5">
                                            <svg class="w-4 h-4 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                            Batalkan
                                        </button>
                                    </div>
                                </div>

                                <!-- Kasus B: Tidak Ada Foto Baru, Menampilkan Foto Eksisting -->
                                <template x-if="!newPreviewUrl && hasExistingPhoto">
                                    <div>
                                        <!-- Kondisi Normal (Foto Ada & Belum Dihapus) -->
                                        <div x-show="!removePhoto" class="p-4 bg-white border border-gray-200 rounded-2xl shadow-sm flex flex-col sm:flex-row items-center justify-between gap-4">
                                            <div class="flex items-center gap-4 w-full sm:w-auto">
                                                <div class="relative w-20 h-20 sm:w-24 sm:h-24 rounded-xl overflow-hidden border border-gray-200 bg-gray-100 flex-shrink-0 shadow-sm">
                                                    <img :src="existingPhotoUrl" alt="Foto Saat Ini" class="w-full h-full object-cover">
                                                </div>
                                                <div class="min-w-0">
                                                    <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-200 mb-1">
                                                        <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                                                        Foto Saat Ini
                                                    </div>
                                                    <p class="text-sm font-bold text-gray-800">Foto Tersimpan</p>
                                                    <p class="text-xs text-gray-400 mt-0.5">Biarkan jika tidak ingin mengubah</p>
                                                </div>
                                            </div>

                                            <div class="flex items-center gap-2 w-full sm:w-auto justify-end">
                                                <button type="button" @click="$refs.photoInput.click()" 
                                                        class="px-3.5 py-2 text-xs font-semibold text-blue-700 bg-blue-50 hover:bg-blue-100 rounded-xl transition-colors inline-flex items-center gap-1.5">
                                                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                                    </svg>
                                                    Ganti Foto
                                                </button>
                                                <button type="button" @click="markRemoveExisting" 
                                                        class="px-3.5 py-2 text-xs font-bold text-rose-600 bg-rose-50 hover:bg-rose-100 rounded-xl transition-colors inline-flex items-center gap-1.5">
                                                    <svg class="w-4 h-4 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                                    Hapus Foto
                                                </button>
                                            </div>
                                        </div>

                                        <!-- Kondisi Ditandai Hapus -->
                                        <div x-show="removePhoto" x-cloak class="p-4 bg-rose-50/70 border border-rose-200 rounded-2xl flex flex-col sm:flex-row items-center justify-between gap-4">
                                            <div class="flex items-center gap-3">
                                                <div class="w-10 h-10 rounded-xl bg-rose-100 text-rose-600 flex items-center justify-center flex-shrink-0">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                                </div>
                                                <div>
                                                    <p class="text-sm font-bold text-rose-800">Foto Ditandai Akan Dihapus</p>
                                                    <p class="text-xs text-rose-600 mt-0.5">Foto akan dihapus permanen saat form disimpan.</p>
                                                </div>
                                            </div>
                                            <div class="flex items-center gap-2">
                                                <button type="button" @click="cancelRemoveExisting" 
                                                        class="px-3.5 py-2 text-xs font-semibold text-gray-700 bg-white hover:bg-gray-100 border border-gray-300 rounded-xl transition-colors">
                                                    Batalkan Hapus
                                                </button>
                                                <button type="button" @click="$refs.photoInput.click()" 
                                                        class="px-3.5 py-2 text-xs font-semibold text-blue-700 bg-blue-100 hover:bg-blue-200 rounded-xl transition-colors">
                                                    Pilih Foto Baru
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </template>

                                <!-- Kasus C: Dropzone (Jika tidak ada foto atau foto eksis ditandai hapus dan ingin upload baru) -->
                                <div x-show="!newPreviewUrl && (!hasExistingPhoto || removePhoto)" 
                                     @dragover.prevent="isDragging = true"
                                     @dragleave.prevent="isDragging = false"
                                     @drop.prevent="handleFileDrop"
                                     @click="$refs.photoInput.click()"
                                     :class="isDragging ? 'border-blue-500 bg-blue-50/70 scale-[1.008]' : 'border-gray-300 bg-gray-50/60 hover:bg-blue-50/30 hover:border-blue-400'"
                                     class="border-2 border-dashed rounded-2xl p-6 sm:p-8 text-center cursor-pointer transition-all duration-200 group mt-3">
                                    <div class="mx-auto w-12 h-12 rounded-2xl bg-blue-50 group-hover:bg-blue-100/80 flex items-center justify-center text-blue-600 mb-3 transition-colors shadow-sm">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                    <p class="text-sm font-semibold text-gray-700 group-hover:text-blue-600 transition-colors">
                                        Klik untuk memilih gambar <span class="font-normal text-gray-500">atau seret & lepas ke sini</span>
                                    </p>
                                    <p class="text-xs text-gray-400 mt-1">
                                        PNG, JPG, JPEG, atau WEBP (Maksimal 2MB)
                                    </p>
                                </div>

                                @error('photo')
                                    <p class="mt-1.5 text-sm text-red-600 font-medium">{{ $message }}</p>
                                @enderror
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