<x-app-layout :title="'Edit Barang Habis Pakai ' . $consumable->name">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <!-- Header -->
        <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center gap-3">
                <a href="{{ route('consumables.index') }}" class="p-2 text-gray-500 hover:text-gray-800 hover:bg-gray-100 rounded-xl border border-gray-200 transition-colors shadow-sm" title="Kembali">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                </a>
                <div>
                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 tracking-tight">Edit Barang Habis Pakai</h1>
                    <p class="text-xs sm:text-sm text-gray-500 mt-1">Perbarui informasi barang: {{ $consumable->name }}</p>
                </div>
            </div>
            <a href="{{ route('consumables.index') }}" class="inline-flex items-center justify-center px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-sm font-semibold transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali
            </a>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <form action="{{ route('consumables.update', $consumable) }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
                @csrf
                @method('PATCH')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">
                            Nama Barang <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="name" id="name" value="{{ old('name', $consumable->name) }}" class="block w-full px-4 py-3 bg-gray-50 border-2 border-gray-200 rounded-xl focus:ring-0 focus:border-blue-500 focus:bg-white transition-all text-gray-900" required>
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
                            <option value="ATK" {{ old('kategori', $consumable->kategori) == 'ATK' ? 'selected' : '' }}>ATK</option>
                            <option value="Reagen" {{ old('kategori', $consumable->kategori) == 'Reagen' ? 'selected' : '' }}>Reagen</option>
                            <option value="Alat Kebersihan" {{ old('kategori', $consumable->kategori) == 'Alat Kebersihan' ? 'selected' : '' }}>Alat Kebersihan</option>
                            <option value="Lainnya" {{ old('kategori', $consumable->kategori) == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
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
                            <option value="Dana Sekolah" {{ old('sumber_dana', $consumable->sumber_dana) == 'Dana Sekolah' ? 'selected' : '' }}>Dana Sekolah</option>
                            <option value="Dana BOS" {{ old('sumber_dana', $consumable->sumber_dana) == 'Dana BOS' ? 'selected' : '' }}>Dana BOS</option>
                            <option value="Yayasan" {{ old('sumber_dana', $consumable->sumber_dana) == 'Yayasan' ? 'selected' : '' }}>Yayasan</option>
                            <option value="Lainnya" {{ old('sumber_dana', $consumable->sumber_dana) == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
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
                            <input type="number" name="price" id="price" value="{{ old('price', $consumable->price) }}" 
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
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Stok Saat Ini
                        </label>
                        <input type="text" value="{{ $consumable->stock }} {{ $consumable->unit }}" class="block w-full px-4 py-3 bg-gray-100 border-2 border-gray-200 rounded-xl text-gray-600 cursor-not-allowed" disabled>
                        <p class="mt-1 text-xs text-gray-500">Gunakan menu Transaksi untuk menambah/mengurangi stok.</p>
                    </div>

                    <div>
                        <label for="unit" class="block text-sm font-semibold text-gray-700 mb-2">
                            Satuan <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="unit" id="unit" value="{{ old('unit', $consumable->unit) }}" placeholder="Contoh: pcs, box, rim" class="block w-full px-4 py-3 bg-gray-50 border-2 border-gray-200 rounded-xl focus:ring-0 focus:border-blue-500 focus:bg-white transition-all text-gray-900" required>
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
                            <option value="{{ $location->id }}" {{ old('location_id', $consumable->location_id) == $location->id ? 'selected' : '' }}>
                                {{ $location->name }} (Lantai {{ $location->floor }})
                            </option>
                        @endforeach
                    </select>
                    @error('location_id')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Foto Barang (Modern Dropzone & Delete Preview) -->
                <div x-data="{
                    hasExistingPhoto: {{ $consumable->photo ? 'true' : 'false' }},
                    existingPhotoUrl: '{{ $consumable->photo ? asset('storage/' . $consumable->photo) : '' }}',
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
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Foto Barang</label>
                    <input type="file" name="photo" id="photo" x-ref="photoInput" @change="handleFileSelect" accept="image/*" class="hidden">
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
                                        <p class="text-sm font-bold text-gray-800">Foto Barang Tersimpan</p>
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
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">Deskripsi</label>
                    <textarea name="description" id="description" rows="3" class="block w-full px-4 py-3 bg-gray-50 border-2 border-gray-200 rounded-xl focus:ring-0 focus:border-blue-500 focus:bg-white transition-all text-gray-900" placeholder="Keterangan tambahan...">{{ old('description', $consumable->description) }}</textarea>
                    @error('description')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-200">
                    <a href="{{ route('consumables.index') }}" class="px-6 py-2.5 bg-white text-gray-700 rounded-lg hover:bg-gray-50 transition-all text-sm font-medium border border-gray-300">
                        Batal
                    </a>
                    <button type="submit" class="px-6 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-all text-sm font-medium shadow-sm hover:shadow-md">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
