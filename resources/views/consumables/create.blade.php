<x-app-layout>
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="mb-8">
            <div class="flex items-center gap-3 mb-2">
                <a href="{{ route('consumables.index') }}" class="inline-flex items-center text-gray-600 hover:text-gray-900 transition-colors">
                    <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Kembali
                </a>
            </div>
            <h1 class="text-2xl font-bold text-gray-900">Tambah Barang Habis Pakai</h1>
            <p class="mt-1 text-sm text-gray-600">Lengkapi form di bawah untuk menambahkan barang (ATK, reagen, dsb)</p>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <form action="{{ route('consumables.store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">
                            Nama Barang <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" class="block w-full px-4 py-3 bg-gray-50 border-2 border-gray-200 rounded-xl focus:ring-0 focus:border-blue-500 focus:bg-white transition-all text-gray-900" required>
                        @error('name')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="kategori" class="block text-sm font-semibold text-gray-700 mb-2">
                            Kategori
                        </label>
                        <select name="kategori" id="kategori" class="block w-full px-4 py-3 bg-gray-50 border-2 border-gray-200 rounded-xl focus:ring-0 focus:border-blue-500 focus:bg-white transition-all text-gray-900">
                            <option value="">Pilih Kategori...</option>
                            <option value="ATK" {{ old('kategori') == 'ATK' ? 'selected' : '' }}>ATK</option>
                            <option value="Reagen" {{ old('kategori') == 'Reagen' ? 'selected' : '' }}>Reagen</option>
                            <option value="Alat Kebersihan" {{ old('kategori') == 'Alat Kebersihan' ? 'selected' : '' }}>Alat Kebersihan</option>
                            <option value="Lainnya" {{ old('kategori') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                        </select>
                        @error('kategori')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="sumber_dana" class="block text-sm font-semibold text-gray-700 mb-2">
                            Sumber Dana
                        </label>
                        <select name="sumber_dana" id="sumber_dana" class="block w-full px-4 py-3 bg-gray-50 border-2 border-gray-200 rounded-xl focus:ring-0 focus:border-blue-500 focus:bg-white transition-all text-gray-900">
                            <option value="">Pilih Sumber Dana...</option>
                            <option value="Dana Sekolah" {{ old('sumber_dana') == 'Dana Sekolah' ? 'selected' : '' }}>Dana Sekolah</option>
                            <option value="Dana BOS" {{ old('sumber_dana') == 'Dana BOS' ? 'selected' : '' }}>Dana BOS</option>
                            <option value="Yayasan" {{ old('sumber_dana') == 'Yayasan' ? 'selected' : '' }}>Yayasan</option>
                            <option value="Lainnya" {{ old('sumber_dana') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                        </select>
                        @error('sumber_dana')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="price" class="block text-sm font-semibold text-gray-700 mb-2">
                            Harga Satuan (Rp)
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-500 font-medium">Rp</span>
                            <input type="number" name="price" id="price" value="{{ old('price') }}" 
                                placeholder="0" min="0" step="0.01"
                                class="block w-full pl-12 pr-4 py-3 bg-gray-50 border-2 border-gray-200 rounded-xl focus:ring-0 focus:border-blue-500 focus:bg-white transition-all text-gray-900">
                        </div>
                        @error('price')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="stock" class="block text-sm font-semibold text-gray-700 mb-2">
                            Stok Awal <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="stock" id="stock" min="0" value="{{ old('stock', 0) }}" class="block w-full px-4 py-3 bg-gray-50 border-2 border-gray-200 rounded-xl focus:ring-0 focus:border-blue-500 focus:bg-white transition-all text-gray-900" required>
                        @error('stock')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="unit" class="block text-sm font-semibold text-gray-700 mb-2">
                            Satuan <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="unit" id="unit" value="{{ old('unit', 'pcs') }}" placeholder="Contoh: pcs, box, rim" class="block w-full px-4 py-3 bg-gray-50 border-2 border-gray-200 rounded-xl focus:ring-0 focus:border-blue-500 focus:bg-white transition-all text-gray-900" required>
                        @error('unit')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label for="location_id" class="block text-sm font-semibold text-gray-700 mb-2">
                        Lokasi Penyimpanan
                    </label>
                    <select name="location_id" id="location_id" class="block w-full px-4 py-3 bg-gray-50 border-2 border-gray-200 rounded-xl focus:ring-0 focus:border-blue-500 focus:bg-white transition-all text-gray-900">
                        <option value="">Pilih Lokasi...</option>
                        @foreach($locations as $location)
                            <option value="{{ $location->id }}" {{ old('location_id') == $location->id ? 'selected' : '' }}>
                                {{ $location->name }} (Lantai {{ $location->floor }})
                            </option>
                        @endforeach
                    </select>
                    @error('location_id')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="photo" class="block text-sm font-semibold text-gray-700 mb-2">
                        Foto Barang (Opsional)
                    </label>
                    <input type="file" name="photo" id="photo" accept="image/*" class="block w-full px-4 py-2 border-2 border-gray-200 rounded-xl bg-gray-50">
                    <p class="mt-1 text-xs text-gray-500">Maksimal 2MB, format gambar (JPG/PNG).</p>
                    @error('photo')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">Deskripsi</label>
                    <textarea name="description" id="description" rows="3" class="block w-full px-4 py-3 bg-gray-50 border-2 border-gray-200 rounded-xl focus:ring-0 focus:border-blue-500 focus:bg-white transition-all text-gray-900" placeholder="Keterangan tambahan...">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-200">
                    <a href="{{ route('consumables.index') }}" class="px-6 py-2.5 bg-white text-gray-700 rounded-lg hover:bg-gray-50 transition-all text-sm font-medium border border-gray-300">
                        Batal
                    </a>
                    <button type="submit" class="px-6 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-all text-sm font-medium shadow-sm hover:shadow-md">
                        Simpan Barang
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
