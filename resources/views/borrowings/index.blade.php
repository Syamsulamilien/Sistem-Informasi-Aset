<x-app-layout :title="'Daftar Peminjaman Aset'">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <!-- Header -->
        <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 tracking-tight">
                    @if(Auth::user()->isUser() && !Auth::user()->isPimpinan())
                        Pengajuan & Riwayat Peminjaman Aset
                    @else
                        Daftar Peminjaman Aset
                    @endif
                </h1>
                <p class="text-xs sm:text-sm text-gray-500 mt-1">
                    @if(Auth::user()->isUser() && !Auth::user()->isPimpinan())
                        Pantau status pengajuan peminjaman barang Anda dan lakukan pengembalian secara langsung
                    @else
                        Kelola persetujuan (ACC), peminjaman aktif, serta pencatatan pengembalian aset sekolah
                    @endif
                </p>
            </div>
            <a href="{{ route('borrowings.create') }}" class="inline-flex items-center justify-center px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-semibold shadow-sm transition-all active:scale-[0.98]">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                <span>{{ (Auth::user()->isUser() && !Auth::user()->isPimpinan()) ? 'Ajukan Peminjaman' : 'Catat Peminjaman' }}</span>
            </a>
        </div>

        <!-- Filter & Search Bar (Record Peminjaman User & Admin) -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-4 sm:p-5 mb-6">
            <form method="GET" action="{{ route('borrowings.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-3 items-end">
                <!-- Search Input -->
                <div class="sm:col-span-2 lg:col-span-2">
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Pencarian</label>
                    <div class="relative">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama barang, kode, peminjam..." class="w-full pl-9 pr-3.5 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm font-medium text-gray-800 focus:ring-2 focus:ring-blue-500 focus:bg-white transition-colors">
                        <svg class="w-4 h-4 text-gray-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                </div>

                <!-- Status Filter -->
                <div class="lg:col-span-1">
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Status</label>
                    <select name="status" class="w-full px-3.5 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm font-medium text-gray-800 focus:ring-2 focus:ring-blue-500 focus:bg-white transition-colors">
                        <option value="">Semua Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>⏳ Menunggu ACC</option>
                        <option value="borrowed" {{ request('status') == 'borrowed' ? 'selected' : '' }}>🔵 Dipinjam</option>
                        <option value="returned" {{ request('status') == 'returned' ? 'selected' : '' }}>🟢 Dikembalikan</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>🔴 Ditolak</option>
                    </select>
                </div>

                <!-- Date Range From -->
                <div class="lg:col-span-1">
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Dari Tanggal</label>
                    <input type="date" name="date_from" value="{{ request('date_from') }}" class="w-full px-3.5 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm font-medium text-gray-800 focus:ring-2 focus:ring-blue-500 focus:bg-white transition-colors" title="Filter dari tanggal pinjam">
                </div>

                <!-- Date Range To -->
                <div class="lg:col-span-1">
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Sampai Tanggal</label>
                    <input type="date" name="date_to" value="{{ request('date_to') }}" class="w-full px-3.5 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm font-medium text-gray-800 focus:ring-2 focus:ring-blue-500 focus:bg-white transition-colors" title="Filter sampai tanggal pinjam">
                </div>

                <!-- Buttons: Terapkan & Reset -->
                <div class="sm:col-span-2 lg:col-span-1 flex items-center gap-2">
                    <button type="submit" class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition-colors text-sm font-semibold shadow-sm flex items-center justify-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                        </svg>
                        <span>Filter</span>
                    </button>
                    @if(request()->hasAny(['search', 'status', 'date_from', 'date_to']))
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
                            <th class="px-6 py-3.5 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Status & Verifikator</th>
                            <th class="px-6 py-3.5 text-right text-xs font-bold text-gray-600 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @forelse($borrowings as $borrowing)
                            @php
                                $canManage = Auth::user()->managesLocation($borrowing->asset?->location_id);
                                $isMyBorrowing = Auth::id() === $borrowing->user_id;
                            @endphp
                            <tr class="hover:bg-slate-50/60 transition-colors">
                                <!-- Aset & Foto Tag -->
                                <td class="px-6 py-4">
                                    <div class="text-sm font-bold text-gray-900">{{ $borrowing->asset->brand }} - {{ $borrowing->asset->model }}</div>
                                    <div class="text-xs text-gray-500 flex items-center gap-2 mt-0.5">
                                        <span class="font-mono bg-gray-100 px-1.5 py-0.5 rounded text-[11px] text-gray-700">{{ $borrowing->asset->asset_code }}</span>
                                        <span class="text-gray-400">•</span>
                                        <span class="font-medium text-gray-600">📍 {{ $borrowing->asset->location->name ?? 'Aset Umum' }}</span>
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
                                    @if($borrowing->status === 'returned' && $borrowing->actual_return_date)
                                        <div class="text-[11px] text-emerald-600 font-semibold mt-0.5">
                                            Kembali: {{ $borrowing->actual_return_date->format('d M Y') }}
                                        </div>
                                    @endif
                                </td>

                                <!-- Status & Verifikator (ACC) -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($borrowing->status === 'pending')
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 text-xs font-bold rounded-full bg-amber-50 text-amber-700 border border-amber-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-600 animate-pulse"></span>
                                            Menunggu ACC
                                        </span>
                                    @elseif($borrowing->status === 'borrowed')
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 text-xs font-bold rounded-full bg-blue-50 text-blue-700 border border-blue-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-blue-600"></span>
                                            Sedang Dipinjam
                                        </span>
                                    @elseif($borrowing->status === 'returned')
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 text-xs font-bold rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-600"></span>
                                            Dikembalikan
                                        </span>
                                    @elseif($borrowing->status === 'rejected')
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 text-xs font-bold rounded-full bg-rose-50 text-rose-700 border border-rose-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-rose-600"></span>
                                            Ditolak
                                        </span>
                                    @endif

                                    <!-- Keterangan Siapa yang Menyetujui / Menolak -->
                                    @if($borrowing->approver)
                                        <div class="text-[11px] text-gray-500 mt-1 flex items-center gap-1">
                                            @if($borrowing->status === 'rejected')
                                                <span class="text-rose-600 font-medium">Oleh: {{ $borrowing->approver->name }}</span>
                                            @else
                                                <span class="text-emerald-700 font-medium bg-emerald-50 px-1.5 py-0.2 rounded border border-emerald-200">ACC: {{ $borrowing->approver->name }}</span>
                                            @endif
                                        </div>
                                    @endif

                                    @if($borrowing->status === 'rejected' && $borrowing->approval_notes)
                                        <div class="text-[10px] text-rose-600 mt-0.5 max-w-[170px] truncate" title="{{ $borrowing->approval_notes }}">
                                            "{{ $borrowing->approval_notes }}"
                                        </div>
                                    @endif
                                </td>

                                <!-- Aksi -->
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <!-- Tombol ACC & Tolak untuk Admin Pengelola Ruangan / Admin Umum -->
                                        @if($borrowing->status === 'pending' && $canManage)
                                            <!-- Form Setujui (ACC) -->
                                            <form method="POST" action="{{ route('borrowings.approve', $borrowing) }}" class="inline" onsubmit="return confirm('Setujui pengajuan peminjaman barang ini?')">
                                                @csrf
                                                <button type="submit" class="inline-flex items-center gap-1 px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-xs font-bold shadow-sm transition-all active:scale-95" title="Setujui (ACC) Peminjaman">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
                                                    </svg>
                                                    <span>ACC</span>
                                                </button>
                                            </form>

                                            <!-- Tombol Tolak Modal -->
                                            <button type="button" onclick="openRejectBorrowingModal({{ $borrowing->id }}, '{{ addslashes($borrowing->asset->brand . ' ' . $borrowing->asset->model) }}', '{{ addslashes($borrowing->user->name) }}')" class="inline-flex items-center gap-1 px-2.5 py-1.5 bg-rose-50 text-rose-700 hover:bg-rose-100 border border-rose-200 rounded-lg text-xs font-bold transition-all active:scale-95" title="Tolak Pengajuan">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
                                                <span>Tolak</span>
                                            </button>
                                        @endif

                                        <!-- Tombol Kembalikan Langsung (1-Klik Modal untuk Peminjam atau Admin) -->
                                        @if($borrowing->status === 'borrowed' && ($isMyBorrowing || $canManage))
                                            <button type="button" onclick="openDirectReturnModal({{ $borrowing->id }}, '{{ addslashes($borrowing->asset->brand . ' ' . $borrowing->asset->model) }}', '{{ $borrowing->asset->asset_code }}', '{{ $borrowing->borrow_date->format('d M Y') }}')" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-xs font-bold shadow-sm transition-all active:scale-95" title="Kembalikan Barang Langsung">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"></path>
                                                </svg>
                                                <span>Kembalikan</span>
                                            </button>
                                        @endif

                                        <!-- Detail Button -->
                                        <a href="{{ route('borrowings.show', $borrowing) }}" class="px-2.5 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg text-xs font-semibold transition-colors">
                                            Detail
                                        </a>

                                        @if($borrowing->status === 'returned' && $canManage)
                                            <a href="{{ route('borrowings.edit', $borrowing) }}" class="p-1.5 text-gray-400 hover:text-gray-700 rounded-lg hover:bg-gray-100 transition-colors" title="Edit Riwayat">
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
                                    <p class="text-xs text-slate-400 mt-0.5">Belum ada aktivitas atau data peminjaman yang cocok dengan filter Anda.</p>
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
                @php
                    $canManage = Auth::user()->managesLocation($borrowing->asset?->location_id);
                    $isMyBorrowing = Auth::id() === $borrowing->user_id;
                @endphp
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-4 space-y-3">
                    <div class="flex justify-between items-start gap-2">
                        <div>
                            <p class="text-sm font-bold text-gray-900">{{ $borrowing->asset->brand }} - {{ $borrowing->asset->model }}</p>
                            <div class="flex items-center gap-1.5 mt-0.5 text-xs text-gray-500">
                                <span>{{ $borrowing->user->name }}</span>
                                <span class="text-gray-300">•</span>
                                <span class="text-blue-600 font-medium">📍 {{ $borrowing->asset->location->name ?? 'Umum' }}</span>
                                @if($borrowing->borrow_photo || $borrowing->return_photo)
                                    <span class="inline-flex items-center gap-0.5 text-[10px] text-blue-700 bg-blue-50 border border-blue-200 px-1.5 py-0.2 rounded font-medium">
                                        Foto
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div>
                            @if($borrowing->status === 'pending')
                                <span class="px-2.5 py-1 text-[11px] font-bold rounded-full bg-amber-50 text-amber-700 border border-amber-200">
                                    Menunggu ACC
                                </span>
                            @elseif($borrowing->status === 'borrowed')
                                <span class="px-2.5 py-1 text-[11px] font-bold rounded-full bg-blue-50 text-blue-700 border border-blue-200">
                                    Dipinjam
                                </span>
                            @elseif($borrowing->status === 'returned')
                                <span class="px-2.5 py-1 text-[11px] font-bold rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    Dikembalikan
                                </span>
                            @elseif($borrowing->status === 'rejected')
                                <span class="px-2.5 py-1 text-[11px] font-bold rounded-full bg-rose-50 text-rose-700 border border-rose-200">
                                    Ditolak
                                </span>
                            @endif
                        </div>
                    </div>

                    @if($borrowing->approver)
                        <div class="text-[11px] bg-slate-50 p-2 rounded-lg border border-slate-100 flex items-center justify-between">
                            <span class="text-gray-500 font-medium">Penyetuju / ACC:</span>
                            <span class="font-bold text-gray-800">{{ $borrowing->approver->name }}</span>
                        </div>
                    @endif

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
                    
                    <div class="flex flex-wrap gap-2 pt-1 border-t border-gray-100">
                        @if($borrowing->status === 'pending' && $canManage)
                            <form method="POST" action="{{ route('borrowings.approve', $borrowing) }}" class="flex-1" onsubmit="return confirm('Setujui peminjaman ini?')">
                                @csrf
                                <button type="submit" class="w-full text-center px-3 py-2 text-xs font-bold text-white bg-emerald-600 hover:bg-emerald-700 rounded-xl shadow-sm">
                                    Setujui (ACC)
                                </button>
                            </form>
                            <button type="button" onclick="openRejectBorrowingModal({{ $borrowing->id }}, '{{ addslashes($borrowing->asset->brand) }}', '{{ addslashes($borrowing->user->name) }}')" class="flex-1 text-center px-3 py-2 text-xs font-bold text-rose-700 bg-rose-50 hover:bg-rose-100 border border-rose-200 rounded-xl">
                                Tolak
                            </button>
                        @endif

                        @if($borrowing->status === 'borrowed' && ($isMyBorrowing || $canManage))
                            <button type="button" onclick="openDirectReturnModal({{ $borrowing->id }}, '{{ addslashes($borrowing->asset->brand . ' ' . $borrowing->asset->model) }}', '{{ $borrowing->asset->asset_code }}', '{{ $borrowing->borrow_date->format('d M Y') }}')" class="flex-1 text-center px-3 py-2 text-xs font-bold text-white bg-blue-600 hover:bg-blue-700 rounded-xl shadow-sm">
                                Kembalikan
                            </button>
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

    <!-- ============================================================== -->
    <!-- MODAL 1: PENGEMBALIAN LANGSUNG (1-KLIK USER FRIENDLY RETURN)  -->
    <!-- ============================================================== -->
    <div id="directReturnModal" onclick="if(event.target === this) closeDirectReturnModal()" class="fixed inset-0 z-50 hidden overflow-y-auto bg-black/60 backdrop-blur-sm p-4 flex items-center justify-center transition-all">
        <div class="relative w-full max-w-lg bg-white rounded-2xl shadow-2xl overflow-hidden border border-gray-200 flex flex-col my-8">
            <!-- Modal Header -->
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-slate-50/80">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-gray-900">Pengembalian Barang Langsung</h3>
                        <p class="text-xs text-gray-500">Konfirmasi pengembalian barang secara langsung</p>
                    </div>
                </div>
                <button type="button" onclick="closeDirectReturnModal()" class="p-1 rounded-lg text-gray-400 hover:text-gray-600 hover:bg-gray-100 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- Form -->
            <form id="directReturnForm" method="POST" action="" enctype="multipart/form-data" class="p-6 space-y-4">
                @csrf

                <!-- Summary Card Barang -->
                <div class="p-3.5 bg-blue-50/70 border border-blue-200/80 rounded-xl flex items-center justify-between">
                    <div>
                        <p class="text-xs text-blue-600 font-semibold uppercase tracking-wider">Barang Dipinjam</p>
                        <p class="text-sm font-bold text-gray-900" id="returnItemName">-</p>
                        <p class="text-xs text-gray-500 font-mono mt-0.5" id="returnItemCode">-</p>
                    </div>
                    <div class="text-right">
                        <p class="text-[10px] text-gray-400 uppercase font-bold">Dipinjam Sejak</p>
                        <p class="text-xs font-semibold text-gray-700" id="returnBorrowDate">-</p>
                    </div>
                </div>

                <!-- Tanggal Pengembalian Riil -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Tanggal Pengembalian <span class="text-rose-500">*</span></label>
                    <input type="date" name="actual_return_date" value="{{ date('Y-m-d') }}" required class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm font-medium">
                </div>

                <!-- Foto Bukti Pengembalian Fisik (Menggunakan Komponen Kamera Terpadu) -->
                <div>
                    <x-camera-capture 
                        id="modal-return-photo" 
                        name="return_photo" 
                        label="Foto Kondisi Barang Saat Dikembalikan" 
                        :required="false"
                        helperText="Ambil foto langsung via kamera atau pilih dari galeri sebagai bukti fisik barang."
                    />
                </div>

                <!-- Catatan Kondisi Pengembalian -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Catatan Pengembalian (Opsional)</label>
                    <textarea name="notes" rows="2" placeholder="Contoh: Barang dikembalikan dalam kondisi baik dan lengkap beserta aksesorisnya." class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm"></textarea>
                </div>

                <!-- Action Buttons -->
                <div class="pt-3 flex items-center justify-end gap-3 border-t border-gray-100">
                    <button type="button" onclick="closeDirectReturnModal()" class="px-4 py-2.5 rounded-xl border border-gray-300 text-gray-700 hover:bg-gray-50 text-sm font-semibold transition-colors">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-bold shadow-md shadow-blue-500/20 transition-all active:scale-[0.98] flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <span>Konfirmasi Kembalikan</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ============================================================== -->
    <!-- MODAL 2: TOLAK PENGAJUAN PEMINJAMAN                            -->
    <!-- ============================================================== -->
    <div id="rejectBorrowingModal" onclick="if(event.target === this) closeRejectBorrowingModal()" class="fixed inset-0 z-50 hidden overflow-y-auto bg-black/60 backdrop-blur-sm p-4 flex items-center justify-center transition-all">
        <div class="relative w-full max-w-md bg-white rounded-2xl shadow-2xl overflow-hidden border border-gray-200 flex flex-col">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-rose-50/70">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-rose-100 text-rose-600 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-gray-900">Tolak Pengajuan Peminjaman</h3>
                        <p class="text-xs text-gray-500">Berikan alasan penolakan pengajuan</p>
                    </div>
                </div>
                <button type="button" onclick="closeRejectBorrowingModal()" class="p-1 rounded-lg text-gray-400 hover:text-gray-600 hover:bg-gray-100 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <form id="rejectBorrowingForm" method="POST" action="" class="p-6 space-y-4">
                @csrf
                <div class="p-3 bg-gray-50 rounded-xl text-xs text-gray-700">
                    <p><span class="font-semibold text-gray-500">Barang:</span> <span id="rejectItemName" class="font-bold text-gray-900">-</span></p>
                    <p class="mt-1"><span class="font-semibold text-gray-500">Peminjam:</span> <span id="rejectBorrowerName" class="font-bold text-gray-900">-</span></p>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Alasan Penolakan <span class="text-rose-500">*</span></label>
                    <textarea name="reason" rows="3" required placeholder="Contoh: Barang sedang disiapkan untuk ujian/praktik siswa kelas XII." class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 focus:ring-2 focus:ring-rose-500 focus:border-rose-500 text-sm"></textarea>
                </div>

                <div class="pt-3 flex items-center justify-end gap-3 border-t border-gray-100">
                    <button type="button" onclick="closeRejectBorrowingModal()" class="px-4 py-2.5 rounded-xl border border-gray-300 text-gray-700 hover:bg-gray-50 text-sm font-semibold">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2.5 bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-sm font-bold shadow-md shadow-rose-500/20">
                        Tolak Pengajuan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <x-image-modal />

    <!-- JavaScript Handlers -->
    <script>
        function openDirectReturnModal(borrowingId, itemName, itemCode, borrowDate) {
            const form = document.getElementById('directReturnForm');
            form.action = `/borrowings/${borrowingId}/return-direct`;
            document.getElementById('returnItemName').innerText = itemName;
            document.getElementById('returnItemCode').innerText = itemCode;
            document.getElementById('returnBorrowDate').innerText = borrowDate;

            // Reset camera capture if already initialized
            if (window['modal-return-photoCapture']) {
                window['modal-return-photoCapture'].resetPhoto();
            }

            document.getElementById('directReturnModal').classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        }

        function closeDirectReturnModal() {
            document.getElementById('directReturnModal').classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }

        function openRejectBorrowingModal(borrowingId, itemName, borrowerName) {
            const form = document.getElementById('rejectBorrowingForm');
            form.action = `/borrowings/${borrowingId}/reject`;
            document.getElementById('rejectItemName').innerText = itemName;
            document.getElementById('rejectBorrowerName').innerText = borrowerName;

            document.getElementById('rejectBorrowingModal').classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        }

        function closeRejectBorrowingModal() {
            document.getElementById('rejectBorrowingModal').classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }

        // Tutup modal jika tombol Escape ditekan
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeDirectReturnModal();
                closeRejectBorrowingModal();
            }
        });
    </script>
</x-app-layout>
