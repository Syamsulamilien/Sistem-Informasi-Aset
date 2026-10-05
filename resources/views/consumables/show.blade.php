<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="mb-8 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
            <div class="flex items-center gap-3">
                <a href="{{ route('consumables.index') }}" class="inline-flex items-center text-gray-600 hover:text-gray-900 transition-colors">
                    <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Kembali
                </a>
            </div>
            <div class="flex gap-2">
                @if(Auth::user()->isAdmin() || Auth::user()->isLaboran())
                    <a href="{{ route('consumables.transaction', $consumable) }}" class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors text-sm font-medium shadow-sm">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                        </svg>
                        Transaksi Stok
                    </a>
                    <a href="{{ route('consumables.edit', $consumable) }}" class="inline-flex items-center px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition-colors text-sm font-medium shadow-sm">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Edit
                    </a>
                @endif
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Info Barang -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-6">
                    @if($consumable->photo)
                        <img src="{{ asset('storage/' . $consumable->photo) }}" alt="{{ $consumable->name }}" class="w-full h-48 object-cover">
                    @else
                        <div class="w-full h-48 bg-gray-100 flex items-center justify-center">
                            <svg class="w-16 h-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                    @endif
                    
                    <div class="p-6">
                        <h2 class="text-xl font-bold text-gray-900 mb-1">{{ $consumable->name }}</h2>
                        <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800 mb-4">
                            {{ $consumable->kategori ?? 'Umum' }}
                        </span>

                        <div class="space-y-4">
                            <div class="flex justify-between items-center pb-3 border-b border-gray-100">
                                <span class="text-sm text-gray-500">Stok Saat Ini</span>
                                <span class="text-lg font-bold {{ $consumable->stock > 10 ? 'text-green-600' : ($consumable->stock > 0 ? 'text-yellow-600' : 'text-red-600') }}">
                                    {{ $consumable->stock }} {{ $consumable->unit }}
                                </span>
                            </div>
                            <div class="flex justify-between items-center pb-3 border-b border-gray-100">
                                <span class="text-sm text-gray-500">Lokasi</span>
                                <span class="text-sm font-medium text-gray-900">{{ $consumable->location->name ?? '-' }}</span>
                            </div>
                            <div class="flex justify-between items-center pb-3 border-b border-gray-100">
                                <span class="text-sm text-gray-500">Sumber Dana</span>
                                <span class="text-sm font-medium text-gray-900">{{ $consumable->sumber_dana ?? '-' }}</span>
                            </div>
                            <div class="flex justify-between items-center pb-3 border-b border-gray-100">
                                <span class="text-sm text-gray-500">Harga Satuan</span>
                                <span class="text-sm font-bold text-emerald-600">
                                    {{ $consumable->price ? 'Rp ' . number_format($consumable->price, 0, ',', '.') : '-' }}
                                </span>
                            </div>
                            @if($consumable->description)
                                <div>
                                    <span class="text-sm text-gray-500 block mb-1">Deskripsi</span>
                                    <p class="text-sm text-gray-900">{{ $consumable->description }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Riwayat Transaksi -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="text-lg font-bold text-gray-900">Riwayat Transaksi Stok</h3>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipe</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Petugas</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Catatan</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($transactions as $trx)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $trx->transaction_date->format('d M Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($trx->type == 'in')
                                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                                    Masuk
                                                </span>
                                            @else
                                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                                    Keluar
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium {{ $trx->type == 'in' ? 'text-green-600' : 'text-red-600' }}">
                                            {{ $trx->type == 'in' ? '+' : '-' }}{{ $trx->quantity }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $trx->user->name ?? '-' }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-500 max-w-xs truncate">
                                            {{ $trx->notes ?? '-' }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-8 text-center text-gray-500 text-sm">
                                            Belum ada riwayat transaksi.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    @if($transactions->hasPages())
                        <div class="px-6 py-4 border-t border-gray-200">
                            {{ $transactions->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
