<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="mb-6 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Daftar Aset </h1>
                <p class="text-sm text-gray-600 mt-1">Kelola seluruh aset rumah sakit</p>
            </div>
            @can('create', App\Models\Asset::class)
                <a href="{{ route('assets.create') }}" class="inline-flex items-center justify-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors shadow-sm">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Tambah Aset
                </a>
            @endcan
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 sm:p-6 mb-6">
            <form method="GET" action="{{ route('assets.index') }}">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-4">
                    <div class="lg:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Pencarian</label>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari kode aset, merek, atau serial..." class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                    </div>
                    
                    <!-- Searchable Dropdown for Asset Type -->
                    <div x-data="{
                        open: false,
                        search: '',
                        selected: '{{ request('asset_type_id') ? $assetTypes->firstWhere('id', request('asset_type_id'))->name ?? '' : '' }}',
                        selectedId: '{{ request('asset_type_id') ?? '' }}',
                        currentPage: 1,
                        perPage: 5,
                        items: @js($assetTypes),
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
                        
                        <label class="block text-sm font-medium text-gray-700 mb-2">Jenis</label>
                        
                        <input type="hidden" name="asset_type_id" x-model="selectedId">
                        
                        <button type="button" @click="open = !open" class="w-full px-4 py-2.5 text-left bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors flex items-center justify-between hover:bg-gray-50">
                            <span x-text="selected || 'Semua Jenis'" :class="selected ? 'text-gray-900' : 'text-gray-500'"></span>
                            <svg class="w-5 h-5 text-gray-400 transition-transform" :class="{'rotate-180': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>

                        <div x-show="open" x-transition class="absolute z-50 w-full mt-2 bg-white border border-gray-200 rounded-lg shadow-xl">
                            <div class="p-3 border-b border-gray-200">
                                <div class="relative">
                                    <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                    <input type="text" x-model="search" @input="currentPage = 1" placeholder="Cari jenis aset..." class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                </div>
                            </div>

                            <div class="max-h-64 overflow-y-auto">
                                <button type="button" @click="clearSelection(); open = false;" class="w-full px-4 py-3 text-left hover:bg-blue-50 transition-colors">
                                    <span class="text-gray-700 hover:text-blue-600 font-medium">Semua Jenis</span>
                                </button>
                                
                                <template x-if="paginatedItems.length > 0">
                                    <div>
                                        <template x-for="item in paginatedItems" :key="item.id">
                                            <button type="button" @click="selectItem(item)" class="w-full px-4 py-3 text-left hover:bg-blue-50 transition-colors flex items-center justify-between group">
                                                <span x-text="item.name" class="text-gray-700 group-hover:text-blue-600 font-medium"></span>
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
                                    <span x-text="`${((currentPage-1)*perPage)+1}-${Math.min(currentPage*perPage, filteredItems.length)} of ${filteredItems.length}`"></span>
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
                    
                    <!-- Regular Status Dropdown -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                        <select name="status" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            <option value="">Semua Status</option>
                            <option value="Aktif" {{ request('status') == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="Nonaktif" {{ request('status') == 'Nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                        </select>
                    </div>
                    
                    <!-- Searchable Dropdown for Kategori -->
                    <div x-data="{
                        open: false,
                        search: '',
                        selected: '{{ request('kategori') ?? '' }}',
                        selectedKategori: '{{ request('kategori') ?? '' }}',
                        currentPage: 1,
                        perPage: 5,
                        items: @js($categories),
                        get filteredItems() {
                            return this.items.filter(item =>
                                item.toLowerCase().includes(this.search.toLowerCase())
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
                            this.selected = item;
                            this.selectedKategori = item;
                            this.open = false;
                            this.search = '';
                            this.currentPage = 1;
                        },
                        clearSelection() {
                            this.selected = '';
                            this.selectedKategori = '';
                        }
                    }" @click.away="open = false" class="relative">

                        <label class="block text-sm font-medium text-gray-700 mb-2">Kategori</label>

                        <input type="hidden" name="kategori" x-model="selectedKategori">

                        <button type="button" @click="open = !open" class="w-full px-4 py-2.5 text-left bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors flex items-center justify-between hover:bg-gray-50">
                            <span x-text="selected || 'Semua Kategori'" :class="selected ? 'text-gray-900' : 'text-gray-500'"></span>
                            <svg class="w-5 h-5 text-gray-400 transition-transform" :class="{'rotate-180': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>

                        <div x-show="open" x-transition class="absolute z-50 w-full mt-2 bg-white border border-gray-200 rounded-lg shadow-xl">
                            <div class="p-3 border-b border-gray-200">
                                <div class="relative">
                                    <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                    <input type="text" x-model="search" @input="currentPage = 1" placeholder="Cari kategori..." class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                </div>
                            </div>

                            <div class="max-h-64 overflow-y-auto">
                                <button type="button" @click="clearSelection(); open = false;" class="w-full px-4 py-3 text-left hover:bg-blue-50 transition-colors">
                                    <span class="text-gray-700 hover:text-blue-600 font-medium">Semua Kategori</span>
                                </button>

                                <template x-if="paginatedItems.length > 0">
                                    <div>
                                        <template x-for="item in paginatedItems" :key="item">
                                            <button type="button" @click="selectItem(item)" class="w-full px-4 py-3 text-left hover:bg-blue-50 transition-colors flex items-center justify-between group">
                                                <span x-text="item" class="text-gray-700 group-hover:text-blue-600 font-medium"></span>
                                                <svg x-show="selected === item" class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
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
                                    <span x-text="`${((currentPage-1)*perPage)+1}-${Math.min(currentPage*perPage, filteredItems.length)} of ${filteredItems.length}`"></span>
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
                    
                    <!-- Searchable Dropdown for Location -->
                    <div x-data="{
                        open: false,
                        search: '',
                        selected: '{{ request('location_id') ? $locations->firstWhere('id', request('location_id'))->name ?? '' : '' }}',
                        selectedId: '{{ request('location_id') ?? '' }}',
                        currentPage: 1,
                        perPage: 5,
                        items: @js($locations),
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
                        
                        <label class="block text-sm font-medium text-gray-700 mb-2">Lokasi</label>
                        
                        <input type="hidden" name="location_id" x-model="selectedId">
                        
                        <button type="button" @click="open = !open" class="w-full px-4 py-2.5 text-left bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors flex items-center justify-between hover:bg-gray-50">
                            <span x-text="selected || 'Semua Lokasi'" :class="selected ? 'text-gray-900' : 'text-gray-500'"></span>
                            <svg class="w-5 h-5 text-gray-400 transition-transform" :class="{'rotate-180': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>

                        <div x-show="open" x-transition class="absolute z-50 w-full mt-2 bg-white border border-gray-200 rounded-lg shadow-xl">
                            <div class="p-3 border-b border-gray-200">
                                <div class="relative">
                                    <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                    <input type="text" x-model="search" @input="currentPage = 1" placeholder="Cari lokasi..." class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                </div>
                            </div>

                            <div class="max-h-64 overflow-y-auto">
                                <button type="button" @click="clearSelection(); open = false;" class="w-full px-4 py-3 text-left hover:bg-blue-50 transition-colors">
                                    <span class="text-gray-700 hover:text-blue-600 font-medium">Semua Lokasi</span>
                                </button>
                                
                                <template x-if="paginatedItems.length > 0">
                                    <div>
                                        <template x-for="item in paginatedItems" :key="item.id">
                                            <button type="button" @click="selectItem(item)" class="w-full px-4 py-3 text-left hover:bg-blue-50 transition-colors flex items-center justify-between group">
                                                <span x-text="item.name" class="text-gray-700 group-hover:text-blue-600 font-medium"></span>
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
                                    <span x-text="`${((currentPage-1)*perPage)+1}-${Math.min(currentPage*perPage, filteredItems.length)} of ${filteredItems.length}`"></span>
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
                </div>

                <div class="flex flex-col sm:flex-row gap-2 mt-4">
                    <button type="submit" class="inline-flex items-center justify-center px-6 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        Filter
                    </button>
                    <a href="{{ route('assets.index') }}" class="inline-flex items-center justify-center px-6 py-2.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors font-medium">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                        Reset
                    </a>
                </div>
            </form>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 mb-6">
            <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4">
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('export.excel') }}?{{ http_build_query(request()->except('page')) }}" class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors text-sm font-medium">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Export Excel
                    </a>
                    <a href="{{ route('reports.pdf') }}?{{ http_build_query(request()->except('page')) }}" target="_blank" class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors text-sm font-medium">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                        </svg>
                        Export PDF
                    </a>
                </div>
                @can('create', App\Models\Asset::class)
                    <div class="flex flex-wrap gap-2">
                        <a href="{{ route('import.template') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors text-sm font-medium">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Download Template
                        </a>
                        <button onclick="openImportModal()" class="inline-flex items-center px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors text-sm font-medium">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                            </svg>
                            Import Excel
                        </button>
                    </div>
                @endcan
            </div>
        </div>

        <!-- Desktop Table -->
        <div class="hidden lg:block bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Kode Aset</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Jenis</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Kategori</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Merek/Model</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Lokasi</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Penanggung Jawab</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Harga</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Kondisi</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Status</th>
                            <th class="px-16 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($assets as $asset)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $asset->asset_code }}</div>
                                    <div class="text-xs text-gray-500">{{ $asset->serial_number }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                        {{ $asset->assetType->name ?? 'N/A' }} 
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $badgeColors = [
                                            'Asset TI' => 'bg-purple-100 text-purple-800 border-purple-300',
                                            'Asset Rumah Tangga' => 'bg-green-100 text-green-800 border-green-300',
                                            'Asset Transportasi' => 'bg-orange-100 text-orange-800 border-orange-300',
                                            'Asset Gizi' => 'bg-pink-100 text-pink-800 border-pink-300',
                                            'Asset Lainnya' => 'bg-gray-100 text-gray-800 border-gray-300',
                                        ];
                                        $colorClass = $badgeColors[$asset->kategori] ?? 'bg-gray-100 text-gray-800 border-gray-300';
                                    @endphp
                                    <span class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-full border {{ $colorClass }}">
                                        {{ $asset->kategori ?? 'N/A' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $asset->brand }}</div>
                                    <div class="text-xs text-gray-500">{{ $asset->model }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900">{{ $asset->location->name }}</div>
                                    <div class="text-xs text-gray-500">{{ $asset->location->floor }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($asset->penanggung_jawab)
                                        <div class="flex items-center">
                                            <svg class="w-4 h-4 mr-1.5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                            </svg>
                                            <span class="text-sm font-medium text-gray-900">{{ $asset->penanggung_jawab }}</span>
                                        </div>
                                    @else
                                        <span class="text-xs text-gray-400 italic">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($asset->price)
                                        <div class="text-sm font-semibold text-gray-900">
                                            Rp {{ number_format($asset->price, 0, ',', '.') }}
                                        </div>
                                    @else
                                        <span class="text-xs text-gray-400 italic">Tidak ada data</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full 
                                        {{ $asset->condition == 'Baik' ? 'bg-green-100 text-green-800' : ($asset->condition == 'Rusak Ringan' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                        {{ $asset->condition }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $asset->status == 'Aktif' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $asset->status }}
                                    </span>
                                </td>
                                   <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-1">
                                        {{-- Lihat: icon mata biru --}}
                                        <a href="{{ route('assets.show', $asset) }}" title="Lihat" aria-label="Lihat {{ $asset->asset_code }}"
                                        class="p-2 rounded-lg text-blue-600 hover:bg-blue-50 hover:text-blue-800 transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500">
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                                <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                                                <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                                            </svg>
                                        </a>
                                
                                        @can('update', $asset)
                                            {{-- Edit: icon pensil kuning --}}
                                            <a href="{{ route('assets.edit', $asset) }}" title="Edit" aria-label="Edit {{ $asset->asset_code }}"
                                            class="p-2 rounded-lg text-yellow-500 hover:bg-yellow-50 hover:text-yellow-600 transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-yellow-500">
                                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                                    <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/>
                                                </svg>
                                            </a>
                                        @endcan
                                
                                        @can('delete', $asset)
                                            {{-- Hapus: icon tempat sampah merah --}}
                                            <button type="button" onclick="openDeleteModal('{{ $asset->id }}', '{{ $asset->asset_code }}')" title="Hapus" aria-label="Hapus {{ $asset->asset_code }}"
                                                    class="p-2 rounded-lg text-red-600 hover:bg-red-50 hover:text-red-700 transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-red-500">
                                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                                    <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                                </svg>
                                            </button>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="px-6 py-12 text-center">
                                    <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                                    </svg>
                                    <p class="text-gray-500 text-sm">Tidak ada data aset</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Mobile View -->
        <div class="lg:hidden space-y-4">
            @forelse($assets as $asset)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
                    <div class="flex justify-between items-start mb-3">
                        <div>
                            <p class="text-sm font-bold text-gray-900">{{ $asset->asset_code }}</p>
                            <p class="text-xs text-gray-500">{{ $asset->serial_number }}</p>
                        </div>
                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                            {{ $asset->assetType->name ?? 'N/A' }}
                        </span>
                    </div>
                    
                    <div class="space-y-2 mb-3">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Kategori:</span>
                            @php
                                $badgeColors = [
                                    'Asset TI' => 'bg-purple-100 text-purple-800',
                                    'Asset Rumah Tangga' => 'bg-green-100 text-green-800',
                                    'Asset Transportasi' => 'bg-orange-100 text-orange-800',
                                    'Asset Gizi' => 'bg-pink-100 text-pink-800',
                                    'Asset Lainnya' => 'bg-gray-100 text-gray-800',
                                ];
                                $colorClass = $badgeColors[$asset->kategori] ?? 'bg-gray-100 text-gray-800';
                            @endphp
                            <span class="px-2 py-1 text-xs font-semibold rounded {{ $colorClass }}">
                                {{ $asset->kategori ?? 'N/A' }}
                            </span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Merek:</span>
                            <span class="font-medium text-gray-900">{{ $asset->brand }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Model:</span>
                            <span class="font-medium text-gray-900">{{ $asset->model }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Lokasi:</span>
                            <span class="font-medium text-gray-900">{{ $asset->location->name }}</span>
                        </div>
                        @if($asset->penanggung_jawab)
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Penanggung Jawab:</span>
                            <span class="font-semibold text-indigo-700 flex items-center">
                                <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                {{ $asset->penanggung_jawab }}
                            </span>
                        </div>
                        @endif
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Harga:</span>
                            @if($asset->price)
                                <span class="font-semibold text-green-700">Rp {{ number_format($asset->price, 0, ',', '.') }}</span>
                            @else
                                <span class="text-xs text-gray-400 italic">Tidak ada data</span>
                            @endif
                        </div>
                    </div>

                    <div class="flex gap-2 mb-3">
                        <span class="px-2 py-1 text-xs font-semibold rounded-full 
                            {{ $asset->condition == 'Baik' ? 'bg-green-100 text-green-800' : ($asset->condition == 'Rusak Ringan' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                            {{ $asset->condition }}
                        </span>
                        <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $asset->status == 'Aktif' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $asset->status }}
                        </span>
                    </div>

                    <div class="flex gap-2 pt-3 border-t border-gray-200">
                        <a href="{{ route('assets.show', $asset) }}" class="flex-1 text-center px-3 py-2 text-sm font-medium text-blue-600 bg-blue-50 rounded-lg hover:bg-blue-100">Lihat</a>
                        @can('update', $asset)
                            <a href="{{ route('assets.edit', $asset) }}" class="flex-1 text-center px-3 py-2 text-sm font-medium text-yellow-600 bg-yellow-50 rounded-lg hover:bg-yellow-100">Edit</a>
                        @endcan
                        @can('delete', $asset)
                            <button onclick="openDeleteModal('{{ $asset->id }}', '{{ $asset->asset_code }}')" class="flex-1 px-3 py-2 text-sm font-medium text-red-600 bg-red-50 rounded-lg hover:bg-red-100">Hapus</button>
                        @endcan
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
                    <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                    </svg>
                    <p class="text-gray-500 text-sm">Tidak ada data aset</p>
                </div>
            @endforelse
        </div>

        <div class="mt-6">
            @if ($assets->hasPages())
                <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
                    <div class="text-sm text-gray-600">
                        Showing {{ $assets->firstItem() }} to {{ $assets->lastItem() }} of {{ $assets->total() }} results
                    </div>

                    <nav class="flex items-center gap-1">
                        @if ($assets->onFirstPage())
                            <span class="px-3 py-2 bg-gray-800 text-gray-500 rounded-lg cursor-not-allowed">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                </svg>
                            </span>
                        @else
                            <a href="{{ $assets->previousPageUrl() }}" class="px-3 py-2 bg-gray-800 text-white rounded-lg hover:bg-gray-700 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                </svg>
                            </a>
                        @endif

                        @foreach ($assets->getUrlRange(1, $assets->lastPage()) as $page => $url)
                            @if ($page == $assets->currentPage())
                                <span class="px-4 py-2 bg-blue-600 text-white rounded-lg font-medium min-w-[44px] text-center">
                                    {{ $page }}
                                </span>
                            @else
                                <a href="{{ $url }}" class="px-4 py-2 bg-gray-800 text-gray-300 rounded-lg hover:bg-gray-700 hover:text-white transition-colors font-medium min-w-[44px] text-center">
                                    {{ $page }}
                                </a>
                            @endif
                        @endforeach

                        @if ($assets->hasMorePages())
                            <a href="{{ $assets->nextPageUrl() }}" class="px-3 py-2 bg-gray-800 text-white rounded-lg hover:bg-gray-700 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        @else
                            <span class="px-3 py-2 bg-gray-800 text-gray-500 rounded-lg cursor-not-allowed">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </span>
                        @endif
                    </nav>
                </div>
            @endif
        </div>
    </div>

    <!-- Import Modal -->
    <div id="importModal" style="display: none;" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-xl shadow-xl max-w-md w-full">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Import Data Aset</h3>
                    <button onclick="closeImportModal()" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <form method="POST" action="{{ route('import.excel') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Pilih File Excel</label>
                        <input type="file" name="file" accept=".xlsx,.xls,.csv" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <p class="mt-2 text-xs text-gray-500">Format: .xlsx, .xls, atau .csv</p>
                    </div>
                    <div class="flex gap-3">
                        <button type="button" onclick="closeImportModal()" class="flex-1 px-4 py-2.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors font-medium">
                            Batal
                        </button>
                        <button type="submit" class="flex-1 px-4 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium">
                            Import
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Modal -->
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
                    Apakah Anda yakin ingin menghapus aset <strong id="deleteAssetCode"></strong>? Tindakan ini tidak dapat dibatalkan.
                </p>
                <form id="deleteForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="flex gap-3">
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

    <script>
        function openImportModal() {
            document.getElementById('importModal').style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }

        function closeImportModal() {
            document.getElementById('importModal').style.display = 'none';
            document.body.style.overflow = '';
        }

        function openDeleteModal(assetId, assetCode) {
            document.getElementById('deleteAssetCode').textContent = assetCode;
            document.getElementById('deleteForm').action = `/assets/${assetId}`;
            document.getElementById('deleteModal').style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').style.display = 'none';
            document.body.style.overflow = '';
        }

        document.getElementById('importModal').addEventListener('click', function(e) {
            if (e.target === this) closeImportModal();
        });

        document.getElementById('deleteModal').addEventListener('click', function(e) {
            if (e.target === this) closeDeleteModal();
        });

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeImportModal();
                closeDeleteModal();
            }
        });
    </script>
</x-app-layout>