<x-app-layout :title="'Detail Peminjaman ' . $borrowing->borrower_name">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <!-- Header -->
        <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center gap-3">
                <a href="{{ route('borrowings.index') }}" class="p-2 text-gray-500 hover:text-gray-800 hover:bg-gray-100 rounded-xl border border-gray-200 transition-colors shadow-sm" title="Kembali">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                </a>
                <div>
                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 tracking-tight">Detail Peminjaman</h1>
                    <p class="text-xs sm:text-sm text-gray-500 mt-1">Peminjam: {{ $borrowing->borrower_name }} • Aset: {{ $borrowing->asset->asset_code }}</p>
                </div>
            </div>
            <div class="flex flex-wrap items-center gap-2">
                @if(Auth::user()->isAdmin() || Auth::user()->isLaboran())
                    <a href="{{ route('borrowings.edit', $borrowing) }}" class="inline-flex items-center justify-center px-4 py-2.5 bg-yellow-600 hover:bg-yellow-700 text-white rounded-xl text-sm font-semibold shadow-sm transition-all active:scale-[0.98]">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Update Status
                    </a>
                @endif
                <a href="{{ route('borrowings.index') }}" class="inline-flex items-center justify-center px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-sm font-semibold transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Kembali
                </a>
            </div>
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
