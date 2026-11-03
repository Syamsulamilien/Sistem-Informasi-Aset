<x-app-layout :title="'Detail Peminjaman - ' . $borrowing->user->name">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <!-- Header Halaman -->
        <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center gap-3">
                <a href="{{ route('borrowings.index') }}" class="p-2 text-gray-500 hover:text-gray-800 hover:bg-gray-100 rounded-xl border border-gray-200 transition-colors shadow-sm" title="Kembali">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                </a>
                <div>
                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 tracking-tight">Detail Peminjaman</h1>
                    <p class="text-xs sm:text-sm text-gray-500 mt-1">Peminjam: {{ $borrowing->user->name }} • Kode: {{ $borrowing->asset->asset_code }}</p>
                </div>
            </div>

            <div class="flex flex-wrap items-center gap-2">
                @if($borrowing->status !== 'returned' && (Auth::user()->isAdmin() || Auth::user()->isLaboran() || Auth::user()->isSuperAdmin()))
                    <a href="{{ route('borrowings.edit', $borrowing) }}" class="inline-flex items-center justify-center px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-sm font-semibold shadow-sm transition-all active:scale-[0.98]">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Proses Pengembalian
                    </a>
                @elseif(Auth::user()->isAdmin() || Auth::user()->isLaboran() || Auth::user()->isSuperAdmin())
                    <a href="{{ route('borrowings.edit', $borrowing) }}" class="inline-flex items-center justify-center px-4 py-2.5 bg-yellow-600 hover:bg-yellow-700 text-white rounded-xl text-sm font-semibold shadow-sm transition-all active:scale-[0.98]">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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

        <!-- Kartu Detail Utama -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="p-6 sm:p-8 space-y-6">
                <!-- Status Peminjaman -->
                <div class="flex items-center justify-between p-4 {{ $borrowing->status === 'returned' ? 'bg-emerald-50 border border-emerald-200' : 'bg-blue-50 border border-blue-200' }} rounded-xl">
                    <div>
                        <span class="text-xs uppercase font-bold tracking-wider {{ $borrowing->status === 'returned' ? 'text-emerald-700' : 'text-blue-700' }}">Status Transaksi</span>
                        <h3 class="text-base font-bold text-gray-900 mt-0.5">
                            {{ $borrowing->status === 'returned' ? 'Sudah Dikembalikan' : 'Sedang Dipinjam' }}
                        </h3>
                    </div>
                    <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $borrowing->status === 'returned' ? 'bg-emerald-100 text-emerald-800' : 'bg-blue-100 text-blue-800' }}">
                        {{ $borrowing->status === 'returned' ? 'Selesai' : 'Aktif' }}
                    </span>
                </div>

                <!-- Grid Data Peminjaman -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                    <div class="p-4 bg-gray-50 rounded-xl border border-gray-100">
                        <span class="text-xs text-gray-500 block mb-1">Aset / Barang</span>
                        <p class="font-semibold text-gray-900">{{ $borrowing->asset->brand }} - {{ $borrowing->asset->model }}</p>
                        <p class="text-xs text-gray-500 font-mono mt-0.5">Kode: {{ $borrowing->asset->asset_code }}</p>
                    </div>

                    <div class="p-4 bg-gray-50 rounded-xl border border-gray-100">
                        <span class="text-xs text-gray-500 block mb-1">Lokasi Ruangan</span>
                        <p class="font-semibold text-gray-900">{{ $borrowing->asset->location->name ?? '-' }}</p>
                        <p class="text-xs text-gray-500 mt-0.5">Lantai: {{ $borrowing->asset->location->floor ?? '-' }}</p>
                    </div>

                    <div class="p-4 bg-gray-50 rounded-xl border border-gray-100">
                        <span class="text-xs text-gray-500 block mb-1">Peminjam</span>
                        <p class="font-semibold text-gray-900 flex items-center gap-1.5">
                            {{ $borrowing->user->name }}
                            @if($borrowing->user->gender === 'Laki-laki')
                                <span class="text-[10px] font-bold text-blue-700 bg-blue-50 border border-blue-200 px-1 py-0.2 rounded" title="Laki-laki">L</span>
                            @elseif($borrowing->user->gender === 'Perempuan')
                                <span class="text-[10px] font-bold text-rose-700 bg-rose-50 border border-rose-200 px-1 py-0.2 rounded" title="Perempuan">P</span>
                            @endif
                        </p>
                        <p class="text-xs text-gray-500 mt-0.5">{{ ucfirst($borrowing->user->role) }} {{ $borrowing->user->class_name ? '• Kelas ' . $borrowing->user->class_name : '' }}</p>
                    </div>

                    <div class="p-4 bg-gray-50 rounded-xl border border-gray-100">
                        <span class="text-xs text-gray-500 block mb-1">Jadwal Tanggal</span>
                        <p class="font-semibold text-gray-900">Pinjam: {{ $borrowing->borrow_date->format('d M Y') }}</p>
                        <p class="text-xs text-gray-500 mt-0.5">Rencana Kembali: {{ $borrowing->expected_return_date->format('d M Y') }}</p>
                    </div>
                </div>

                @if($borrowing->actual_return_date)
                    <div class="p-4 bg-emerald-50/60 rounded-xl border border-emerald-200 text-sm flex items-center justify-between">
                        <span class="text-emerald-800 font-medium">Tanggal Pengembalian Aktual:</span>
                        <span class="font-bold text-emerald-900">{{ $borrowing->actual_return_date->format('d M Y') }}</span>
                    </div>
                @endif

                @if($borrowing->notes)
                    <div>
                        <span class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Catatan Keperluan</span>
                        <div class="p-4 bg-gray-50 rounded-xl text-sm text-gray-700 border border-gray-200">
                            {{ $borrowing->notes }}
                        </div>
                    </div>
                @endif

                <!-- Symmetrical Foto Bukti Fisik Section (50% / 50%) -->
                <div class="pt-6 border-t border-gray-200">
                    <h4 class="text-sm font-semibold text-gray-900 mb-4 flex items-center gap-2">
                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        Foto Dokumentasi & Bukti Fisik
                    </h4>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- Kartu 1: Foto Bukti Peminjaman -->
                        <div class="bg-gray-50 rounded-xl p-4 border border-gray-200 flex flex-col justify-between">
                            <div class="flex items-center justify-between mb-2.5">
                                <span class="text-xs font-semibold text-gray-800 flex items-center gap-1.5">
                                    <span class="w-2 h-2 rounded-full bg-blue-500"></span>
                                    Bukti Peminjaman (Awal)
                                </span>
                                @if($borrowing->borrow_photo)
                                    <span class="text-[10px] font-bold text-emerald-700 bg-emerald-50 border border-emerald-200 px-2 py-0.5 rounded-full">Tersedia</span>
                                @else
                                    <span class="text-[10px] text-gray-400 bg-white border border-gray-200 px-2 py-0.5 rounded-full">Tidak Ada</span>
                                @endif
                            </div>

                            <div class="relative w-full h-44 rounded-lg overflow-hidden border border-gray-200 bg-gray-900 flex items-center justify-center group">
                                @if($borrowing->borrow_photo && \Illuminate\Support\Facades\Storage::disk('public')->exists($borrowing->borrow_photo))
                                    <img src="{{ asset('storage/' . $borrowing->borrow_photo) }}" alt="Bukti Peminjaman" class="w-full h-full object-contain cursor-pointer hover:scale-105 transition-transform" onclick="window.viewFullImage('{{ asset('storage/' . $borrowing->borrow_photo) }}')">
                                    <button type="button" onclick="window.viewFullImage('{{ asset('storage/' . $borrowing->borrow_photo) }}')" class="absolute bottom-2 right-2 px-2.5 py-1 rounded-md bg-black/70 hover:bg-black/90 text-white text-xs font-medium backdrop-blur-sm flex items-center gap-1 shadow-sm opacity-90 group-hover:opacity-100 transition-opacity">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"></path>
                                        </svg>
                                        <span>Perbesar</span>
                                    </button>
                                @else
                                    <div class="text-center p-4 text-gray-400">
                                        <svg class="w-8 h-8 mx-auto mb-1 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        <span class="text-xs">Tidak ada foto bukti pinjam</span>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Kartu 2: Foto Bukti Pengembalian -->
                        <div class="bg-gray-50 rounded-xl p-4 border border-gray-200 flex flex-col justify-between">
                            <div class="flex items-center justify-between mb-2.5">
                                <span class="text-xs font-semibold text-gray-800 flex items-center gap-1.5">
                                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                    Bukti Pengembalian (Akhir)
                                </span>
                                @if($borrowing->return_photo)
                                    <span class="text-[10px] font-bold text-emerald-700 bg-emerald-50 border border-emerald-200 px-2 py-0.5 rounded-full">Tersedia</span>
                                @else
                                    <span class="text-[10px] text-gray-400 bg-white border border-gray-200 px-2 py-0.5 rounded-full">Belum Ada</span>
                                @endif
                            </div>

                            <div class="relative w-full h-44 rounded-lg overflow-hidden border border-gray-200 bg-gray-900 flex items-center justify-center group">
                                @if($borrowing->return_photo && \Illuminate\Support\Facades\Storage::disk('public')->exists($borrowing->return_photo))
                                    <img src="{{ asset('storage/' . $borrowing->return_photo) }}" alt="Bukti Pengembalian" class="w-full h-full object-contain cursor-pointer hover:scale-105 transition-transform" onclick="window.viewFullImage('{{ asset('storage/' . $borrowing->return_photo) }}')">
                                    <button type="button" onclick="window.viewFullImage('{{ asset('storage/' . $borrowing->return_photo) }}')" class="absolute bottom-2 right-2 px-2.5 py-1 rounded-md bg-black/70 hover:bg-black/90 text-white text-xs font-medium backdrop-blur-sm flex items-center gap-1 shadow-sm opacity-90 group-hover:opacity-100 transition-opacity">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"></path>
                                        </svg>
                                        <span>Perbesar</span>
                                    </button>
                                @else
                                    <div class="text-center p-4 text-gray-400">
                                        <svg class="w-8 h-8 mx-auto mb-1 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        <span class="text-xs">Belum ada foto bukti kembali</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
