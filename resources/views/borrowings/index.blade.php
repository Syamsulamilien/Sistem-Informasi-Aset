<x-app-layout :title="'Daftar Peminjaman Aset'">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <!-- Header -->
        <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 tracking-tight">Daftar Peminjaman Aset</h1>
                <p class="text-xs sm:text-sm text-gray-500 mt-1">Kelola data peminjaman dan pencatatan pengembalian aset sekolah</p>
            </div>
            <a href="{{ route('borrowings.create') }}" class="inline-flex items-center justify-center px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-semibold shadow-sm transition-all active:scale-[0.98]">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Catat Peminjaman
            </a>
        </div>

        <!-- Filter Bar -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-4 sm:p-5 mb-6">
            <form method="GET" action="{{ route('borrowings.index') }}" class="flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="w-full sm:w-72">
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Filter Status</label>
                    <select name="status" onchange="this.form.submit()" class="w-full px-3.5 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm font-medium text-gray-800 focus:ring-2 focus:ring-blue-500 focus:bg-white transition-colors">
                        <option value="">Semua Status (Dipinjam & Dikembalikan)</option>
                        <option value="borrowed" {{ request('status') == 'borrowed' ? 'selected' : '' }}>🔵 Sedang Dipinjam</option>
                        <option value="returned" {{ request('status') == 'returned' ? 'selected' : '' }}>🟢 Sudah Dikembalikan</option>
                    </select>
                </div>

                <div class="flex items-center gap-2 w-full sm:w-auto self-end">
                    <button type="submit" class="flex-1 sm:flex-none px-5 py-2 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition-colors text-sm font-semibold shadow-sm">
                        Terapkan
                    </button>
                    @if(request('status'))
                        <a href="{{ route('borrowings.index') }}" class="px-3.5 py-2 bg-gray-100 text-gray-600 rounded-xl hover:bg-gray-200 transition-colors text-xs font-semibold" title="Reset Filter">
                            Reset
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Desktop Table -->
        <div class="hidden lg:block bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-slate-50/70 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-3.5 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Aset / Barang</th>
                            <th class="px-6 py-3.5 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Peminjam</th>
                            <th class="px-6 py-3.5 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Tgl Pinjam</th>
                            <th class="px-6 py-3.5 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Rencana Kembali</th>
                            <th class="px-6 py-3.5 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3.5 text-right text-xs font-bold text-gray-600 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @forelse($borrowings as $borrowing)
                            <tr class="hover:bg-slate-50/60 transition-colors">
                                <!-- Aset & Foto Tag -->
                                <td class="px-6 py-4">
                                    <div class="text-sm font-bold text-gray-900">{{ $borrowing->asset->brand }} - {{ $borrowing->asset->model }}</div>
                                    <div class="text-xs text-gray-500 flex items-center gap-2 mt-0.5">
                                        <span class="font-mono bg-gray-100 px-1.5 py-0.2 rounded text-[11px] text-gray-700">{{ $borrowing->asset->asset_code }}</span>
                                        <span class="text-gray-400">•</span>
                                        <span>{{ $borrowing->asset->location->name ?? 'Umum' }}</span>
                                        @if($borrowing->borrow_photo || $borrowing->return_photo)
                                            <span class="inline-flex items-center gap-1 text-[10px] text-blue-700 bg-blue-50 border border-blue-200 px-1.5 py-0.2 rounded font-medium" title="Memiliki Foto Bukti Fisik">
                                                <svg class="w-3 h-3 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                                                </svg>
                                                Foto
                                            </span>
                                        @endif
                                    </div>
                                </td>

                                <!-- Peminjam -->
                                <td class="px-6 py-4">
                                    <div class="text-sm font-semibold text-gray-900 flex items-center gap-1.5">
                                        {{ $borrowing->user->name }}
                                        @if($borrowing->user->gender === 'Laki-laki')
                                            <span class="text-[10px] font-bold text-blue-700 bg-blue-50 border border-blue-200 px-1.5 py-0.2 rounded" title="Laki-laki">L</span>
                                        @elseif($borrowing->user->gender === 'Perempuan')
                                            <span class="text-[10px] font-bold text-rose-700 bg-rose-50 border border-rose-200 px-1.5 py-0.2 rounded" title="Perempuan">P</span>
                                        @endif
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        {{ ucfirst($borrowing->user->role) }}
                                        @if($borrowing->user->role === 'siswa' && $borrowing->user->class_name)
                                            • <span class="font-semibold text-blue-600">Kelas {{ $borrowing->user->class_name }}</span>
                                        @endif
                                    </div>
                                </td>

                                <!-- Tanggal Pinjam -->
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 font-medium">
                                    {{ $borrowing->borrow_date->format('d M Y') }}
                                </td>

                                <!-- Rencana Kembali -->
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 font-medium">
                                    {{ $borrowing->expected_return_date->format('d M Y') }}
                                </td>

                                <!-- Status (Hanya Dipinjam & Dikembalikan) -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($borrowing->status === 'returned')
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 text-xs font-bold rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-600"></span>
                                            Dikembalikan
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 text-xs font-bold rounded-full bg-blue-50 text-blue-700 border border-blue-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-blue-600 animate-pulse"></span>
                                            Sedang Dipinjam
                                        </span>
                                    @endif
                                </td>

                                <!-- Aksi -->
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                                    <div class="flex items-center justify-end gap-2">
                                        @if($borrowing->status !== 'returned' && (Auth::user()->isAdmin() || Auth::user()->isLaboran() || Auth::user()->isSuperAdmin()))
                                            <a href="{{ route('borrowings.edit', $borrowing) }}" class="inline-flex items-center gap-1 px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-xs font-bold shadow-sm transition-all active:scale-95" title="Proses Pengembalian Barang">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                                <span>Kembalikan</span>
                                            </a>
                                        @endif

                                        <a href="{{ route('borrowings.show', $borrowing) }}" class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg text-xs font-semibold transition-colors">
                                            Detail
                                        </a>

                                        @if($borrowing->status === 'returned' && (Auth::user()->isAdmin() || Auth::user()->isLaboran() || Auth::user()->isSuperAdmin()))
                                            <a href="{{ route('borrowings.edit', $borrowing) }}" class="p-1.5 text-gray-400 hover:text-gray-700 rounded-lg hover:bg-gray-100 transition-colors" title="Edit Catatan / Foto">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                </svg>
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <div class="w-16 h-16 mx-auto mb-3 rounded-2xl bg-slate-50 flex items-center justify-center text-slate-400 border border-slate-200">
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                                        </svg>
                                    </div>
                                    <p class="text-sm font-semibold text-slate-700">Tidak Ada Data Peminjaman</p>
                                    <p class="text-xs text-slate-400 mt-0.5">Belum ada aktivitas peminjaman aset yang tercatat.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Mobile View (Symmetrical Card) -->
        <div class="lg:hidden space-y-3.5">
            @forelse($borrowings as $borrowing)
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-4 space-y-3">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-sm font-bold text-gray-900">{{ $borrowing->asset->brand }} - {{ $borrowing->asset->model }}</p>
                            <div class="flex items-center gap-1.5 mt-0.5">
                                <span class="text-xs text-gray-500">{{ $borrowing->user->name }}</span>
                                @if($borrowing->borrow_photo || $borrowing->return_photo)
                                    <span class="inline-flex items-center gap-0.5 text-[10px] text-blue-700 bg-blue-50 border border-blue-200 px-1.5 py-0.2 rounded font-medium">
                                        Foto
                                    </span>
                                @endif
                            </div>
                        </div>

                        @if($borrowing->status === 'returned')
                            <span class="px-2.5 py-1 text-[11px] font-bold rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200">
                                Dikembalikan
                            </span>
                        @else
                            <span class="px-2.5 py-1 text-[11px] font-bold rounded-full bg-blue-50 text-blue-700 border border-blue-200">
                                Dipinjam
                            </span>
                        @endif
                    </div>

                    <div class="grid grid-cols-2 gap-2 text-xs text-gray-600 bg-gray-50 p-2.5 rounded-xl">
                        <div>
                            <span class="text-gray-400 text-[10px] block">Tanggal Pinjam:</span>
                            <span class="font-medium text-gray-800">{{ $borrowing->borrow_date->format('d M Y') }}</span>
                        </div>
                        <div>
                            <span class="text-gray-400 text-[10px] block">Rencana Kembali:</span>
                            <span class="font-medium text-gray-800">{{ $borrowing->expected_return_date->format('d M Y') }}</span>
                        </div>
                    </div>
                    
                    <div class="flex gap-2 pt-1 border-t border-gray-100">
                        @if($borrowing->status !== 'returned' && (Auth::user()->isAdmin() || Auth::user()->isLaboran() || Auth::user()->isSuperAdmin()))
                            <a href="{{ route('borrowings.edit', $borrowing) }}" class="flex-1 text-center px-3 py-2 text-xs font-bold text-white bg-emerald-600 hover:bg-emerald-700 rounded-xl shadow-sm">
                                Kembalikan
                            </a>
                        @endif
                        <a href="{{ route('borrowings.show', $borrowing) }}" class="flex-1 text-center px-3 py-2 text-xs font-semibold text-slate-700 bg-slate-100 hover:bg-slate-200 rounded-xl">
                            Detail
                        </a>
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-8 text-center text-gray-500 text-xs">
                    Tidak ada data peminjaman
                </div>
            @endforelse
        </div>

        <div class="mt-6">
            {{ $borrowings->links() }}
        </div>
    </div>
</x-app-layout>
