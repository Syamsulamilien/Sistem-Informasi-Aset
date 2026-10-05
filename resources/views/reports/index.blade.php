<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Laporan Aset </h1>
            <p class="text-sm text-gray-600 mt-1">Export dan analisis laporan aset rumah sakit</p>
        </div>

        <!-- Export Section -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Filter & Export Laporan</h3>
            
            <form id="reportForm" class="space-y-6">
                <!-- Row 1: Jenis Aset, Kategori, Status, Kondisi -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <!-- Searchable Dropdown for Asset Type -->
                    <div x-data="{
                        open: false,
                        search: '',
                        selected: '',
                        selectedId: '',
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
                        
                        <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Aset</label>
                        
                        <input type="hidden" name="asset_type_id" x-model="selectedId">
                        
                        <button type="button" @click="open = !open" class="w-full px-4 py-2 text-left bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors flex items-center justify-between hover:bg-gray-50">
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

                    <!-- Searchable Dropdown for Kategori -->
                    <div x-data="{
                        open: false,
                        search: '',
                        selected: '',
                        selectedKategori: '',
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

                        <button type="button" @click="open = !open" class="w-full px-4 py-2 text-left bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors flex items-center justify-between hover:bg-gray-50">
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

                    <!-- Regular Status Dropdown -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                        <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            <option value="">Semua Status</option>
                            <option value="Aktif">Aktif</option>
                            <option value="Nonaktif">Nonaktif</option>
                        </select>
                    </div>

                    <!-- Regular Condition Dropdown -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Kondisi</label>
                        <select name="condition" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            <option value="">Semua Kondisi</option>
                            <option value="Baik">Baik</option>
                            <option value="Rusak Ringan">Rusak Ringan</option>
                            <option value="Rusak Berat">Rusak Berat</option>
                        </select>
                    </div>
                </div>

                <!-- Row 2: Lokasi dan Tahun Pembelian -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <!-- Searchable Dropdown for Location -->
                    <div x-data="{
                        open: false,
                        search: '',
                        selected: '',
                        selectedId: '',
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
                        
                        <button type="button" @click="open = !open" class="w-full px-4 py-2 text-left bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors flex items-center justify-between hover:bg-gray-50">
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

                    <!-- Searchable Dropdown for Year -->
                    <div x-data="{
                        open: false,
                        search: '',
                        selected: '',
                        selectedYear: '',
                        currentPage: 1,
                        perPage: 5,
                        items: @js($years->toArray()),
                        get filteredItems() {
                            return this.items.filter(item => 
                                item.toString().includes(this.search)
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
                            this.selectedYear = item;
                            this.open = false;
                            this.search = '';
                            this.currentPage = 1;
                        },
                        clearSelection() {
                            this.selected = '';
                            this.selectedYear = '';
                        }
                    }" @click.away="open = false" class="relative">
                        
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tahun Pembelian</label>
                        
                        <input type="hidden" name="year" x-model="selectedYear">
                        
                        <button type="button" @click="open = !open" class="w-full px-4 py-2 text-left bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors flex items-center justify-between hover:bg-gray-50">
                            <span x-text="selected || 'Semua Tahun'" :class="selected ? 'text-gray-900' : 'text-gray-500'"></span>
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
                                    <input type="text" x-model="search" @input="currentPage = 1" placeholder="Cari tahun..." class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                </div>
                            </div>

                            <div class="max-h-64 overflow-y-auto">
                                <button type="button" @click="clearSelection(); open = false;" class="w-full px-4 py-3 text-left hover:bg-blue-50 transition-colors">
                                    <span class="text-gray-700 hover:text-blue-600 font-medium">Semua Tahun</span>
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
                </div>
            </form>

            <!-- Export Buttons -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4 mt-6">
                <button onclick="exportPDF()" class="flex items-center justify-center gap-3 p-6 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-all hover:shadow-lg">
                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                    </svg>
                    <div class="text-left">
                        <p class="text-xl font-bold">Export PDF</p>
                        <p class="text-sm text-red-100">Unduh laporan dalam format PDF</p>
                    </div>
                </button>

                <button onclick="exportExcel()" class="flex items-center justify-center gap-3 p-6 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-all hover:shadow-lg">
                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <div class="text-left">
                        <p class="text-xl font-bold">Export Excel</p>
                        <p class="text-sm text-green-100">Unduh laporan dalam format Excel</p>
                    </div>
                </button>
            </div>

            <!-- View PDF Button -->
            <button onclick="viewPDF()" class="w-full flex items-center justify-center gap-3 p-4 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-all hover:shadow-lg">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                </svg>
                <div class="text-left">
                    <p class="text-lg font-bold">Lihat Laporan PDF</p>
                    <p class="text-sm text-blue-100">Preview laporan tanpa download</p>
                </div>
            </button>

            <!-- Info Box -->
            <div class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                <h4 class="font-semibold text-blue-900 mb-2 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Informasi Laporan
                </h4>
                <ul class="text-sm text-blue-800 space-y-1">
                    <li>• Laporan akan mencakup semua data aset sesuai filter yang dipilih</li>
                    <li>• Format PDF cocok untuk dicetak atau dipresentasikan dengan statistik lengkap</li>
                    <li>• Format Excel cocok untuk analisis data lebih lanjut</li>
                    <li>• Gunakan tombol "Lihat Laporan PDF" untuk preview tanpa download</li>
                    <li>• Kosongkan filter untuk export semua data aset</li>
                </ul>
            </div>
        </div>
    </div>

<!-- View PDF Button -->
            <!-- ✅ TAMBAHAN: PDF Container -->
            <div id="pdfContainer" class="hidden mt-6">
                <!-- Header PDF Viewer -->
                <div class="bg-white rounded-t-lg border border-gray-200 p-4 flex items-center justify-between">
                    <h4 class="text-lg font-semibold text-gray-900 flex items-center">
                        <svg class="w-6 h-6 mr-2 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                        </svg>
                        Preview Laporan PDF
                    </h4>
                    <button onclick="hidePDF()" class="text-gray-500 hover:text-gray-700 hover:bg-gray-100 transition-colors px-3 py-1.5 rounded-lg text-sm font-medium">
                        <svg class="w-5 h-5 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        Tutup
                    </button>
                </div>

                <!-- Loading Indicator -->
                <div id="pdfLoading" class="bg-gray-50 border-x border-gray-200 p-12 text-center">
                    <svg class="animate-spin h-12 w-12 text-blue-600 mx-auto mb-4" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <p class="text-gray-600 font-medium">Memuat PDF...</p>
                </div>

                <!-- PDF Iframe -->
                <iframe id="pdfFrame" class="hidden w-full border-x border-b border-gray-200 rounded-b-lg" style="height: 800px;" src="about:blank"></iframe>
            </div>

            <!-- Info Box -->
            <div class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">



<script>
    function getFormData() {
        const form = document.getElementById('reportForm');
        const formData = new FormData(form);
        const params = new URLSearchParams();
        
        for (let [key, value] of formData.entries()) {
            if (value) {
                params.append(key, value);
            }
        }
        
        return params.toString();
    }

    // ✅ FUNGSI BARU: Tampilkan PDF di Halaman
    function viewPDF() {
        const container = document.getElementById('pdfContainer');
        const loading = document.getElementById('pdfLoading');
        const iframe = document.getElementById('pdfFrame');
        
        // Show container
        container.classList.remove('hidden');
        loading.classList.remove('hidden');
        iframe.classList.add('hidden');
        
        // Scroll ke PDF container
        container.scrollIntoView({ behavior: 'smooth', block: 'start' });
        
        // Build URL with filters
        const params = getFormData();
        const url = "{{ route('reports.view') }}" + (params ? '?' + params : '');
        
        // Load PDF in iframe
        iframe.src = url;
        
        // Hide loading when iframe loads
        iframe.onload = function() {
            loading.classList.add('hidden');
            iframe.classList.remove('hidden');
        };
    }

    // ✅ FUNGSI BARU: Sembunyikan PDF
    function hidePDF() {
        const container = document.getElementById('pdfContainer');
        const iframe = document.getElementById('pdfFrame');
        
        container.classList.add('hidden');
        iframe.src = 'about:blank';
    }

    function exportPDF() {
        const params = getFormData();
        const url = "{{ route('reports.pdf') }}" + (params ? '?' + params : '');
        window.open(url, '_blank');
    }

    function exportExcel() {
        const params = getFormData();
        const url = "{{ route('export.excel') }}" + (params ? '?' + params : '');
        window.location.href = url;
    }
</script>
</x-app-layout>