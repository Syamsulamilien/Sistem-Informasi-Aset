<x-app-layout>
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="mb-8 flex justify-between items-center">
            <div class="flex items-center gap-3">
                <a href="{{ route('borrowings.index') }}" class="inline-flex items-center text-gray-600 hover:text-gray-900 transition-colors">
                    <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Kembali
                </a>
            </div>
            @if(Auth::user()->isAdmin() || Auth::user()->isLaboran())
                <a href="{{ route('borrowings.edit', $borrowing) }}" class="inline-flex items-center px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition-colors text-sm font-medium">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Update Status
                </a>
            @endif
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-xl font-bold text-gray-900 mb-4">Detail Peminjaman</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Status Peminjaman</p>
                        @php
                            $statusColors = [
                                'pending' => 'bg-yellow-100 text-yellow-800',
                                'approved' => 'bg-green-100 text-green-800',
                                'rejected' => 'bg-red-100 text-red-800',
                                'borrowed' => 'bg-blue-100 text-blue-800',
                                'returned' => 'bg-gray-100 text-gray-800',
                            ];
                        @endphp
                        <span class="px-3 py-1 text-sm font-semibold rounded-full inline-block {{ $statusColors[$borrowing->status] }}">
                            {{ ucfirst($borrowing->status) }}
                        </span>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Peminjam</p>
                        <p class="font-medium text-gray-900">{{ $borrowing->user->name }}</p>
                    </div>
                    
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Aset</p>
                        <p class="font-medium text-gray-900">{{ $borrowing->asset->brand }} - {{ $borrowing->asset->model }}</p>
                        <p class="text-xs text-gray-500 mt-0.5">Kode: {{ $borrowing->asset->asset_code }}</p>
                    </div>
                    
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Lokasi Aset</p>
                        <p class="font-medium text-gray-900">{{ $borrowing->asset->location->name ?? '-' }}</p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-500 mb-1">Tanggal Pinjam</p>
                        <p class="font-medium text-gray-900">{{ $borrowing->borrow_date->format('d M Y') }}</p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-500 mb-1">Rencana Kembali</p>
                        <p class="font-medium text-gray-900">{{ $borrowing->expected_return_date->format('d M Y') }}</p>
                    </div>
                    
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Aktual Kembali</p>
                        <p class="font-medium {{ $borrowing->actual_return_date ? 'text-gray-900' : 'text-gray-400 italic' }}">
                            {{ $borrowing->actual_return_date ? $borrowing->actual_return_date->format('d M Y') : 'Belum dikembalikan' }}
                        </p>
                    </div>
                </div>

                @if($borrowing->notes)
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <p class="text-sm text-gray-500 mb-2">Catatan</p>
                    <div class="p-4 bg-gray-50 rounded-lg text-sm text-gray-700">
                        {{ $borrowing->notes }}
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
