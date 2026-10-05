<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <!-- Header -->
        <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Record Maintenance</h1>
                <p class="text-sm text-gray-600 mt-1">Kelola dan pantau riwayat pemeliharaan aset</p>
            </div>
            <a href="{{ route('maintenance.create') }}" class="inline-flex items-center justify-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm font-medium shadow-sm">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Tambah Maintenance
            </a>
        </div>

        @if (session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
                @php session()->forget('success'); @endphp
            @endif

            <!-- Filter Section -->
            <div class="bg-white overflow-visible shadow-sm rounded-lg mb-6">
                <div class="p-4 sm:p-6 overflow-visible">
                    <form method="GET" action="{{ route('maintenance.index') }}">
                        <!-- Filter Row 1: Search and Dropdowns -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-4 relative z-10">
                            <!-- Search -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Pencarian</label>
                                <input type="text" name="search" value="{{ request('search') }}" 
                                       placeholder="Cari kode aset, merek, atau serial..." 
                                       class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition">
                            </div>

                            <!-- Searchable Asset Dropdown -->
                            <div x-data="{
                                open: false,
                                search: '',
                                selected: '{{ $assets->firstWhere('id', request('asset_id'))->label ?? '' }}',
                                selectedId: '{{ request('asset_id') ?? '' }}',
                                currentPage: 1,
                                perPage: 8,
                                items: @js($assets->map(fn($a) => ['id' => $a->id, 'label' => $a->label])),
                                get filteredItems() {
                                    return this.items.filter(item => 
                                        item.label.toLowerCase().includes(this.search.toLowerCase())
                                    );
                                },
                                get totalPages() {
                                    return Math.ceil(this.filteredItems.length / this.perPage);
                                },
                                get paginatedItems() {
                                    const start = (this.currentPage - 1) * this.perPage;
                                    return this.filteredItems.slice(start, start + this.perPage);
                                },
                                selectItem(item) {
                                    this.selected = item.label;
                                    this.selectedId = item.id;
                                    this.open = false;
                                    this.search = '';
                                    this.currentPage = 1;
                                },
                                clearSelection() {
                                    this.selected = '';
                                    this.selectedId = '';
                                }
                            }" @click.away="open = false" class="relative">
                                
                                <label class="block text-sm font-medium text-gray-700 mb-2">Aset</label>
                                
                                <input type="hidden" name="asset_id" x-model="selectedId">
                                
                                <button type="button" @click="open = !open" class="w-full px-4 py-2 text-left bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors flex items-center justify-between hover:bg-gray-50">
                                    <span x-text="selected || 'Semua Aset'" :class="selected ? 'text-gray-900' : 'text-gray-500'" class="truncate"></span>
                                    <svg class="w-5 h-5 text-gray-400 transition-transform flex-shrink-0" :class="{'rotate-180': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>

                                <div x-show="open" 
                                     x-transition:enter="transition ease-out duration-200"
                                     x-transition:enter-start="opacity-0 -translate-y-2"
                                     x-transition:enter-end="opacity-100 translate-y-0"
                                     x-transition:leave="transition ease-in duration-150"
                                     x-transition:leave-start="opacity-100 translate-y-0"
                                     x-transition:leave-end="opacity-0 -translate-y-2"
                                     class="absolute left-0 right-0 z-[9999] mt-2 bg-white border border-gray-300 rounded-lg shadow-2xl"
                                     style="min-width: 280px;">
                                    <div class="p-3 border-b border-gray-200">
                                        <div class="relative">
                                            <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                            </svg>
                                            <input type="text" x-model="search" @input="currentPage = 1" placeholder="Cari aset..." class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                        </div>
                                    </div>

                                    <div class="max-h-64 overflow-y-auto">
                                        <button type="button" @click="clearSelection(); open = false;" class="w-full px-4 py-3 text-left hover:bg-blue-50 transition-colors">
                                            <span class="text-gray-700 hover:text-blue-600 font-medium">Semua Aset</span>
                                        </button>
                                        
                                        <template x-if="paginatedItems.length > 0">
                                            <div>
                                                <template x-for="item in paginatedItems" :key="item.id">
                                                    <button type="button" @click="selectItem(item)" class="w-full px-4 py-2.5 text-left hover:bg-blue-50 transition-colors flex items-center justify-between group">
                                                        <span x-text="item.label" class="text-gray-700 group-hover:text-blue-600 font-medium text-sm flex-1 text-left"></span>
                                                        <svg x-show="selected === item.label" class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                                        </svg>
                                                    </button>
                                                </template>
                                            </div>
                                        </template>
                                        
                                        <template x-if="paginatedItems.length === 0 && search !== ''">
                                            <div class="px-4 py-8 text-center text-gray-500">
                                                <svg class="w-12 h-12 mx-auto mb-2 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                                </svg>
                                                <p class="text-sm">Tidak ada hasil ditemukan</p>
                                            </div>
                                        </template>
                                    </div>

                                    <div x-show="filteredItems.length > perPage" class="p-3 border-t border-gray-200 flex items-center justify-between bg-gray-50">
                                        <div class="text-xs text-gray-600">
                                            <span x-text="`${((currentPage-1)*perPage)+1}-${Math.min(currentPage*perPage, filteredItems.length)} dari ${filteredItems.length}`"></span>
                                        </div>
                                        <div class="flex items-center gap-1">
                                            <button type="button" @click="currentPage > 1 && currentPage--" :disabled="currentPage === 1" :class="currentPage === 1 ? 'text-gray-300 cursor-not-allowed' : 'text-gray-600 hover:bg-gray-200'" class="p-1.5 rounded transition-colors">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                                </svg>
                                            </button>
                                            
                                            <span class="px-2 py-1 text-xs font-medium text-gray-700" x-text="`${currentPage}/${totalPages}`"></span>
                                            
                                            <button type="button" @click="currentPage < totalPages && currentPage++" :disabled="currentPage === totalPages" :class="currentPage === totalPages ? 'text-gray-300 cursor-not-allowed' : 'text-gray-600 hover:bg-gray-200'" class="p-1.5 rounded transition-colors">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Searchable Technician Dropdown -->
                            <div x-data="{
                                open: false,
                                search: '',
                                selected: '{{ $technicians->firstWhere('id', request('technician_id'))->name ?? '' }}',
                                selectedId: '{{ request('technician_id') ?? '' }}',
                                currentPage: 1,
                                perPage: 8,
                                items: @js($technicians->map(fn($t) => ['id' => $t->id, 'name' => $t->name])),
                                get filteredItems() {
                                    return this.items.filter(item => 
                                        item.name.toLowerCase().includes(this.search.toLowerCase())
                                    );
                                },
                                get totalPages() {
                                    return Math.ceil(this.filteredItems.length / this.perPage);
                                },
                                get paginatedItems() {
                                    const start = (this.currentPage - 1) * this.perPage;
                                    return this.filteredItems.slice(start, start + this.perPage);
                                },
                                selectItem(item) {
                                    this.selected = item.name;
                                    this.selectedId = item.id;
                                    this.open = false;
                                    this.search = '';
                                    this.currentPage = 1;
                                },
                                clearSelection() {
                                    this.selected = '';
                                    this.selectedId = '';
                                }
                            }" @click.away="open = false" class="relative">
                                
                                <label class="block text-sm font-medium text-gray-700 mb-2">Teknisi</label>
                                
                                <input type="hidden" name="technician_id" x-model="selectedId">
                                
                                <button type="button" @click="open = !open" class="w-full px-4 py-2 text-left bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors flex items-center justify-between hover:bg-gray-50">
                                    <span x-text="selected || 'Semua Teknisi'" :class="selected ? 'text-gray-900' : 'text-gray-500'" class="truncate"></span>
                                    <svg class="w-5 h-5 text-gray-400 transition-transform flex-shrink-0" :class="{'rotate-180': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>

                                <div x-show="open" x-transition class="absolute z-[100] w-full mt-2 bg-white border border-gray-200 rounded-lg shadow-2xl max-w-full">
                                    <div class="p-3 border-b border-gray-200">
                                        <div class="relative">
                                            <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                            </svg>
                                            <input type="text" x-model="search" @input="currentPage = 1" placeholder="Cari teknisi..." class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                        </div>
                                    </div>

                                    <div class="max-h-64 overflow-y-auto">
                                        <button type="button" @click="clearSelection(); open = false;" class="w-full px-4 py-3 text-left hover:bg-blue-50 transition-colors">
                                            <span class="text-gray-700 hover:text-blue-600 font-medium">Semua Teknisi</span>
                                        </button>
                                        
                                        <template x-if="paginatedItems.length > 0">
                                            <div>
                                                <template x-for="item in paginatedItems" :key="item.id">
                                                    <button type="button" @click="selectItem(item)" class="w-full px-4 py-3 text-left hover:bg-blue-50 transition-colors flex items-center justify-between group">
                                                        <span x-text="item.name" class="text-gray-700 group-hover:text-blue-600 font-medium text-sm break-words pr-2"></span>
                                                        <svg x-show="selected === item.name" class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                                        </svg>
                                                    </button>
                                                </template>
                                            </div>
                                        </template>
                                        
                                        <template x-if="paginatedItems.length === 0 && search !== ''">
                                            <div class="px-4 py-8 text-center text-gray-500">
                                                <svg class="w-12 h-12 mx-auto mb-2 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                                </svg>
                                                <p class="text-sm">Tidak ada hasil ditemukan</p>
                                            </div>
                                        </template>
                                    </div>

                                    <div x-show="filteredItems.length > perPage" class="p-3 border-t border-gray-200 flex items-center justify-between bg-gray-50">
                                        <div class="text-xs text-gray-600">
                                            <span x-text="`${((currentPage-1)*perPage)+1}-${Math.min(currentPage*perPage, filteredItems.length)} dari ${filteredItems.length}`"></span>
                                        </div>
                                        <div class="flex items-center gap-1">
                                            <button type="button" @click="currentPage > 1 && currentPage--" :disabled="currentPage === 1" :class="currentPage === 1 ? 'text-gray-300 cursor-not-allowed' : 'text-gray-600 hover:bg-gray-200'" class="p-1.5 rounded transition-colors">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                                </svg>
                                            </button>
                                            
                                            <span class="px-2 py-1 text-xs font-medium text-gray-700" x-text="`${currentPage}/${totalPages}`"></span>
                                            
                                            <button type="button" @click="currentPage < totalPages && currentPage++" :disabled="currentPage === totalPages" :class="currentPage === totalPages ? 'text-gray-300 cursor-not-allowed' : 'text-gray-600 hover:bg-gray-200'" class="p-1.5 rounded transition-colors">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Status -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                                <select name="status" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition appearance-none bg-white">
                                    <option value="">Semua Status</option>
                                    <option value="Scheduled" {{ request('status') == 'Scheduled' ? 'selected' : '' }}>Terjadwal</option>
                                    <option value="Proses" {{ request('status') == 'Proses' ? 'selected' : '' }}>Proses</option>
                                    <option value="Completed" {{ request('status') == 'Completed' ? 'selected' : '' }}>Selesai</option>
                                    <option value="Cancelled" {{ request('status') == 'Cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                                </select>
                            </div>
                        </div>

                        <!-- Filter Row 2: Compact Date Range and Buttons -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-3 items-end">
                            <!-- Start Date -->
                            <div class="lg:col-span-1">
                                <label class="block text-xs font-medium text-gray-700 mb-1.5">Tanggal Mulai</label>
                                <input type="date" name="start_date" value="{{ request('start_date') }}" 
                                       class="w-full px-3 py-1.5 text-sm rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition">
                            </div>

                            <!-- End Date -->
                            <div class="lg:col-span-1">
                                <label class="block text-xs font-medium text-gray-700 mb-1.5">Tanggal Akhir</label>
                                <input type="date" name="end_date" value="{{ request('end_date') }}" 
                                       class="w-full px-3 py-1.5 text-sm rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition">
                            </div>

                            <!-- Buttons -->
                            <div class="lg:col-span-4 flex flex-col sm:flex-row gap-2">
                                <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg inline-flex items-center justify-center transition text-sm">
                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                    </svg>
                                    Filter
                                </button>
                                <a href="{{ route('maintenance.index') }}" class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg inline-flex items-center justify-center transition text-sm">
                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                    </svg>
                                    Reset
                                </a>
                                <a href="{{ route('maintenance.create') }}" class="flex-1 bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg inline-flex items-center justify-center transition">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                        </svg>
                                        Tambah Maintenance
                                    </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Quick Stats - Moved Here (Dynamic based on filters) -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 mb-6">
                <div class="bg-white overflow-hidden shadow-sm rounded-lg p-4">
                    <div class="text-gray-500 text-xs mb-1">Total Terjadwal</div>
                    <div class="text-2xl font-bold text-blue-600">
                        {{ $stats['scheduled'] }}
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm rounded-lg p-4">
                    <div class="text-gray-500 text-xs mb-1">Selesai</div>
                    <div class="text-2xl font-bold text-green-600">
                        {{ $stats['completed'] }}
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm rounded-lg p-4">
                    <div class="text-gray-500 text-xs mb-1">Tertinggal</div>
                    <div class="text-2xl font-bold text-red-600">
                        {{ $stats['overdue'] }}
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm rounded-lg p-4">
                    <div class="text-gray-500 text-xs mb-1">Biaya Bulan Ini</div>
                    <div class="text-xl font-bold text-purple-600">
                        Rp {{ number_format($stats['cost_this_month'], 0, ',', '.') }}
                    </div>
                </div>
            </div>

            <!-- Quick Links -->
            <div class="flex flex-col sm:flex-row gap-3 mb-6">
                <a href="{{ route('maintenance.upcoming') }}" class="flex-1 bg-blue-100 hover:bg-blue-200 text-blue-700 px-4 py-2 rounded-lg inline-flex items-center justify-center transition text-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    Jadwal Mendatang
                </a>
                <a href="{{ route('maintenance.overdue') }}" class="flex-1 bg-red-100 hover:bg-red-200 text-red-700 px-4 py-2 rounded-lg inline-flex items-center justify-center transition text-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    Jadwal Tertinggal
                </a>
            </div>

            <!-- Maintenance Table - Desktop View -->
            <div class="hidden lg:block bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aset</th>
                                    <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tgl Terjadwal</th>
                                    <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tgl Pelaksanaan</th>
                                    <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tgl Penerimaan</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Teknisi</th>
                                    <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Biaya</th>
                                    <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($maintenances as $maintenance)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-3">
                                            <div class="font-medium text-gray-900 text-sm">{{ $maintenance->asset->asset_code }}</div>
                                            <div class="text-xs text-gray-500">
                                                {{ $maintenance->asset->brand }} {{ $maintenance->asset->model }}
                                            </div>
                                            <div class="text-xs text-gray-400">
                                                {{ $maintenance->asset->assetType->name ?? 'N/A' }}
                                            </div>
                                        </td>
                                        <td class="px-3 py-3 whitespace-nowrap text-sm text-gray-900">
                                            {{ $maintenance->schedule_date->format('d M Y') }}
                                        </td>
                                        <td class="px-3 py-3 whitespace-nowrap text-sm text-gray-900">
                                            {{ $maintenance->performed_date ? $maintenance->performed_date->format('d M Y') : '-' }}
                                        </td>
                                        <td class="px-3 py-3 whitespace-nowrap text-sm text-gray-900">
                                            @if($maintenance->tanggal_penerimaan_barang)
                                                <div class="flex items-center">
                                                    <svg class="w-4 h-4 mr-1 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                    <span class="text-xs">{{ $maintenance->tanggal_penerimaan_barang->format('d M Y') }}</span>
                                                </div>
                                            @else
                                                <span class="text-gray-400">-</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-900">
                                            @if($maintenance->technician_id && $maintenance->technician)
                                                <div class="flex items-center gap-2">
                                                    <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                                                    {{ $maintenance->technician->name }}
                                                </div>
                                            @elseif($maintenance->technician_name)
                                                <div class="flex items-center gap-2">
                                                    <span class="w-2 h-2 bg-blue-500 rounded-full"></span>
                                                    {{ $maintenance->technician_name }}
                                                    <span class="text-xs text-gray-500">(Manual)</span>
                                                </div>
                                            @else
                                                <span class="text-gray-400 italic">Belum ditentukan</span>
                                            @endif
                                        </td>
                                        <td class="px-3 py-3 whitespace-nowrap text-sm text-gray-900">
                                            Rp {{ number_format($maintenance->cost, 0, ',', '.') }}
                                        </td>
                                        <td class="px-3 py-3 whitespace-nowrap">
                                            @if ($maintenance->status == 'Scheduled')
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                    Terjadwal
                                                </span>
                                            @elseif ($maintenance->status == 'Proses')
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                                    Proses
                                                </span>
                                            @elseif ($maintenance->status == 'Completed')
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                    Selesai
                                                </span>
                                            @else
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                    Dibatalkan
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm font-medium">
                                            <div class="flex gap-2">
                                                <a href="{{ route('maintenance.show', $maintenance) }}" class="text-blue-600 hover:text-blue-900 text-xs">Lihat</a>
                                                <a href="{{ route('maintenance.edit', $maintenance) }}" class="text-indigo-600 hover:text-indigo-900 text-xs">Edit</a>
                                                @if ($maintenance->status == 'Scheduled' || $maintenance->status == 'Proses')
                                                    <button type="button" onclick="openCompleteModal({{ $maintenance->id }}, '{{ $maintenance->asset->asset_code }}')" class="text-green-600 hover:text-green-900 text-xs">Selesai</button>
                                                @endif
                                                <button type="button" onclick="openDeleteModal({{ $maintenance->id }}, '{{ $maintenance->asset->asset_code }}')" class="text-red-600 hover:text-red-900 text-xs">Hapus</button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="px-6 py-12 text-center">
                                            <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                            </svg>
                                            <p class="text-gray-500 text-sm">Tidak ada record maintenance yang ditemukan.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $maintenances->links() }}
                    </div>
                </div>
            </div>

            <!-- Maintenance Cards - Mobile View -->
            <div class="lg:hidden space-y-4">
                @forelse ($maintenances as $maintenance)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
                        <!-- Header -->
                        <div class="flex justify-between items-start mb-3">
                            <div>
                                <p class="text-sm font-bold text-gray-900">{{ $maintenance->asset->asset_code }}</p>
                                <p class="text-xs text-gray-500">{{ $maintenance->asset->brand }} {{ $maintenance->asset->model }}</p>
                                <p class="text-xs text-gray-400 mt-1">{{ $maintenance->asset->assetType->name ?? 'N/A' }}</p>
                            </div>
                            @if ($maintenance->status == 'Scheduled')
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                    Terjadwal
                                </span>
                            @elseif ($maintenance->status == 'Proses')
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                    Proses
                                </span>
                            @elseif ($maintenance->status == 'Completed')
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                    Selesai
                                </span>
                            @else
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                    Dibatalkan
                                </span>
                            @endif
                        </div>

                        <!-- Details -->
                        <div class="space-y-2 mb-3">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Tanggal Terjadwal:</span>
                                <span class="font-medium text-gray-900">{{ $maintenance->schedule_date->format('d M Y') }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Tanggal Pelaksanaan:</span>
                                <span class="font-medium text-gray-900">{{ $maintenance->performed_date ? $maintenance->performed_date->format('d M Y') : '-' }}</span>
                            </div>
                            @if($maintenance->tanggal_penerimaan_barang)
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Tanggal Penerimaan:</span>
                                    <span class="font-semibold text-green-700 flex items-center">
                                        <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        {{ $maintenance->tanggal_penerimaan_barang->format('d M Y') }}
                                    </span>
                                </div>
                            @endif
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Teknisi:</span>
                                <<span class="font-medium text-gray-900">
                                @if($maintenance->technician_id && $maintenance->technician)
                                    {{ $maintenance->technician->name }}
                                @elseif($maintenance->technician_name)
                                    {{ $maintenance->technician_name }}
                                @else
                                    <span class="text-gray-400 italic">Belum ditentukan</span>
                                @endif
                            </span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Biaya:</span>
                                <span class="font-semibold text-green-700">Rp {{ number_format($maintenance->cost, 0, ',', '.') }}</span>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="flex flex-col xs:flex-row gap-2 pt-3 border-t border-gray-200">
                            <a href="{{ route('maintenance.show', $maintenance) }}" class="flex-1 text-center px-3 py-2 text-sm font-medium text-blue-600 bg-blue-50 rounded-lg hover:bg-blue-100 transition">
                                Lihat
                            </a>
                            <a href="{{ route('maintenance.edit', $maintenance) }}" class="flex-1 text-center px-3 py-2 text-sm font-medium text-indigo-600 bg-indigo-50 rounded-lg hover:bg-indigo-100 transition">
                                Edit
                            </a>
                            @if ($maintenance->status == 'Scheduled' || $maintenance->status == 'Proses')
                                <button onclick="openCompleteModal({{ $maintenance->id }}, '{{ $maintenance->asset->asset_code }}')" class="flex-1 px-3 py-2 text-sm font-medium text-green-600 bg-green-50 rounded-lg hover:bg-green-100 transition">
                                    Selesai
                                </button>
                            @endif
                            <button onclick="openDeleteModal({{ $maintenance->id }}, '{{ $maintenance->asset->asset_code }}')" class="flex-1 px-3 py-2 text-sm font-medium text-red-600 bg-red-50 rounded-lg hover:bg-red-100 transition">
                                Hapus
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
                        <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        <p class="text-gray-500 text-sm">Tidak ada record maintenance yang ditemukan.</p>
                    </div>
                @endforelse

                <!-- Pagination for Mobile -->
                <div class="mt-6">
                    {{ $maintenances->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" style="display: none;" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-xl shadow-xl max-w-md w-full">
            <div class="p-6">
                <div class="flex items-center justify-center w-12 h-12 mx-auto bg-red-100 rounded-full mb-4">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 text-center mb-2">Konfirmasi Hapus</h3>
                <p class="text-sm text-gray-600 text-center mb-6">
                    Apakah Anda yakin ingin menghapus maintenance record untuk aset <strong id="deleteAssetCode"></strong>? Tindakan ini tidak dapat dibatalkan.
                </p>
                <form id="deleteForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="flex flex-col sm:flex-row gap-3">
                        <button type="button" onclick="closeDeleteModal()" class="flex-1 px-4 py-2.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors font-medium">
                            Batal
                        </button>
                        <button type="submit" class="flex-1 px-4 py-2.5 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors font-medium">
                            Ya, Hapus
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Complete Confirmation Modal -->
    <div id="completeModal" style="display: none;" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-xl shadow-xl max-w-md w-full">
            <div class="p-6">
                <div class="flex items-center justify-center w-12 h-12 mx-auto bg-green-100 rounded-full mb-4">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 text-center mb-2">Tandai Selesai</h3>
                <p class="text-sm text-gray-600 text-center mb-6">
                    Tandai maintenance untuk aset <strong id="completeAssetCode"></strong> sebagai selesai? Tanggal pelaksanaan akan diatur ke hari ini.
                </p>
                <form id="completeForm" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="flex flex-col sm:flex-row gap-3">
                        <button type="button" onclick="closeCompleteModal()" class="flex-1 px-4 py-2.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors font-medium">
                            Batal
                        </button>
                        <button type="submit" class="flex-1 px-4 py-2.5 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-medium">
                            Ya, Selesai
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    </div>

    <script>
        // Delete Modal Functions
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

        // Complete Modal Functions
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

        // Close modals when clicking outside
        document.getElementById('deleteModal').addEventListener('click', function(e) {
            if (e.target === this) closeDeleteModal();
        });

        document.getElementById('completeModal').addEventListener('click', function(e) {
            if (e.target === this) closeCompleteModal();
        });

        // Close modals with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeDeleteModal();
                closeCompleteModal();
            }
        });
    </script>
</x-app-layout>