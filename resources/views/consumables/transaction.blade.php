<x-app-layout>
    <div class="w-full px-4 sm:px-6 lg:px-10 py-6">

        {{-- Header --}}
        <div class="mb-6 flex flex-wrap items-end justify-between gap-4">
            <div>
                <a href="{{ route('consumables.show', $consumable) }}"
                   class="inline-flex items-center gap-1.5 text-sm text-gray-600 hover:text-gray-900 transition-colors rounded focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Kembali
                </a>
                <h1 class="mt-2 text-2xl font-bold text-gray-900">Transaksi stok: {{ $consumable->name }}</h1>
                <p class="mt-1 text-sm text-gray-600">Catat barang masuk (tambah stok) atau barang keluar (pemakaian).</p>
            </div>
            <span class="px-3 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                {{ $consumable->kategori ?? 'Umum' }}
            </span>
        </div>

        <form action="{{ route('consumables.storeTransaction', $consumable) }}" method="POST"
              class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start"
              x-data="{
                type: @js(old('type', 'in')),
                qty: @js((int) old('quantity', 1)),
                stock: @js((int) $consumable->stock),
                submitting: false,
                get amount() { return Number(this.qty) || 0 },
                get result() { return this.amount < 1 ? this.stock : (this.type === 'in' ? this.stock + this.amount : this.stock - this.amount) },
                get tooMuch() { return this.type === 'out' && this.amount > this.stock },
                get invalid() { return this.amount < 1 || this.tooMuch },
                inc() { this.qty = this.amount + 1 },
                dec() { if (this.amount > 1) this.qty = this.amount - 1 }
              }"
              @submit="submitting = true">
            @csrf

            {{-- Kolom kiri: ringkasan stok (menempel saat scroll) --}}
            <aside class="lg:col-span-4 xl:col-span-3 lg:sticky lg:top-6 space-y-4">
                <div class="bg-white rounded-xl border border-gray-200 p-6">
                    <p class="text-sm font-medium text-gray-600">Stok saat ini</p>
                    <p class="mt-1 text-5xl font-bold text-gray-900 tabular-nums">
                        {{ $consumable->stock }}
                        <span class="text-lg font-medium text-gray-500">{{ $consumable->unit }}</span>
                    </p>
                    @if ($consumable->stock <= 0)
                        <span class="mt-3 inline-block px-3 py-1 text-xs font-semibold rounded-full bg-amber-100 text-amber-800">Stok kosong</span>
                    @endif
                </div>

                <div class="rounded-xl border p-6 transition-colors" aria-live="polite"
                     :class="tooMuch ? 'border-red-200 bg-red-50' : (type === 'in' ? 'border-green-200 bg-green-50' : 'border-red-200 bg-red-50')">
                    <p class="text-sm font-medium text-gray-700">Stok setelah transaksi</p>
                    <p class="mt-1 text-4xl font-bold tabular-nums"
                       :class="tooMuch ? 'text-red-700' : (type === 'in' ? 'text-green-700' : 'text-red-700')">
                        <span x-text="result"></span>
                        <span class="text-base font-medium">{{ $consumable->unit }}</span>
                    </p>
                    <p class="mt-2 text-sm text-gray-600">
                        <span x-text="(type === 'in' ? '+' : '−') + amount"></span>
                        {{ $consumable->unit }} dari stok saat ini
                    </p>
                    <p x-show="tooMuch" x-cloak class="mt-3 text-sm font-medium text-red-700" role="alert">
                        Jumlah melebihi stok yang tersedia.
                    </p>
                </div>
            </aside>

            {{-- Kolom kanan: form --}}
            <section class="lg:col-span-8 xl:col-span-9 bg-white rounded-xl border border-gray-200">
                <div class="p-6 lg:p-8 space-y-8">

                    {{-- Tipe transaksi --}}
                    <fieldset>
                        <legend class="text-sm font-semibold text-gray-700 mb-3">
                            Tipe transaksi <span class="text-red-500">*</span>
                        </legend>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <label class="relative flex items-center gap-4 p-5 border-2 rounded-xl cursor-pointer transition-colors focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-green-500"
                                   :class="type === 'in' ? 'border-green-500 bg-green-50' : 'border-gray-200 bg-white hover:bg-gray-50'">
                                <input type="radio" name="type" value="in" class="sr-only" x-model="type">
                                <span class="flex-none w-12 h-12 rounded-full bg-green-100 flex items-center justify-center">
                                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                    </svg>
                                </span>
                                <span class="min-w-0">
                                    <span class="block font-semibold text-gray-900">Barang masuk</span>
                                    <span class="block text-sm text-gray-500">Tambah stok</span>
                                </span>
                                <svg x-show="type === 'in'" x-cloak class="absolute top-3 right-3 w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                            </label>

                            <label class="relative flex items-center gap-4 p-5 border-2 rounded-xl cursor-pointer transition-colors focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-red-500"
                                   :class="type === 'out' ? 'border-red-500 bg-red-50' : 'border-gray-200 bg-white hover:bg-gray-50'">
                                <input type="radio" name="type" value="out" class="sr-only" x-model="type">
                                <span class="flex-none w-12 h-12 rounded-full bg-red-100 flex items-center justify-center">
                                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                    </svg>
                                </span>
                                <span class="min-w-0">
                                    <span class="block font-semibold text-gray-900">Barang keluar</span>
                                    <span class="block text-sm text-gray-500">Kurangi stok</span>
                                </span>
                                <svg x-show="type === 'out'" x-cloak class="absolute top-3 right-3 w-5 h-5 text-red-500" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                            </label>
                        </div>
                        @error('type')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </fieldset>

                    {{-- Jumlah & tanggal --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="quantity" class="block text-sm font-semibold text-gray-700 mb-2">
                                Jumlah <span class="text-red-500">*</span>
                            </label>
                            <div class="flex items-stretch">
                                <button type="button" @click="dec()" :disabled="amount <= 1" aria-label="Kurangi jumlah"
                                        class="w-12 flex items-center justify-center bg-gray-100 border-2 border-r-0 border-gray-200 rounded-l-xl text-gray-700 hover:bg-gray-200 disabled:opacity-40 disabled:cursor-not-allowed focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path></svg>
                                </button>
                                <input type="number" name="quantity" id="quantity" min="1" inputmode="numeric"
                                       x-model="qty" :max="type === 'out' ? stock : null"
                                       class="block w-full min-w-0 px-3 py-3 text-center text-lg tabular-nums bg-gray-50 border-2 border-gray-200 focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 focus:bg-white transition-all text-gray-900"
                                       :class="tooMuch ? 'border-red-400' : ''" required>
                                <button type="button" @click="inc()" aria-label="Tambah jumlah"
                                        class="w-12 flex items-center justify-center bg-gray-100 border-2 border-l-0 border-gray-200 text-gray-700 hover:bg-gray-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                </button>
                                <span class="px-4 flex items-center bg-gray-100 border-2 border-l-0 border-gray-200 rounded-r-xl text-sm text-gray-600">
                                    {{ $consumable->unit }}
                                </span>
                            </div>
                            @error('quantity')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="transaction_date" class="block text-sm font-semibold text-gray-700 mb-2">
                                Tanggal transaksi <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="transaction_date" id="transaction_date"
                                   value="{{ old('transaction_date', date('Y-m-d')) }}"
                                   class="block w-full px-4 py-3 text-lg bg-gray-50 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 focus:bg-white transition-all text-gray-900" required>
                            @error('transaction_date')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Catatan --}}
                    <div>
                        <label for="notes" class="block text-sm font-semibold text-gray-700 mb-2">
                            Catatan <span class="font-normal text-gray-500">(opsional)</span>
                        </label>
                        <textarea name="notes" id="notes" rows="5"
                                  class="block w-full px-4 py-3 bg-gray-50 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 focus:bg-white transition-all text-gray-900 placeholder:text-gray-400"
                                  placeholder="Contoh: pembelian dari supplier X, atau diambil divisi Y untuk kegiatan Z">{{ old('notes') }}</textarea>
                        @error('notes')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Aksi --}}
                <div class="flex flex-col-reverse sm:flex-row sm:items-center sm:justify-end gap-3 px-6 lg:px-8 py-4 bg-gray-50 border-t border-gray-200 rounded-b-xl">
                    <a href="{{ route('consumables.show', $consumable) }}"
                       class="px-6 py-2.5 text-center bg-white text-gray-700 rounded-lg hover:bg-gray-50 transition-all text-sm font-medium border border-gray-300 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500">
                        Batal
                    </a>
                    <button type="submit" :disabled="invalid || submitting"
                            class="px-6 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-all text-sm font-medium shadow-sm disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:bg-blue-600 focus:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:ring-blue-500">
                        <span x-show="!submitting">Simpan transaksi</span>
                        <span x-show="submitting" x-cloak>Menyimpan...</span>
                    </button>
                </div>
            </section>
        </form>
    </div>
</x-app-layout>