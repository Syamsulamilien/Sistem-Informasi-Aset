<x-app-layout>
    @php
        $statusLabel = [
            'Scheduled' => 'Terjadwal',
            'Proses'    => 'Proses',
            'Completed' => 'Selesai',
            'Cancelled' => 'Dibatalkan',
        ];
        $inputClass = 'w-full px-4 py-2.5 text-sm border border-gray-300 rounded-lg bg-white focus:ring-2 focus:ring-blue-200 focus:border-blue-500 transition';
        $labelClass = 'block text-sm font-bold text-gray-900 mb-2';
    @endphp

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        {{-- ===== Top bar: sapaan user ===== --}}
        <div class="flex justify-end pt-12 lg:pt-0 mb-4">
            <div class="flex items-center gap-3">
                <div class="text-right leading-tight">
                    <p class="text-base text-gray-700">Hallo, <span class="font-bold text-gray-900">{{ Auth::user()->name }}</span></p>
                    <p class="text-xs text-gray-500 capitalize mt-0.5">{{ Auth::user()->role }}</p>
                </div>
                <div class="w-11 h-11 rounded-full bg-gradient-to-br from-pink-400 to-orange-300 flex items-center justify-center text-white text-base font-bold">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
            </div>
        </div>

        {{-- ===== Judul + tombol tambah ===== --}}
        <div class="mb-6 flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
            <div>
                <h1 class="text-4xl font-extrabold text-gray-900">Maintenance</h1>
                <p class="text-sm text-gray-500 mt-1.5">Kelola dan pantau riwayat pemeliharaan asset</p>
            </div>
            <a href="{{ route('maintenance.create') }}" class="inline-flex items-center justify-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-base font-semibold shadow-sm transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                Tambah Maintenance
            </a>
        </div>

        {{-- ===== Filter ===== --}}
        <form method="GET" action="{{ route('maintenance.index') }}" class="bg-white rounded-xl shadow-sm p-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">

                {{-- Pencarian --}}
                <div>
                    <label class="{{ $labelClass }}">Pencarian</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari kode asset, merek, atau serial.." class="{{ $inputClass }}">
                </div>

                {{-- Aset (searchable) --}}
                <div x-data="{
                        open: false,
                        search: '',
                        selected: @js($assets->firstWhere('id', request('asset_id'))->label ?? ''),
                        selectedId: @js(request('asset_id') ?? ''),
                        items: @js($assets->map(fn($a) => ['id' => $a->id, 'label' => $a->label])->values()),
                        get filtered() { return this.items.filter(i => i.label.toLowerCase().includes(this.search.toLowerCase())); },
                        pick(i) { this.selected = i.label; this.selectedId = i.id; this.open = false; this.search = ''; },
                        clear() { this.selected = ''; this.selectedId = ''; this.open = false; }
                    }" @click.away="open = false" class="relative">
                    <label class="{{ $labelClass }}">Aset</label>
                    <input type="hidden" name="asset_id" x-model="selectedId">
                    <button type="button" @click="open = !open" class="{{ $inputClass }} flex items-center justify-between text-left">
                        <span x-text="selected || 'Semua Asset'" :class="selected ? 'text-gray-900' : 'text-gray-400'" class="truncate"></span>
                        <svg class="w-5 h-5 text-gray-400 flex-shrink-0 transition-transform" :class="{'rotate-180': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="open" x-transition class="absolute left-0 right-0 z-50 mt-1 bg-white border border-gray-200 rounded-lg shadow-xl" style="min-width: 260px;">
                        <div class="p-2.5 border-b border-gray-100">
                            <input type="text" x-model="search" placeholder="Cari aset..." class="{{ $inputClass }}">
                        </div>
                        <div class="max-h-60 overflow-y-auto">
                            <button type="button" @click="clear()" class="w-full px-4 py-2.5 text-left text-sm font-medium text-gray-700 hover:bg-blue-50">Semua Asset</button>
                            <template x-for="item in filtered" :key="item.id">
                                <button type="button" @click="pick(item)" x-text="item.label" class="w-full px-4 py-2.5 text-left text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600"></button>
                            </template>
                            <p x-show="filtered.length === 0" class="px-4 py-5 text-center text-sm text-gray-400">Tidak ada hasil</p>
                        </div>
                    </div>
                </div>

                {{-- Teknisi (searchable) --}}
                <div x-data="{
                        open: false,
                        search: '',
                        selected: @js($technicians->firstWhere('id', request('technician_id'))->name ?? ''),
                        selectedId: @js(request('technician_id') ?? ''),
                        items: @js($technicians->map(fn($t) => ['id' => $t->id, 'name' => $t->name])->values()),
                        get filtered() { return this.items.filter(i => i.name.toLowerCase().includes(this.search.toLowerCase())); },
                        pick(i) { this.selected = i.name; this.selectedId = i.id; this.open = false; this.search = ''; },
                        clear() { this.selected = ''; this.selectedId = ''; this.open = false; }
                    }" @click.away="open = false" class="relative">
                    <label class="{{ $labelClass }}">Teknisi</label>
                    <input type="hidden" name="technician_id" x-model="selectedId">
                    <button type="button" @click="open = !open" class="{{ $inputClass }} flex items-center justify-between text-left">
                        <span x-text="selected || 'Semua Teknisi'" :class="selected ? 'text-gray-900' : 'text-gray-400'" class="truncate"></span>
                        <svg class="w-5 h-5 text-gray-400 flex-shrink-0 transition-transform" :class="{'rotate-180': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="open" x-transition class="absolute left-0 right-0 z-50 mt-1 bg-white border border-gray-200 rounded-lg shadow-xl">
                        <div class="p-2.5 border-b border-gray-100">
                            <input type="text" x-model="search" placeholder="Cari teknisi..." class="{{ $inputClass }}">
                        </div>
                        <div class="max-h-60 overflow-y-auto">
                            <button type="button" @click="clear()" class="w-full px-4 py-2.5 text-left text-sm font-medium text-gray-700 hover:bg-blue-50">Semua Teknisi</button>
                            <template x-for="item in filtered" :key="item.id">
                                <button type="button" @click="pick(item)" x-text="item.name" class="w-full px-4 py-2.5 text-left text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600"></button>
                            </template>
                            <p x-show="filtered.length === 0" class="px-4 py-5 text-center text-sm text-gray-400">Tidak ada hasil</p>
                        </div>
                    </div>
                </div>

                {{-- Status --}}
                <div>
                    <label class="{{ $labelClass }}">Status</label>
                    <select name="status" class="{{ $inputClass }}">
                        <option value="">Semua Status</option>
                        <option value="Scheduled" {{ request('status') == 'Scheduled' ? 'selected' : '' }}>Terjadwal</option>
                        <option value="Proses" {{ request('status') == 'Proses' ? 'selected' : '' }}>Proses</option>
                        <option value="Completed" {{ request('status') == 'Completed' ? 'selected' : '' }}>Selesai</option>
                        <option value="Cancelled" {{ request('status') == 'Cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                    </select>
                </div>
            </div>

            {{-- Tanggal --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 mt-5">
                <div>
                    <label class="{{ $labelClass }}">Tanggal Mulai</label>
                    <input type="date" name="start_date" value="{{ request('start_date') }}" class="{{ $inputClass }}">
                </div>
                <div>
                    <label class="{{ $labelClass }}">Tanggal Akhir</label>
                    <input type="date" name="end_date" value="{{ request('end_date') }}" class="{{ $inputClass }}">
                </div>
            </div>

            {{-- Tombol --}}
            <div class="flex gap-3 mt-6">
                <button type="submit" class="inline-flex items-center px-8 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-semibold transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    Cari
                </button>
                <a href="{{ route('maintenance.index') }}" class="inline-flex items-center px-7 py-2.5 bg-[#5FB890] hover:bg-[#4fa67e] text-white rounded-lg text-sm font-semibold transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    Reset
                </a>
            </div>
        </form>

        {{-- ===== Kartu statistik ===== --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-5 mt-6">
            <div class="bg-white rounded-xl shadow-sm p-6">
                <p class="text-sm text-gray-500">Total Terjadwal</p>
                <p class="text-4xl font-extrabold text-blue-600 mt-2">{{ $stats['scheduled'] }}</p>
            </div>
            <div class="bg-white rounded-xl shadow-sm p-6">
                <p class="text-sm text-gray-500">Selesai</p>
                <p class="text-4xl font-extrabold text-green-600 mt-2">{{ $stats['completed'] }}</p>
            </div>
            <div class="bg-white rounded-xl shadow-sm p-6">
                <p class="text-sm text-gray-500">Tertinggal</p>
                <p class="text-4xl font-extrabold text-red-600 mt-2">{{ $stats['overdue'] }}</p>
            </div>
            <div class="bg-white rounded-xl shadow-sm p-6">
                <p class="text-sm text-gray-500">Biaya Bulan Ini</p>
                <p class="text-3xl font-extrabold text-[#5B6FD6] mt-2.5">Rp {{ number_format($stats['cost_this_month'], 0, ',', '.') }}</p>
            </div>
        </div>

        {{-- ===== Link cepat ===== --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mt-6">
            <a href="{{ route('maintenance.upcoming') }}" class="inline-flex items-center justify-center gap-2 py-3 bg-[#8CABF2] hover:bg-[#7898e6] text-blue-900 rounded-lg text-sm font-semibold transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                Jadwal Mendatang
            </a>
            <a href="{{ route('maintenance.overdue') }}" class="inline-flex items-center justify-center gap-2 py-3 bg-[#F28B8B] hover:bg-[#e87878] text-red-900 rounded-lg text-sm font-semibold transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                Jadwal Tertinggal
            </a>
        </div>

        {{-- ===== Tabel (desktop) ===== --}}
        <div class="hidden lg:block bg-white rounded-xl shadow-sm p-6 mt-6">
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="px-5 py-4 text-left text-[13px] font-bold text-gray-900 uppercase first:rounded-l-lg">Aset</th>
                            <th class="px-4 py-4 text-center text-[13px] font-bold text-gray-900 uppercase">Tgl Terjadwal</th>
                            <th class="px-4 py-4 text-center text-[13px] font-bold text-gray-900 uppercase">Tgl Pelaksanaan</th>
                            <th class="px-4 py-4 text-center text-[13px] font-bold text-gray-900 uppercase">Tgl Penerimaan</th>
                            <th class="px-4 py-4 text-center text-[13px] font-bold text-gray-900 uppercase">Teknisi</th>
                            <th class="px-4 py-4 text-center text-[13px] font-bold text-gray-900 uppercase">Biaya</th>
                            <th class="px-4 py-4 text-center text-[13px] font-bold text-gray-900 uppercase">Status</th>
                            <th class="px-5 py-4 text-center text-[13px] font-bold text-gray-900 uppercase last:rounded-r-lg">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($maintenances as $maintenance)
                            @php
                                $techName = $maintenance->technician->name ?? $maintenance->technician_name ?? 'Belum ditentukan';
                            @endphp
                            <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors text-gray-800">
                                <td class="px-5 py-4 whitespace-nowrap font-medium">{{ $maintenance->asset->asset_code }}</td>
                                <td class="px-4 py-4 text-center whitespace-nowrap">{{ $maintenance->schedule_date->format('d M Y') }}</td>
                                <td class="px-4 py-4 text-center whitespace-nowrap">{{ $maintenance->performed_date?->format('d M Y') ?? '-' }}</td>
                                <td class="px-4 py-4 text-center whitespace-nowrap">{{ $maintenance->tanggal_penerimaan_barang?->format('d M Y') ?? '-' }}</td>
                                <td class="px-4 py-4 text-center">{{ $techName }}</td>
                                <td class="px-4 py-4 text-center whitespace-nowrap">Rp {{ number_format($maintenance->cost, 0, ',', '.') }}</td>
                                <td class="px-4 py-4 text-center whitespace-nowrap">{{ $statusLabel[$maintenance->status] ?? $maintenance->status }}</td>
                                <td class="px-5 py-4">
                                    <div class="flex items-center justify-center gap-3">
                                        <a href="{{ route('maintenance.show', $maintenance) }}" title="Lihat" class="text-blue-600 hover:text-blue-800">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        </a>
                                        <a href="{{ route('maintenance.edit', $maintenance) }}" title="Edit" class="text-yellow-500 hover:text-yellow-600">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        </a>
                                        @if (in_array($maintenance->status, ['Scheduled', 'Proses']))
                                            {{-- Hapus tombol ini kalau mau persis 3 ikon seperti Figma --}}
                                            <button type="button" title="Tandai Selesai" onclick="openCompleteModal({{ $maintenance->id }}, '{{ $maintenance->asset->asset_code }}')" class="text-green-600 hover:text-green-700">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                            </button>
                                        @endif
                                        <button type="button" title="Hapus" onclick="openDeleteModal({{ $maintenance->id }}, '{{ $maintenance->asset->asset_code }}')" class="text-red-600 hover:text-red-700">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-14 text-center">
                                    <p class="text-gray-400 text-sm">Tidak ada record maintenance yang ditemukan.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-5">
                {{ $maintenances->links() }}
            </div>
        </div>

        {{-- ===== Kartu (mobile) ===== --}}
        <div class="lg:hidden space-y-4 mt-6">
            @forelse ($maintenances as $maintenance)
                @php
                    $techName = $maintenance->technician->name ?? $maintenance->technician_name ?? 'Belum ditentukan';
                @endphp
                <div class="bg-white rounded-xl shadow-sm p-5">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <p class="text-base font-bold text-gray-900">{{ $maintenance->asset->asset_code }}</p>
                            <p class="text-sm text-gray-500">{{ $maintenance->asset->brand }} {{ $maintenance->asset->model }}</p>
                        </div>
                        <span class="text-sm font-semibold text-gray-700">{{ $statusLabel[$maintenance->status] ?? $maintenance->status }}</span>
                    </div>

                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between"><span class="text-gray-500">Tgl Terjadwal</span><span class="font-medium text-gray-900">{{ $maintenance->schedule_date->format('d M Y') }}</span></div>
                        <div class="flex justify-between"><span class="text-gray-500">Tgl Pelaksanaan</span><span class="font-medium text-gray-900">{{ $maintenance->performed_date?->format('d M Y') ?? '-' }}</span></div>
                        <div class="flex justify-between"><span class="text-gray-500">Tgl Penerimaan</span><span class="font-medium text-gray-900">{{ $maintenance->tanggal_penerimaan_barang?->format('d M Y') ?? '-' }}</span></div>
                        <div class="flex justify-between"><span class="text-gray-500">Teknisi</span><span class="font-medium text-gray-900">{{ $techName }}</span></div>
                        <div class="flex justify-between"><span class="text-gray-500">Biaya</span><span class="font-semibold text-gray-900">Rp {{ number_format($maintenance->cost, 0, ',', '.') }}</span></div>
                    </div>

                    <div class="flex gap-2 pt-4 mt-4 border-t border-gray-100">
                        <a href="{{ route('maintenance.show', $maintenance) }}" class="flex-1 text-center px-3 py-2.5 text-sm font-medium text-blue-600 bg-blue-50 rounded-lg hover:bg-blue-100">Lihat</a>
                        <a href="{{ route('maintenance.edit', $maintenance) }}" class="flex-1 text-center px-3 py-2.5 text-sm font-medium text-yellow-600 bg-yellow-50 rounded-lg hover:bg-yellow-100">Edit</a>
                        @if (in_array($maintenance->status, ['Scheduled', 'Proses']))
                            <button type="button" onclick="openCompleteModal({{ $maintenance->id }}, '{{ $maintenance->asset->asset_code }}')" class="flex-1 px-3 py-2.5 text-sm font-medium text-green-600 bg-green-50 rounded-lg hover:bg-green-100">Selesai</button>
                        @endif
                        <button type="button" onclick="openDeleteModal({{ $maintenance->id }}, '{{ $maintenance->asset->asset_code }}')" class="flex-1 px-3 py-2.5 text-sm font-medium text-red-600 bg-red-50 rounded-lg hover:bg-red-100">Hapus</button>
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-xl shadow-sm p-12 text-center">
                    <p class="text-gray-400 text-sm">Tidak ada record maintenance yang ditemukan.</p>
                </div>
            @endforelse

            <div class="mt-4">
                {{ $maintenances->links() }}
            </div>
        </div>
    </div>

    {{-- ===== Modal Hapus ===== --}}
    <div id="deleteModal" style="display: none;" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-xl shadow-xl max-w-md w-full">
            <div class="p-6">
                <div class="flex items-center justify-center w-12 h-12 mx-auto bg-red-100 rounded-full mb-4">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 text-center mb-2">Konfirmasi Hapus</h3>
                <p class="text-sm text-gray-600 text-center mb-6">
                    Apakah Anda yakin ingin menghapus maintenance record untuk aset <strong id="deleteAssetCode"></strong>? Tindakan ini tidak dapat dibatalkan.
                </p>
                <form id="deleteForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="flex flex-col sm:flex-row gap-3">
                        <button type="button" onclick="closeDeleteModal()" class="flex-1 px-4 py-2.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors font-medium">Batal</button>
                        <button type="submit" class="flex-1 px-4 py-2.5 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors font-medium">Ya, Hapus</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ===== Modal Selesai ===== --}}
    <div id="completeModal" style="display: none;" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-xl shadow-xl max-w-md w-full">
            <div class="p-6">
                <div class="flex items-center justify-center w-12 h-12 mx-auto bg-green-100 rounded-full mb-4">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 text-center mb-2">Tandai Selesai</h3>
                <p class="text-sm text-gray-600 text-center mb-6">
                    Tandai maintenance untuk aset <strong id="completeAssetCode"></strong> sebagai selesai? Tanggal pelaksanaan akan diatur ke hari ini.
                </p>
                <form id="completeForm" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="flex flex-col sm:flex-row gap-3">
                        <button type="button" onclick="closeCompleteModal()" class="flex-1 px-4 py-2.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors font-medium">Batal</button>
                        <button type="submit" class="flex-1 px-4 py-2.5 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-medium">Ya, Selesai</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openDeleteModal(id, assetCode) {
            document.getElementById('deleteAssetCode').textContent = assetCode;
            document.getElementById('deleteForm').action = `/maintenance/${id}`;
            document.getElementById('deleteModal').style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').style.display = 'none';
            document.body.style.overflow = '';
        }

        function openCompleteModal(id, assetCode) {
            document.getElementById('completeAssetCode').textContent = assetCode;
            document.getElementById('completeForm').action = `/maintenance/${id}/complete`;
            document.getElementById('completeModal').style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }

        function closeCompleteModal() {
            document.getElementById('completeModal').style.display = 'none';
            document.body.style.overflow = '';
        }

        document.getElementById('deleteModal').addEventListener('click', function (e) {
            if (e.target === this) closeDeleteModal();
        });

        document.getElementById('completeModal').addEventListener('click', function (e) {
            if (e.target === this) closeCompleteModal();
        });

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                closeDeleteModal();
                closeCompleteModal();
            }
        });
    </script>
</x-app-layout>