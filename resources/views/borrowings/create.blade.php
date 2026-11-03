<x-app-layout :title="'Catat Peminjaman Aset'">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <!-- Header Halaman -->
        <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center gap-3">
                <a href="{{ route('borrowings.index') }}" class="p-2 text-gray-500 hover:text-gray-800 hover:bg-gray-100 rounded-xl border border-gray-200 transition-colors shadow-sm" title="Kembali">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                </a>
                <div>
                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 tracking-tight">Catat Peminjaman Aset</h1>
                    <p class="text-xs sm:text-sm text-gray-500 mt-1">Lengkapi data untuk mencatat aset yang sedang dipinjam</p>
                </div>
            </div>
            <a href="{{ route('borrowings.index') }}" class="inline-flex items-center justify-center px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-sm font-semibold transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali
            </a>
        </div>

        <!-- Kartu Formulir Utama -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <form action="{{ route('borrowings.store') }}" method="POST" enctype="multipart/form-data" class="p-6 sm:p-8 space-y-6">
                @csrf

                <!-- Banner Informasi Status -->
                <div class="p-4 bg-blue-50/70 border border-blue-200 rounded-xl flex items-start gap-3">
                    <div class="w-8 h-8 rounded-lg bg-blue-600 text-white flex items-center justify-center flex-shrink-0 mt-0.5 shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="text-xs text-blue-900 leading-relaxed">
                        <span class="font-bold text-sm text-blue-950 block mb-0.5">Sistem Peminjaman Langsung Aktif</span>
                        Peminjaman yang dicatat akan langsung berstatus <strong class="text-blue-700">Dipinjam</strong>. Aset yang sedang dipinjam secara otomatis terkunci dan tidak dapat dipinjam oleh pengguna lain sampai dikembalikan.
                    </div>
                </div>

                <!-- Grid Formulir -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Pilih Aset (Full Width) -->
                    <div class="md:col-span-2">
                        <label for="asset_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Pilih Unit Aset <span class="text-red-500">*</span>
                        </label>
                        @if($assets->isEmpty())
                            <div class="p-4 bg-amber-50 border border-amber-200 rounded-xl text-sm text-amber-800">
                                Saat ini tidak ada unit aset yang tersedia (semua aset sedang dipinjam atau berstatus non-aktif).
                            </div>
                        @else
                            <select name="asset_id" id="asset_id" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors bg-white text-gray-900 text-sm" required>
                                <option value="" disabled selected>-- Pilih Aset yang Akan Dipinjam --</option>
                                @foreach($assets as $asset)
                                    <option value="{{ $asset->id }}" {{ old('asset_id') == $asset->id ? 'selected' : '' }}>
                                        {{ $asset->asset_code }} — {{ $asset->brand }} {{ $asset->model }} (Ruangan: {{ $asset->location->name ?? 'Umum' }})
                                    </option>
                                @endforeach
                            </select>
                        @endif
                        @error('asset_id')
                            <p class="mt-1.5 text-xs text-red-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tanggal Pinjam -->
                    <div>
                        <label for="borrow_date" class="block text-sm font-medium text-gray-700 mb-2">
                            Tanggal Pinjam <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="borrow_date" id="borrow_date" value="{{ old('borrow_date', date('Y-m-d')) }}" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors bg-white text-gray-900 text-sm" required min="{{ date('Y-m-d') }}">
                        @error('borrow_date')
                            <p class="mt-1.5 text-xs text-red-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Rencana Kembali -->
                    <div>
                        <label for="expected_return_date" class="block text-sm font-medium text-gray-700 mb-2">
                            Rencana Kembali <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="expected_return_date" id="expected_return_date" value="{{ old('expected_return_date', date('Y-m-d', strtotime('+1 day'))) }}" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors bg-white text-gray-900 text-sm" required min="{{ date('Y-m-d') }}">
                        @error('expected_return_date')
                            <p class="mt-1.5 text-xs text-red-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Catatan Keperluan (Full Width) -->
                    <div class="md:col-span-2">
                        <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Catatan / Keperluan Peminjaman</label>
                        <textarea name="notes" id="notes" rows="3" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors bg-white text-gray-900 text-sm" placeholder="Tuliskan tujuan peminjaman atau keterangan kondisi awal barang...">{{ old('notes') }}</textarea>
                        @error('notes')
                            <p class="mt-1.5 text-xs text-red-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Foto Bukti Peminjaman (Full Width) -->
                    <div class="md:col-span-2">
                        <x-camera-capture 
                            id="borrow-photo" 
                            name="borrow_photo" 
                            label="Foto Bukti Fisik / Serah Terima Barang" 
                            helperText="Ambil foto unit aset via webcam/kamera laptop/HP atau pilih berkas gambar dari perangkat."
                        />
                        @error('borrow_photo')
                            <p class="mt-1.5 text-xs text-red-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Footer Tombol Aksi -->
                <div class="flex items-center justify-end gap-3 pt-6 border-t border-gray-200">
                    <a href="{{ route('borrowings.index') }}" class="px-6 py-2.5 bg-white text-gray-700 rounded-lg hover:bg-gray-50 transition-all text-sm font-medium border border-gray-300">
                        Batal
                    </a>
                    <button type="submit" @if($assets->isEmpty()) disabled @endif class="px-6 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed transition-all text-sm font-medium shadow-sm hover:shadow-md">
                        Simpan Peminjaman
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
