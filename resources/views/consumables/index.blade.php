<x-app-layout :title="'Daftar Barang Habis Pakai'">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-6">

        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 tracking-tight">Barang Habis Pakai</h1>
                <p class="text-xs sm:text-sm text-gray-500 mt-1">
                    @if(Auth::user()->isUser() && !Auth::user()->isPimpinan())
                        Katalog stok barang habis pakai (ATK, reagen, medis) & ajukan kebutuhan Anda
                    @else
                        Kelola data stok barang habis pakai, catat transaksi, dan verifikasi persetujuan pengajuan
                    @endif
                </p>
            </div>
            
            <div class="flex items-center gap-2">
                @if(Auth::user()->isAdmin() || Auth::user()->isLaboran() || Auth::user()->isSuperAdmin())
                    <a href="{{ route('consumables.create') }}" class="inline-flex items-center justify-center px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-semibold shadow-sm transition-all active:scale-[0.98]">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Tambah Barang
                    </a>
                @endif
            </div>
        </div>

        <!-- ============================================================== -->
        <!-- DAFTAR PENGAJUAN MASUK (KHUSUS PENGELOLA RUANGAN / ADMIN / SUPER ADMIN) -->
        <!-- ============================================================== -->
        @if(isset($pendingRequests) && $pendingRequests->count() > 0)
            <div class="bg-gradient-to-r from-amber-50 to-orange-50 border border-amber-200/90 rounded-2xl p-5 shadow-sm">
                <div class="flex items-center justify-between mb-3.5">
                    <div class="flex items-center gap-2.5">
                        <span class="flex h-3 w-3 relative">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-3 w-3 bg-amber-500"></span>
                        </span>
                        <h2 class="text-sm font-bold text-amber-950 uppercase tracking-wider">
                            Pengajuan Barang Masuk Menunggu ACC ({{ $pendingRequests->count() }})
                        </h2>
                    </div>
                    <span class="text-xs text-amber-800 font-semibold bg-amber-100/80 px-2.5 py-0.5 rounded-full border border-amber-300">
                        Perlu Ditindaklanjuti
                    </span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3.5">
                    @foreach($pendingRequests as $req)
                        <div class="bg-white rounded-xl p-4 border border-amber-200/70 shadow-sm flex flex-col justify-between space-y-3">
                            <div>
                                <div class="flex items-start justify-between gap-2">
                                    <div class="min-w-0">
                                        <p class="text-sm font-bold text-gray-900 truncate">{{ $req->consumable->name }}</p>
                                        <p class="text-xs text-gray-500">📍 {{ $req->consumable->location->name ?? 'Barang Umum' }}</p>
                                    </div>
                                    <span class="px-2 py-0.5 text-xs font-bold bg-blue-50 text-blue-700 border border-blue-200 rounded-lg whitespace-nowrap">
                                        {{ $req->quantity }} {{ $req->consumable->unit }}
                                    </span>
                                </div>

                                <div class="mt-2.5 p-2 bg-slate-50 rounded-lg text-xs space-y-1">
                                    <div class="flex items-center justify-between">
                                        <span class="text-gray-500">Pemohon:</span>
                                        <span class="font-bold text-gray-800">{{ $req->user->name }} ({{ ucfirst($req->user->role) }})</span>
                                    </div>
                                    @if($req->user->class_name)
                                        <div class="flex items-center justify-between">
                                            <span class="text-gray-500">Kelas:</span>
                                            <span class="font-semibold text-blue-600">{{ $req->user->class_name }}</span>
                                        </div>
                                    @endif
                                    <div class="flex items-center justify-between">
                                        <span class="text-gray-500">Tgl Ajuan:</span>
                                        <span class="font-medium text-gray-700">{{ $req->request_date->format('d M Y') }}</span>
                                    </div>
                                    @if($req->notes)
                                        <div class="pt-1 text-gray-600 border-t border-gray-200/60 italic text-[11px]">
                                            "{{ $req->notes }}"
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Tombol ACC dan Tolak -->
                            <div class="flex items-center gap-2 pt-1">
                                <form method="POST" action="{{ route('consumables.request.approve', $req) }}" class="flex-1" onsubmit="return confirm('Setujui pengajuan barang ini dan kurangi stok sebanyak {{ $req->quantity }} {{ $req->consumable->unit }}?')">
                                    @csrf
                                    <button type="submit" class="w-full py-1.5 px-3 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-xs font-bold transition-all shadow-sm active:scale-95 flex items-center justify-center gap-1">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        <span>Setujui (ACC)</span>
                                    </button>
                                </form>

                                <button type="button" onclick="openRejectConsumableModal({{ $req->id }}, '{{ addslashes($req->consumable->name) }}', '{{ addslashes($req->user->name) }}')" class="py-1.5 px-3 bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 rounded-lg text-xs font-bold transition-all active:scale-95">
                                    Tolak
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Filter & Search Bar -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-4 sm:p-5">
            <form method="GET" action="{{ route('consumables.index') }}" class="flex flex-col sm:flex-row items-center gap-3">
                <div class="relative flex-1 w-full">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama barang, kategori, atau lokasi ruangan..." class="w-full pl-10 pr-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm font-medium text-gray-800 focus:ring-2 focus:ring-blue-500 focus:bg-white transition-colors">
                    <svg class="w-4 h-4 text-gray-400 absolute left-3.5 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>

                <div class="flex items-center gap-2 w-full sm:w-auto">
                    <button type="submit" class="flex-1 sm:flex-none px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-semibold shadow-sm transition-colors">
                        Cari
                    </button>
                    @if(request('search'))
                        <a href="{{ route('consumables.index') }}" class="px-3.5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-xl text-xs font-semibold transition-colors">
                            Reset
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- ============================================================== -->
        <!-- KATALOG BARANG HABIS PAKAI (DESKTOP & MOBILE)                  -->
        <!-- ============================================================== -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-slate-50/70">
                <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wider">Katalog Barang Tersedia</h3>
                <span class="text-xs text-gray-500">Total: {{ $consumables->total() }} barang</span>
            </div>

            <!-- Desktop Table -->
            <div class="hidden lg:block overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-slate-50/50">
                        <tr>
                            <th class="px-6 py-3.5 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Nama Barang</th>
                            <th class="px-6 py-3.5 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Kategori</th>
                            <th class="px-6 py-3.5 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Sumber Dana</th>
                            <th class="px-6 py-3.5 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Stok</th>
                            <th class="px-6 py-3.5 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Lokasi Ruangan</th>
                            <th class="px-6 py-3.5 text-right text-xs font-bold text-gray-600 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @forelse($consumables as $consumable)
                            <tr class="hover:bg-slate-50/60 transition-colors">
                                <!-- Nama & Foto -->
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="h-11 w-11 rounded-xl bg-gray-100 flex-shrink-0 overflow-hidden border border-gray-200 flex items-center justify-center">
                                            @if($consumable->photo)
                                                <img class="h-full w-full object-cover" src="{{ asset('storage/' . $consumable->photo) }}" alt="{{ $consumable->name }}">
                                            @else
                                                <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                                </svg>
                                            @endif
                                        </div>
                                        <div>
                                            <div class="text-sm font-bold text-gray-900">{{ $consumable->name }}</div>
                                            @if($consumable->price)
                                                <div class="text-xs text-emerald-600 font-semibold mt-0.5">Rp {{ number_format($consumable->price, 0, ',', '.') }} / {{ $consumable->unit }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </td>

                                <!-- Kategori -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2.5 py-1 text-xs font-semibold rounded-lg bg-blue-50 text-blue-700 border border-blue-200">
                                        {{ $consumable->kategori ?? 'Umum' }}
                                    </span>
                                </td>

                                <!-- Sumber Dana -->
                                <td class="px-6 py-4 whitespace-nowrap text-xs font-medium text-gray-700">
                                    {{ $consumable->sumber_dana ?? '-' }}
                                </td>

                                <!-- Stok -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($consumable->stock > 10)
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 text-xs font-bold rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-600"></span>
                                            {{ $consumable->stock }} {{ $consumable->unit }}
                                        </span>
                                    @elseif($consumable->stock > 0)
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 text-xs font-bold rounded-full bg-amber-50 text-amber-700 border border-amber-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-600"></span>
                                            {{ $consumable->stock }} {{ $consumable->unit }} (Menipis)
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 text-xs font-bold rounded-full bg-rose-50 text-rose-700 border border-rose-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-rose-600"></span>
                                            Habis
                                        </span>
                                    @endif
                                </td>

                                <!-- Lokasi Ruangan -->
                                <td class="px-6 py-4 whitespace-nowrap text-xs font-semibold text-gray-700">
                                    📍 {{ $consumable->location->name ?? 'Aset Umum' }}
                                </td>

                                <!-- Aksi -->
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <!-- Tombol Ajukan Barang untuk Semua Pengguna (Siswa, Guru, Waka, dll) -->
                                        @if($consumable->stock > 0)
                                            <button type="button" onclick="openRequestConsumableModal({{ $consumable->id }}, '{{ addslashes($consumable->name) }}', {{ $consumable->stock }}, '{{ $consumable->unit }}', '{{ addslashes($consumable->location->name ?? 'Aset Umum') }}')" class="inline-flex items-center gap-1 px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-xs font-bold shadow-sm transition-all active:scale-95" title="Ajukan Permintaan Barang Ini">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                                </svg>
                                                <span>Ajukan</span>
                                            </button>
                                        @else
                                            <span class="px-2.5 py-1 text-[11px] font-semibold text-gray-400 bg-gray-100 rounded-lg cursor-not-allowed">
                                                Stok Habis
                                            </span>
                                        @endif

                                        <!-- Detail Link -->
                                        <a href="{{ route('consumables.show', $consumable) }}" class="px-2.5 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg text-xs font-semibold transition-colors">
                                            Detail
                                        </a>

                                        @if(Auth::user()->isAdmin() || Auth::user()->isLaboran() || Auth::user()->isSuperAdmin())
                                            <a href="{{ route('consumables.edit', $consumable) }}" class="p-1.5 text-gray-400 hover:text-amber-600 rounded-lg hover:bg-amber-50 transition-colors" title="Edit Barang">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                </svg>
                                            </a>

                                            <button type="button" onclick="openDeleteModal('{{ route('consumables.destroy', $consumable) }}', '{{ addslashes($consumable->name) }}')" class="p-1.5 text-gray-400 hover:text-rose-600 rounded-lg hover:bg-rose-50 transition-colors" title="Hapus Barang">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-gray-500 text-sm">
                                    Tidak ada data barang habis pakai yang cocok dengan pencarian Anda.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Mobile View (Symmetrical Cards) -->
            <div class="lg:hidden p-4 space-y-3.5">
                @forelse($consumables as $consumable)
                    <div class="bg-white rounded-xl p-4 border border-gray-200 shadow-sm space-y-3">
                        <div class="flex items-start justify-between gap-3">
                            <div class="flex items-center gap-3">
                                <div class="h-10 w-10 rounded-lg bg-gray-100 flex-shrink-0 overflow-hidden border border-gray-200 flex items-center justify-center">
                                    @if($consumable->photo)
                                        <img class="h-full w-full object-cover" src="{{ asset('storage/' . $consumable->photo) }}" alt="{{ $consumable->name }}">
                                    @else
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                        </svg>
                                    @endif
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-gray-900">{{ $consumable->name }}</p>
                                    <p class="text-xs text-gray-500">📍 {{ $consumable->location->name ?? 'Aset Umum' }}</p>
                                </div>
                            </div>

                            <div>
                                @if($consumable->stock > 10)
                                    <span class="px-2.5 py-0.5 text-xs font-bold rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        {{ $consumable->stock }} {{ $consumable->unit }}
                                    </span>
                                @elseif($consumable->stock > 0)
                                    <span class="px-2.5 py-0.5 text-xs font-bold rounded-full bg-amber-50 text-amber-700 border border-amber-200">
                                        {{ $consumable->stock }} {{ $consumable->unit }}
                                    </span>
                                @else
                                    <span class="px-2.5 py-0.5 text-xs font-bold rounded-full bg-rose-50 text-rose-700 border border-rose-200">
                                        Habis
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="flex items-center gap-2 pt-2 border-t border-gray-100">
                            @if($consumable->stock > 0)
                                <button type="button" onclick="openRequestConsumableModal({{ $consumable->id }}, '{{ addslashes($consumable->name) }}', {{ $consumable->stock }}, '{{ $consumable->unit }}', '{{ addslashes($consumable->location->name ?? 'Aset Umum') }}')" class="flex-1 py-2 px-3 text-xs font-bold text-white bg-blue-600 hover:bg-blue-700 rounded-xl shadow-sm text-center transition-all active:scale-95">
                                    Ajukan
                                </button>
                            @endif
                            <a href="{{ route('consumables.show', $consumable) }}" class="flex-1 py-2 px-3 text-xs font-semibold text-slate-700 bg-slate-100 hover:bg-slate-200 rounded-xl text-center transition-colors">
                                Detail
                            </a>
                            @if(Auth::user()->isAdmin() || Auth::user()->isLaboran() || Auth::user()->isSuperAdmin())
                                <a href="{{ route('consumables.edit', $consumable) }}" class="p-2 text-xs font-semibold text-amber-700 bg-amber-50 hover:bg-amber-100 border border-amber-200 rounded-xl text-center transition-colors" title="Edit Barang">
                                    <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </a>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8 text-gray-500 text-xs">
                        Tidak ada barang habis pakai
                    </div>
                @endforelse
            </div>
        </div>

        <div class="mt-4">
            {{ $consumables->links() }}
        </div>

        <!-- ============================================================== -->
        <!-- RIWAYAT PENGAJUAN SAYA (UNTUK SISWA / GURU / WAKA / USER)       -->
        <!-- ============================================================== -->
        @if(isset($myRequests) && $myRequests->count() > 0)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-slate-50/70">
                    <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wider">Riwayat Pengajuan Permintaan Saya</h3>
                    <span class="text-xs text-gray-500">{{ $myRequests->count() }} pengajuan terakhir</span>
                </div>

                <div class="divide-y divide-gray-100">
                    @foreach($myRequests as $myReq)
                        <div class="p-4 sm:px-6 flex flex-col sm:flex-row sm:items-center justify-between gap-3 hover:bg-slate-50/50 transition-colors">
                            <div class="space-y-1">
                                <div class="flex items-center gap-2">
                                    <span class="text-sm font-bold text-gray-900">{{ $myReq->consumable->name }}</span>
                                    <span class="px-2 py-0.5 text-xs font-bold bg-blue-50 text-blue-700 rounded-md border border-blue-200">
                                        {{ $myReq->quantity }} {{ $myReq->consumable->unit }}
                                    </span>
                                </div>
                                <div class="text-xs text-gray-500 flex items-center gap-2">
                                    <span>Tgl: {{ $myReq->request_date->format('d M Y') }}</span>
                                    <span>•</span>
                                    <span>📍 {{ $myReq->consumable->location->name ?? 'Aset Umum' }}</span>
                                    @if($myReq->notes)
                                        <span>•</span>
                                        <span class="italic">"{{ $myReq->notes }}"</span>
                                    @endif
                                </div>
                            </div>

                            <div class="flex items-center gap-3">
                                @if($myReq->status === 'pending')
                                    <span class="px-3 py-1 text-xs font-bold rounded-full bg-amber-50 text-amber-700 border border-amber-200">
                                        ⏳ Menunggu Persetujuan
                                    </span>
                                @elseif($myReq->status === 'approved')
                                    <div class="text-right">
                                        <span class="px-3 py-1 text-xs font-bold rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            ✅ Disetujui (ACC)
                                        </span>
                                        @if($myReq->approver)
                                            <p class="text-[11px] text-gray-500 mt-0.5">Oleh: {{ $myReq->approver->name }}</p>
                                        @endif
                                    </div>
                                @elseif($myReq->status === 'rejected')
                                    <div class="text-right">
                                        <span class="px-3 py-1 text-xs font-bold rounded-full bg-rose-50 text-rose-700 border border-rose-200">
                                            ❌ Ditolak
                                        </span>
                                        @if($myReq->approval_notes)
                                            <p class="text-[11px] text-rose-600 mt-0.5 italic">"{{ $myReq->approval_notes }}"</p>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    <!-- ============================================================== -->
    <!-- MODAL 1: FORM AJUKAN PERMINTAAN BARANG HABIS PAKAI             -->
    <!-- ============================================================== -->
    <div id="requestConsumableModal" onclick="if(event.target === this) closeRequestConsumableModal()" class="fixed inset-0 z-50 hidden overflow-y-auto bg-black/60 backdrop-blur-sm p-4 flex items-center justify-center transition-all">
        <div class="relative w-full max-w-md bg-white rounded-2xl shadow-2xl overflow-hidden border border-gray-200 flex flex-col">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-blue-50/80">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-gray-900">Ajukan Permintaan Barang</h3>
                        <p class="text-xs text-gray-500">Ajukan kebutuhan barang ke admin ruangan</p>
                    </div>
                </div>
                <button type="button" onclick="closeRequestConsumableModal()" class="p-1 rounded-lg text-gray-400 hover:text-gray-600 hover:bg-gray-100 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <form id="requestConsumableForm" method="POST" action="" class="p-6 space-y-4">
                @csrf

                <!-- Detail Barang -->
                <div class="p-3.5 bg-slate-50 border border-slate-200/80 rounded-xl text-xs space-y-1">
                    <div class="flex justify-between">
                        <span class="text-gray-500">Barang:</span>
                        <span class="font-bold text-gray-900" id="reqModalItemName">-</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Lokasi / Pengelola:</span>
                        <span class="font-semibold text-gray-800" id="reqModalLocation">-</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Stok Tersedia:</span>
                        <span class="font-bold text-emerald-600" id="reqModalStock">-</span>
                    </div>
                </div>

                <!-- Input Jumlah -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Jumlah Yang Diajukan <span class="text-rose-500">*</span></label>
                    <div class="relative">
                        <input type="number" name="quantity" id="reqModalQuantity" min="1" value="1" required class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm font-semibold">
                        <span class="absolute right-3.5 top-2.5 text-xs font-semibold text-gray-500" id="reqModalUnit">pcs</span>
                    </div>
                </div>

                <!-- Input Tanggal -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Tanggal Permintaan <span class="text-rose-500">*</span></label>
                    <input type="date" name="request_date" value="{{ date('Y-m-d') }}" required class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                </div>

                <!-- Input Keperluan / Catatan -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Keperluan / Keterangan (Opsional)</label>
                    <textarea name="notes" rows="2" placeholder="Contoh: Praktikum Farmakologi kelas XI atau kegiatan ekstrakurikuler." class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm"></textarea>
                </div>

                <div class="pt-3 flex items-center justify-end gap-3 border-t border-gray-100">
                    <button type="button" onclick="closeRequestConsumableModal()" class="px-4 py-2.5 rounded-xl border border-gray-300 text-gray-700 hover:bg-gray-50 text-sm font-semibold">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-bold shadow-md shadow-blue-500/20">
                        Kirim Pengajuan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ============================================================== -->
    <!-- MODAL 2: TOLAK PENGAJUAN BARANG HABIS PAKAI                     -->
    <!-- ============================================================== -->
    <div id="rejectConsumableModal" onclick="if(event.target === this) closeRejectConsumableModal()" class="fixed inset-0 z-50 hidden overflow-y-auto bg-black/60 backdrop-blur-sm p-4 flex items-center justify-center transition-all">
        <div class="relative w-full max-w-md bg-white rounded-2xl shadow-2xl overflow-hidden border border-gray-200 flex flex-col">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-rose-50/80">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-rose-100 text-rose-600 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-gray-900">Tolak Pengajuan Barang</h3>
                        <p class="text-xs text-gray-500">Berikan keterangan alasan penolakan</p>
                    </div>
                </div>
                <button type="button" onclick="closeRejectConsumableModal()" class="p-1 rounded-lg text-gray-400 hover:text-gray-600 hover:bg-gray-100 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <form id="rejectConsumableForm" method="POST" action="" class="p-6 space-y-4">
                @csrf
                <div class="p-3 bg-gray-50 rounded-xl text-xs text-gray-700">
                    <p><span class="font-semibold text-gray-500">Barang:</span> <span id="rejectReqItemName" class="font-bold text-gray-900">-</span></p>
                    <p class="mt-1"><span class="font-semibold text-gray-500">Pemohon:</span> <span id="rejectReqUserName" class="font-bold text-gray-900">-</span></p>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Alasan Penolakan <span class="text-rose-500">*</span></label>
                    <textarea name="reason" rows="3" required placeholder="Contoh: Stok sedang dialokasikan untuk ujian praktikum minggu depan." class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 focus:ring-2 focus:ring-rose-500 focus:border-rose-500 text-sm"></textarea>
                </div>

                <div class="pt-3 flex items-center justify-end gap-3 border-t border-gray-100">
                    <button type="button" onclick="closeRejectConsumableModal()" class="px-4 py-2.5 rounded-xl border border-gray-300 text-gray-700 hover:bg-gray-50 text-sm font-semibold">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2.5 bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-sm font-bold shadow-md shadow-rose-500/20">
                        Tolak Pengajuan
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Form Delete Tersembunyi --}}
    <form id="deleteForm" method="POST" class="hidden">
        @csrf
        @method('DELETE')
    </form>

    <script>
        function openRequestConsumableModal(consumableId, itemName, stock, unit, locationName) {
            const form = document.getElementById('requestConsumableForm');
            form.action = `/consumables/${consumableId}/request`;
            document.getElementById('reqModalItemName').innerText = itemName;
            document.getElementById('reqModalLocation').innerText = locationName;
            document.getElementById('reqModalStock').innerText = `${stock} ${unit}`;
            document.getElementById('reqModalUnit').innerText = unit;

            const qtyInput = document.getElementById('reqModalQuantity');
            qtyInput.max = stock;
            qtyInput.value = 1;

            document.getElementById('requestConsumableModal').classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        }

        function closeRequestConsumableModal() {
            document.getElementById('requestConsumableModal').classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }

        function openRejectConsumableModal(requestId, itemName, userName) {
            const form = document.getElementById('rejectConsumableForm');
            form.action = `/consumables/requests/${requestId}/reject`;
            document.getElementById('rejectReqItemName').innerText = itemName;
            document.getElementById('rejectReqUserName').innerText = userName;

            document.getElementById('rejectConsumableModal').classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        }

        function closeRejectConsumableModal() {
            document.getElementById('rejectConsumableModal').classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }

        // Tutup modal jika tombol Escape ditekan
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeRequestConsumableModal();
                closeRejectConsumableModal();
            }
        });

        function openDeleteModal(actionUrl, itemName) {
            if (confirm(`Apakah Anda yakin ingin menghapus barang "${itemName}"? Tindakan ini tidak dapat dibatalkan.`)) {
                const form = document.getElementById('deleteForm');
                form.action = actionUrl;
                form.submit();
            }
        }
    </script>
</x-app-layout>
