<x-app-layout>
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="mb-8">
            <div class="flex items-center gap-3 mb-2">
                <a href="{{ route('consumables.show', $consumable) }}" class="inline-flex items-center text-gray-600 hover:text-gray-900 transition-colors">
                    <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Kembali
                </a>
            </div>
            <h1 class="text-2xl font-bold text-gray-900">Transaksi Stok: {{ $consumable->name }}</h1>
            <p class="mt-1 text-sm text-gray-600">Catat barang masuk (tambahan stok) atau barang keluar (pemakaian)</p>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="p-6 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
                <div>
                    <h3 class="font-semibold text-gray-900">Stok Saat Ini</h3>
                    <p class="text-2xl font-bold text-blue-600 mt-1">{{ $consumable->stock }} <span class="text-sm font-medium text-gray-600">{{ $consumable->unit }}</span></p>
                </div>
                <div class="text-right">
                    <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                        {{ $consumable->kategori ?? 'Umum' }}
                    </span>
                </div>
            </div>

            <form action="{{ route('consumables.storeTransaction', $consumable) }}" method="POST" class="p-6 space-y-6">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Tipe Transaksi <span class="text-red-500">*</span>
                        </label>
                        <div class="flex gap-4">
                            <label class="flex items-center flex-1 p-4 border-2 border-gray-200 rounded-xl cursor-pointer hover:bg-gray-50 transition-colors relative" :class="{'border-green-500 bg-green-50': selectedType === 'in'}" x-data="{ selectedType: '{{ old('type', 'in') }}' }">
                                <input type="radio" name="type" value="in" class="sr-only" x-model="selectedType" @change="selectedType = 'in'">
                                <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center mr-3">
                                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                    </svg>
                                </div>
                                <div>
                                    <span class="block font-semibold text-gray-900">Barang Masuk</span>
                                    <span class="block text-xs text-gray-500">Tambah stok</span>
                                </div>
                                <div x-show="selectedType === 'in'" class="absolute top-2 right-2 text-green-500">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                            </label>

                            <label class="flex items-center flex-1 p-4 border-2 border-gray-200 rounded-xl cursor-pointer hover:bg-gray-50 transition-colors relative" :class="{'border-red-500 bg-red-50': selectedType === 'out'}" x-data="{ selectedType: '{{ old('type', 'in') }}' }">
                                <input type="radio" name="type" value="out" class="sr-only" x-model="selectedType" @change="selectedType = 'out'">
                                <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center mr-3">
                                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                    </svg>
                                </div>
                                <div>
                                    <span class="block font-semibold text-gray-900">Barang Keluar</span>
                                    <span class="block text-xs text-gray-500">Kurangi stok</span>
                                </div>
                                <div x-show="selectedType === 'out'" class="absolute top-2 right-2 text-red-500">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                            </label>
                        </div>
                        @error('type')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-6">
                        <div>
                            <label for="quantity" class="block text-sm font-semibold text-gray-700 mb-2">
                                Jumlah <span class="text-red-500">*</span>
                            </label>
                            <div class="flex items-center">
                                <input type="number" name="quantity" id="quantity" min="1" value="{{ old('quantity', 1) }}" class="block w-full px-4 py-3 bg-gray-50 border-2 border-gray-200 rounded-l-xl focus:ring-0 focus:border-blue-500 focus:bg-white transition-all text-gray-900" required>
                                <span class="px-4 py-3 bg-gray-100 border-y-2 border-r-2 border-gray-200 rounded-r-xl text-gray-600 border-l-0">
                                    {{ $consumable->unit }}
                                </span>
                            </div>
                            @error('quantity')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="transaction_date" class="block text-sm font-semibold text-gray-700 mb-2">
                                Tanggal Transaksi <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="transaction_date" id="transaction_date" value="{{ old('transaction_date', date('Y-m-d')) }}" class="block w-full px-4 py-3 bg-gray-50 border-2 border-gray-200 rounded-xl focus:ring-0 focus:border-blue-500 focus:bg-white transition-all text-gray-900" required>
                            @error('transaction_date')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <div>
                    <label for="notes" class="block text-sm font-semibold text-gray-700 mb-2">Catatan Keterangan</label>
                    <textarea name="notes" id="notes" rows="3" class="block w-full px-4 py-3 bg-gray-50 border-2 border-gray-200 rounded-xl focus:ring-0 focus:border-blue-500 focus:bg-white transition-all text-gray-900" placeholder="Misal: Pembelian baru dari supplier X, atau diambil oleh divisi Y untuk kegiatan Z...">{{ old('notes') }}</textarea>
                    @error('notes')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-200">
                    <a href="{{ route('consumables.show', $consumable) }}" class="px-6 py-2.5 bg-white text-gray-700 rounded-lg hover:bg-gray-50 transition-all text-sm font-medium border border-gray-300">
                        Batal
                    </a>
                    <button type="submit" class="px-6 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-all text-sm font-medium shadow-sm hover:shadow-md">
                        Simpan Transaksi
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
