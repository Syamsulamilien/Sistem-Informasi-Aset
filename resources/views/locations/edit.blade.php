<x-app-layout :title="'Edit Unit ' . $location->name">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <!-- Header -->
        <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center gap-3">
                <a href="{{ route('locations.index') }}" class="p-2 text-gray-500 hover:text-gray-800 hover:bg-gray-100 rounded-xl border border-gray-200 transition-colors shadow-sm" title="Kembali">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                </a>
                <div>
                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 tracking-tight">Edit Unit</h1>
                    <p class="text-xs sm:text-sm text-gray-500 mt-1">Perbarui informasi unit: {{ $location->name }}</p>
                </div>
            </div>
            <a href="{{ route('locations.index') }}" class="inline-flex items-center justify-center px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-sm font-semibold transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali
            </a>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 sm:p-8">
                <form method="POST" action="{{ route('locations.update', $location) }}">
                    @csrf
                    @method('PUT')

                    <div class="space-y-4">
                        <!-- Nama Unit -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nama Unit *</label>
                            <input type="text" name="name" value="{{ old('name', $location->name) }}" required 
                                placeholder="Contoh: Ruang IT, Ruang Server, Unit Radiologi"
                                class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 
                                {{ $errors->has('name') ? 'border-red-500' : 'border-gray-300' }}">
                            @error('name')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Lantai -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Lantai</label>
                            <input type="text" name="floor" value="{{ old('floor', $location->floor) }}" 
                                placeholder="Contoh: Lantai 1, Lantai 2, Basement"
                                class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 
                                {{ $errors->has('floor') ? 'border-red-500' : 'border-gray-300' }}">
                            @error('floor')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500">Opsional: Informasi lantai lokasi unit berada</p>
                        </div>

                        <!-- Deskripsi -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
                            <textarea name="description" rows="4" 
                                placeholder="Contoh: Ruangan untuk menyimpan server dan perangkat jaringan di lantai 2"
                                class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 
                                {{ $errors->has('description') ? 'border-red-500' : 'border-gray-300' }}">{{ old('description', $location->description) }}</textarea>
                            @error('description')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500">Opsional: Tambahkan informasi detail tentang unit ini (lokasi, fungsi, dll)</p>
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end gap-2">
                        <a href="{{ route('locations.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                            Batal
                        </a>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                            Update Unit
                        </button>
                    </div>
                </form>
            </div>
        </div>
</x-app-layout>