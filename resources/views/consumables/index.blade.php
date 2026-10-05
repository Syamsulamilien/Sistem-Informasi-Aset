<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="mb-6 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Daftar Barang Habis Pakai</h1>
                <p class="text-sm text-gray-600 mt-1">Kelola stok barang habis pakai (ATK, reagen, dll)</p>
            </div>
            @if(Auth::user()->isAdmin() || Auth::user()->isLaboran())
                <a href="{{ route('consumables.create') }}" class="inline-flex items-center justify-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors shadow-sm">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Tambah Barang
                </a>
            @endif
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 sm:p-6 mb-6">
            <form method="GET" action="{{ route('consumables.index') }}">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div class="md:col-span-3">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Pencarian</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama barang atau kategori..." class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        </div>
                    </div>
                    
                    <div class="flex items-end gap-2">
                        <button type="submit" class="flex-1 inline-flex items-center justify-center px-6 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium shadow-sm">
                            Cari
                        </button>
                        <a href="{{ route('consumables.index') }}" class="inline-flex items-center justify-center px-4 py-2.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors font-medium">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                        </a>
                    </div>
                </div>
            </form>
        </div>

        <!-- Desktop Table -->
        <div class="hidden lg:block bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Nama Barang</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Kategori</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Harga</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Stok</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Satuan</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Lokasi</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($consumables as $consumable)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div class="h-10 w-10 flex-shrink-0">
                                            @if($consumable->photo)
                                                <img class="h-10 w-10 rounded-lg object-cover" src="{{ asset('storage/' . $consumable->photo) }}" alt="">
                                            @else
                                                <div class="h-10 w-10 rounded-lg bg-gray-100 flex items-center justify-center">
                                                    <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                    </svg>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $consumable->name }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                        {{ $consumable->kategori ?? 'Umum' }} 
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm font-medium text-gray-900">
                                        {{ $consumable->price ? 'Rp ' . number_format($consumable->price, 0, ',', '.') : '-' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $consumable->stock > 10 ? 'bg-green-100 text-green-800' : ($consumable->stock > 0 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                        {{ $consumable->stock }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $consumable->unit }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900">{{ $consumable->location->name ?? '-' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <div class="flex gap-2">
                                        <a href="{{ route('consumables.show', $consumable) }}" class="text-blue-600 hover:text-blue-800 font-medium">Detail</a>
                                        @if(Auth::user()->isAdmin() || Auth::user()->isLaboran())
                                            <a href="{{ route('consumables.edit', $consumable) }}" class="text-yellow-600 hover:text-yellow-800 font-medium">Edit</a>
                                            <form action="{{ route('consumables.destroy', $consumable) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-800 font-medium">Hapus</button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center">
                                    <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                                    </svg>
                                    <p class="text-gray-500 text-sm">Tidak ada data barang habis pakai</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Mobile View (Simplified) -->
        <div class="lg:hidden space-y-4">
            @forelse($consumables as $consumable)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
                    <div class="flex justify-between items-start mb-3">
                        <div class="flex items-center">
                            @if($consumable->photo)
                                <img class="h-10 w-10 rounded-lg object-cover mr-3" src="{{ asset('storage/' . $consumable->photo) }}" alt="">
                            @endif
                            <div>
                                <p class="text-sm font-bold text-gray-900">{{ $consumable->name }}</p>
                                <p class="text-xs text-gray-500">{{ $consumable->kategori }}</p>
                                @if($consumable->price)
                                    <p class="text-xs font-semibold text-emerald-600 mt-0.5">Rp {{ number_format($consumable->price, 0, ',', '.') }}</p>
                                @endif
                            </div>
                        </div>
                        <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $consumable->stock > 10 ? 'bg-green-100 text-green-800' : ($consumable->stock > 0 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                            {{ $consumable->stock }} {{ $consumable->unit }}
                        </span>
                    </div>

                    <div class="space-y-1 mb-3">
                        @if($consumable->sumber_dana)
                            <div class="flex justify-between text-xs">
                                <span class="text-gray-500">Sumber Dana:</span>
                                <span class="font-medium text-gray-700">{{ $consumable->sumber_dana }}</span>
                            </div>
                        @endif
                    </div>
                    
                    <div class="flex gap-2 pt-3 border-t border-gray-200">
                        <a href="{{ route('consumables.show', $consumable) }}" class="flex-1 text-center px-3 py-2 text-sm font-medium text-blue-600 bg-blue-50 rounded-lg hover:bg-blue-100">Detail</a>
                        @if(Auth::user()->isAdmin() || Auth::user()->isLaboran())
                            <a href="{{ route('consumables.edit', $consumable) }}" class="flex-1 text-center px-3 py-2 text-sm font-medium text-yellow-600 bg-yellow-50 rounded-lg hover:bg-yellow-100">Edit</a>
                        @endif
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
                    <p class="text-gray-500 text-sm">Tidak ada data</p>
                </div>
            @endforelse
        </div>

        <div class="mt-6">
            {{ $consumables->links() }}
        </div>
    </div>
</x-app-layout>
