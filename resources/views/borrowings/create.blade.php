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
            <h1 class="text-2xl font-bold text-gray-900">Ajukan Peminjaman Aset</h1>
            <p class="mt-1 text-sm text-gray-600">Isi form di bawah untuk meminjam aset</p>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <form action="{{ route('borrowings.store') }}" method="POST" class="p-6 space-y-6">
                @csrf

                <div>
                    <label for="asset_id" class="block text-sm font-semibold text-gray-700 mb-2">
                        Pilih Aset <span class="text-red-500">*</span>
                    </label>
                    <select name="asset_id" id="asset_id" class="block w-full px-4 py-3 bg-gray-50 border-2 border-gray-200 rounded-xl focus:ring-0 focus:border-blue-500 focus:bg-white transition-all text-gray-900" required>
                        <option value="" disabled selected>Pilih Aset...</option>
                        @foreach($assets as $asset)
                            <option value="{{ $asset->id }}" {{ old('asset_id') == $asset->id ? 'selected' : '' }}>
                                {{ $asset->asset_code }} - {{ $asset->brand }} {{ $asset->model }}
                            </option>
                        @endforeach
                    </select>
                    @error('asset_id')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="borrow_date" class="block text-sm font-semibold text-gray-700 mb-2">
                            Tanggal Pinjam <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="borrow_date" id="borrow_date" value="{{ old('borrow_date', date('Y-m-d')) }}" class="block w-full px-4 py-3 bg-gray-50 border-2 border-gray-200 rounded-xl focus:ring-0 focus:border-blue-500 focus:bg-white transition-all text-gray-900" required min="{{ date('Y-m-d') }}">
                        @error('borrow_date')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="expected_return_date" class="block text-sm font-semibold text-gray-700 mb-2">
                            Rencana Kembali <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="expected_return_date" id="expected_return_date" value="{{ old('expected_return_date', date('Y-m-d', strtotime('+1 day'))) }}" class="block w-full px-4 py-3 bg-gray-50 border-2 border-gray-200 rounded-xl focus:ring-0 focus:border-blue-500 focus:bg-white transition-all text-gray-900" required min="{{ date('Y-m-d') }}">
                        @error('expected_return_date')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label for="notes" class="block text-sm font-semibold text-gray-700 mb-2">Catatan/Keperluan</label>
                    <textarea name="notes" id="notes" rows="4" class="block w-full px-4 py-3 bg-gray-50 border-2 border-gray-200 rounded-xl focus:ring-0 focus:border-blue-500 focus:bg-white transition-all text-gray-900" placeholder="Jelaskan tujuan peminjaman aset ini...">{{ old('notes') }}</textarea>
                    @error('notes')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-200">
                    <a href="{{ route('borrowings.index') }}" class="px-6 py-2.5 bg-white text-gray-700 rounded-lg hover:bg-gray-50 transition-all text-sm font-medium border border-gray-300">
                        Batal
                    </a>
                    <button type="submit" class="px-6 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-all text-sm font-medium shadow-sm hover:shadow-md">
                        Ajukan Peminjaman
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
