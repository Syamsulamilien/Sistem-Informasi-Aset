<x-app-layout>
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="mb-8">
            <div class="flex items-center gap-3 mb-2">
                <a href="{{ route('borrowings.index') }}" class="inline-flex items-center text-gray-600 hover:text-gray-900 transition-colors">
                    <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Kembali
                </a>
            </div>
            <h1 class="text-2xl font-bold text-gray-900">Update Status Peminjaman</h1>
            <p class="mt-1 text-sm text-gray-600">Perbarui status peminjaman aset</p>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="p-6 border-b border-gray-200 bg-gray-50">
                <h3 class="font-semibold text-gray-900 mb-2">Informasi Peminjaman</h3>
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <span class="text-gray-500">Aset:</span>
                        <p class="font-medium text-gray-900">{{ $borrowing->asset->brand }} - {{ $borrowing->asset->model }} ({{ $borrowing->asset->asset_code }})</p>
                    </div>
                    <div>
                        <span class="text-gray-500">Peminjam:</span>
                        <p class="font-medium text-gray-900">{{ $borrowing->user->name }}</p>
                    </div>
                    <div>
                        <span class="text-gray-500">Tanggal Pinjam:</span>
                        <p class="font-medium text-gray-900">{{ $borrowing->borrow_date->format('d M Y') }}</p>
                    </div>
                    <div>
                        <span class="text-gray-500">Rencana Kembali:</span>
                        <p class="font-medium text-gray-900">{{ $borrowing->expected_return_date->format('d M Y') }}</p>
                    </div>
                </div>
            </div>

            <form action="{{ route('borrowings.update', $borrowing) }}" method="POST" class="p-6 space-y-6">
                @csrf
                @method('PATCH')

                <div>
                    <label for="status" class="block text-sm font-semibold text-gray-700 mb-2">
                        Status <span class="text-red-500">*</span>
                    </label>
                    <select name="status" id="status" class="block w-full px-4 py-3 bg-gray-50 border-2 border-gray-200 rounded-xl focus:ring-0 focus:border-blue-500 focus:bg-white transition-all text-gray-900" required>
                        <option value="pending" {{ old('status', $borrowing->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ old('status', $borrowing->status) == 'approved' ? 'selected' : '' }}>Disetujui</option>
                        <option value="rejected" {{ old('status', $borrowing->status) == 'rejected' ? 'selected' : '' }}>Ditolak</option>
                        <option value="borrowed" {{ old('status', $borrowing->status) == 'borrowed' ? 'selected' : '' }}>Dipinjam</option>
                        <option value="returned" {{ old('status', $borrowing->status) == 'returned' ? 'selected' : '' }}>Dikembalikan</option>
                    </select>
                    @error('status')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="actual_return_date" class="block text-sm font-semibold text-gray-700 mb-2">
                        Tanggal Kembali Aktual
                    </label>
                    <input type="date" name="actual_return_date" id="actual_return_date" value="{{ old('actual_return_date', $borrowing->actual_return_date ? $borrowing->actual_return_date->format('Y-m-d') : '') }}" class="block w-full px-4 py-3 bg-gray-50 border-2 border-gray-200 rounded-xl focus:ring-0 focus:border-blue-500 focus:bg-white transition-all text-gray-900">
                    <p class="mt-1 text-xs text-gray-500">Opsional, akan otomatis terisi saat status diubah menjadi "Dikembalikan".</p>
                    @error('actual_return_date')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="notes" class="block text-sm font-semibold text-gray-700 mb-2">Catatan Tambahan</label>
                    <textarea name="notes" id="notes" rows="4" class="block w-full px-4 py-3 bg-gray-50 border-2 border-gray-200 rounded-xl focus:ring-0 focus:border-blue-500 focus:bg-white transition-all text-gray-900" placeholder="Catatan opsional dari admin/laboran...">{{ old('notes', $borrowing->notes) }}</textarea>
                    @error('notes')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-200">
                    <a href="{{ route('borrowings.index') }}" class="px-6 py-2.5 bg-white text-gray-700 rounded-lg hover:bg-gray-50 transition-all text-sm font-medium border border-gray-300">
                        Batal
                    </a>
                    <button type="submit" class="px-6 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-all text-sm font-medium shadow-sm hover:shadow-md">
                        Update Status
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
