<x-app-layout>
    <div class="py-6 px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto">
            <div class="mb-6">
                <h1 class="text-3xl font-bold text-gray-800">Tambah Unit Baru</h1>
                <p class="text-gray-600">Tambahkan unit/ruangan baru</p>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6">
                <form method="POST" action="{{ route('locations.store') }}">
                    @csrf

                    <div class="space-y-4">
                        <!-- Nama Unit -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nama Unit *</label>
                            <input type="text" name="name" value="{{ old('name') }}" required 
                                placeholder="Contoh: Ruang IT, Ruang Server, Unit Radiologi"
                                class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 
                                {{ $errors->has('name') ? 'border-red-500' : 'border-gray-300' }}">
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Lantai -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Lantai</label>
                            <input type="text" name="floor" value="{{ old('floor') }}" 
                                placeholder="Contoh: Lantai 1, Lantai 2, Basement"
                                class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 
                                {{ $errors->has('floor') ? 'border-red-500' : 'border-gray-300' }}">
                            @error('floor')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500">Opsional: Informasi lantai lokasi unit berada</p>
                        </div>

                        <!-- Deskripsi -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
                            <textarea name="description" rows="4" 
                                placeholder="Contoh: Ruangan untuk menyimpan server dan perangkat jaringan di lantai 2"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 {{ $errors->has('description') ? 'border-red-500' : '' }}">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500">Opsional: Tambahkan informasi detail tentang unit ini (lokasi, fungsi, dll)</p>
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end gap-2">
                        <a href="{{ route('locations.index') }}" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition-colors">
                            Batal
                        </a>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                            Simpan Unit
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>