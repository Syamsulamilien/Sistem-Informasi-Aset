<x-app-layout>
    <div class="py-6 px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto">
            <div class="mb-6">
                <h1 class="text-3xl font-bold text-gray-800">Tambah Jenis Aset Baru</h1>
                <p class="text-gray-600">Buat jenis aset IT baru dengan prefix kode unik</p>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6">
                <form method="POST" action="{{ route('asset-types.store') }}">
                    @csrf

                    <div class="space-y-4">
                        <!-- Nama Jenis -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nama Jenis Aset *</label>
                            <input type="text" name="name" value="{{ old('name') }}" required 
                                placeholder="contoh: Router, Scanner, CCTV"
                                class="w-full px-4 py-2 rounded-lg focus:ring-2 focus:ring-blue-500 {{ $errors->has('name') ? 'border-red-500' : 'border-gray-300' }} border">
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Prefix Kode -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Prefix Kode *</label>
                            <input type="text" name="code_prefix" value="{{ old('code_prefix') }}" required 
                                placeholder="contoh: RTR, SCN, CCTV"
                                maxlength="10"
                                pattern="[A-Z0-9]+"
                                class="w-full px-4 py-2 rounded-lg focus:ring-2 focus:ring-blue-500 font-mono uppercase {{ $errors->has('code_prefix') ? 'border-red-500' : 'border-gray-300' }} border">
                            @error('code_prefix')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500">
                                ⚠️ Hanya huruf KAPITAL dan angka, tanpa spasi (max 10 karakter). Contoh: RTR, SWT, AP
                            </p>
                        </div>

                        <!-- Preview Kode -->
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Preview Kode Aset:</label>
                            <div class="flex items-center gap-2">
                                <span id="preview-code" class="px-4 py-2 bg-blue-100 text-blue-800 font-mono font-bold rounded text-lg">
                                    XXX-{{ date('Y') }}-0001
                                </span>
                                <span class="text-sm text-gray-600">← Contoh kode yang akan dibuat</span>
                            </div>
                        </div>

                        <!-- Deskripsi -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
                            <textarea name="description" rows="3" 
                                placeholder="Deskripsi singkat tentang jenis aset ini"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">{{ old('description') }}</textarea>
                        </div>

                        <!-- Status -->
                        <div>
                            <label class="flex items-center">
                                <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                                    class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                <span class="ml-2 text-sm text-gray-700">Jenis aset aktif (bisa digunakan untuk aset baru)</span>
                            </label>
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end gap-2">
                        <a href="{{ route('asset-types.index') }}" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400">
                            Batal
                        </a>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                            Simpan Jenis Aset
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Live preview kode aset
        document.querySelector('input[name="code_prefix"]').addEventListener('input', function(e) {
            let prefix = e.target.value.toUpperCase().replace(/[^A-Z0-9]/g, '');
            e.target.value = prefix;
            
            if (prefix) {
                document.getElementById('preview-code').textContent = prefix + '-{{ date("Y") }}-0001';
            } else {
                document.getElementById('preview-code').textContent = 'XXX-{{ date("Y") }}-0001';
            }
        });
    </script>
</x-app-layout>