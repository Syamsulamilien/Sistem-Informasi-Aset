<x-app-layout :title="'Update Status Peminjaman - ' . $borrowing->user->name">
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
                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 tracking-tight">Update Status Peminjaman</h1>
                    <p class="text-xs sm:text-sm text-gray-500 mt-1">Perbarui status barang menjadi <span class="font-semibold text-emerald-600">Dikembalikan</span> atau kelola foto bukti fisik</p>
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
            <!-- Informasi Singkat Transaksi -->
            <div class="p-6 border-b border-gray-200 bg-gray-50">
                <h3 class="font-semibold text-gray-900 mb-3 text-sm">Informasi Transaksi Peminjaman</h3>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 text-xs sm:text-sm">
                    <div>
                        <span class="text-gray-500 block text-xs">Unit Aset:</span>
                        <p class="font-medium text-gray-900 mt-0.5">{{ $borrowing->asset->brand }} {{ $borrowing->asset->model }}</p>
                        <p class="text-[11px] font-mono text-gray-500">({{ $borrowing->asset->asset_code }})</p>
                    </div>
                    <div>
                        <span class="text-gray-500 block text-xs">Peminjam:</span>
                        <p class="font-medium text-gray-900 mt-0.5">{{ $borrowing->user->name }}</p>
                        <p class="text-[11px] text-gray-500">{{ ucfirst($borrowing->user->role) }}</p>
                    </div>
                    <div>
                        <span class="text-gray-500 block text-xs">Tanggal Pinjam:</span>
                        <p class="font-medium text-gray-900 mt-0.5">{{ $borrowing->borrow_date->format('d M Y') }}</p>
                    </div>
                    <div>
                        <span class="text-gray-500 block text-xs">Rencana Kembali:</span>
                        <p class="font-medium text-gray-900 mt-0.5">{{ $borrowing->expected_return_date->format('d M Y') }}</p>
                    </div>
                </div>
            </div>

            <!-- Form Edit -->
            <form action="{{ route('borrowings.update', $borrowing) }}" method="POST" enctype="multipart/form-data" class="p-6 sm:p-8 space-y-6">
                @csrf
                @method('PATCH')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Status Peminjaman -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                            Status Peminjaman <span class="text-red-500">*</span>
                        </label>
                        <select name="status" id="status" onchange="toggleReturnDateField(this.value)" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors bg-white text-gray-900 text-sm font-medium" required>
                            <option value="borrowed" {{ old('status', $borrowing->status) == 'borrowed' ? 'selected' : '' }}>🔵 Dipinjam (Barang masih dipinjam)</option>
                            <option value="returned" {{ old('status', $borrowing->status) == 'returned' ? 'selected' : '' }}>🟢 Dikembalikan (Barang sudah dikembalikan)</option>
                        </select>
                        @error('status')
                            <p class="mt-1.5 text-xs text-red-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tanggal Pengembalian Aktual -->
                    <div>
                        <label for="actual_return_date" class="block text-sm font-medium text-gray-700 mb-2">
                            Tanggal Kembali Aktual
                        </label>
                        <input type="date" name="actual_return_date" id="actual_return_date" value="{{ old('actual_return_date', $borrowing->actual_return_date ? $borrowing->actual_return_date->format('Y-m-d') : date('Y-m-d')) }}" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors bg-white text-gray-900 text-sm font-medium">
                        <p class="mt-1 text-[11px] text-gray-500" id="return-date-hint">Otomatis terisi saat status diubah menjadi "Dikembalikan".</p>
                        @error('actual_return_date')
                            <p class="mt-1.5 text-xs text-red-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Catatan Tambahan (Full Width) -->
                    <div class="md:col-span-2">
                        <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Catatan Tambahan</label>
                        <textarea name="notes" id="notes" rows="3" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors bg-white text-gray-900 text-sm" placeholder="Catatan kondisi saat pengembalian atau keterangan lainnya...">{{ old('notes', $borrowing->notes) }}</textarea>
                        @error('notes')
                            <p class="mt-1.5 text-xs text-red-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Bukti Foto Peminjaman (50% Kiri) -->
                    <div class="col-span-1">
                        <x-camera-capture 
                            id="edit-borrow-photo" 
                            name="borrow_photo" 
                            label="Foto Bukti Peminjaman" 
                            :currentPhoto="$borrowing->borrow_photo" 
                            removeName="remove_borrow_photo" 
                            helperText="Foto kondisi awal saat barang diserahkan."
                        />
                    </div>

                    <!-- Bukti Foto Pengembalian (50% Kanan) -->
                    <div class="col-span-1">
                        <x-camera-capture 
                            id="edit-return-photo" 
                            name="return_photo" 
                            label="Foto Bukti Pengembalian" 
                            :currentPhoto="$borrowing->return_photo" 
                            removeName="remove_return_photo" 
                            helperText="Foto kondisi barang saat diterima kembali."
                        />
                    </div>
                </div>

                <!-- Footer Tombol Aksi -->
                <div class="flex items-center justify-end gap-3 pt-6 border-t border-gray-200">
                    <a href="{{ route('borrowings.index') }}" class="px-6 py-2.5 bg-white text-gray-700 rounded-lg hover:bg-gray-50 transition-all text-sm font-medium border border-gray-300">
                        Batal
                    </a>
                    <button type="submit" class="px-6 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-all text-sm font-medium shadow-sm hover:shadow-md">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
    function toggleReturnDateField(status) {
        const dateInput = document.getElementById('actual_return_date');
        const hint = document.getElementById('return-date-hint');
        if (status === 'returned') {
            if (!dateInput.value) {
                dateInput.value = new Date().toISOString().split('T')[0];
            }
            hint.innerText = 'Tanggal pengembalian barang tercatat.';
            hint.className = 'mt-1 text-[11px] text-emerald-600 font-medium';
        } else {
            hint.innerText = 'Otomatis terisi saat status diubah menjadi "Dikembalikan".';
            hint.className = 'mt-1 text-[11px] text-gray-500';
        }
    }
    </script>
</x-app-layout>
