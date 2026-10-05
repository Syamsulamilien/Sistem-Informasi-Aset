<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <!-- Header -->
        <div class="mb-6 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Master Jenis Aset</h1>
                <p class="text-sm text-gray-600 mt-1">Kelola jenis-jenis aset berdasarkan kategori</p>
            </div>
            <a href="{{ route('asset-types.create') }}" class="inline-flex items-center justify-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors shadow-sm">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Tambah Jenis Aset
            </a>
        </div>

        <!-- Info Box -->
        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border-l-4 border-blue-500 rounded-lg p-4 mb-6">
            <div class="flex items-start">
                <svg class="w-5 h-5 text-blue-600 mr-3 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div class="flex-1">
                    <p class="text-sm font-semibold text-blue-900 mb-1">💡 Informasi Master Jenis Aset</p>
                    <ul class="text-xs text-blue-800 space-y-1">
                        <li>• Prefix kode harus <strong>unik</strong> dan hanya boleh huruf kapital &amp; angka (contoh: PC, LTP, RTR)</li>
                        <li>• Setiap jenis aset memiliki <strong>kategori</strong> untuk pengelompokan yang lebih baik</li>
                        <li>• Prefix akan digunakan untuk generate kode aset otomatis (contoh: PC-2025-0001)</li>
                        <li>• Jenis aset yang sudah digunakan tidak bisa dihapus</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Asset Types Table - Desktop -->
        <div class="hidden lg:block bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="relative">
                <button id="scrollLeft" class="absolute left-0 top-0 bottom-0 z-10 bg-gradient-to-r from-white to-transparent px-2 hidden items-center">
                    <div class="bg-white rounded-full shadow-lg p-2 hover:bg-gray-50 transition-colors">
                        <svg class="w-5 h-5 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                    </div>
                </button>

                <button id="scrollRight" class="absolute right-0 top-0 bottom-0 z-10 bg-gradient-to-l from-white to-transparent px-2 flex items-center">
                    <div class="bg-white rounded-full shadow-lg p-2 hover:bg-gray-50 transition-colors">
                        <svg class="w-5 h-5 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </div>
                </button>

                <div id="tableContainer" class="overflow-x-auto scroll-smooth" style="scrollbar-width: none; -ms-overflow-style: none;">
                    <style>
                        #tableContainer::-webkit-scrollbar { display: none; }
                    </style>
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider whitespace-nowrap">Nama Jenis</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider whitespace-nowrap">Kategori</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider whitespace-nowrap">Prefix Kode</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider whitespace-nowrap">Contoh Kode</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider whitespace-nowrap">Jumlah Aset</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider whitespace-nowrap">Status</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider whitespace-nowrap">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($assetTypes as $type)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="flex items-start min-w-max">
                                            <div class="flex-shrink-0 mr-3">
                                                <div class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center">
                                                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                                    </svg>
                                                </div>
                                            </div>
                                            <div>
                                                <div class="text-sm font-semibold text-gray-900 whitespace-nowrap">{{ $type->name }}</div>
                                                @if($type->description)
                                                    <div class="text-xs text-gray-500 mt-1 whitespace-nowrap">{{ $type->description }}</div>
                                                @endif
                                            </div>
                                        </div>
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
                                            $colorClass = $badgeColors[$type->kategori] ?? 'bg-gray-100 text-gray-800 border-gray-300';
                                        @endphp
                                        <span class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-full border {{ $colorClass }}">
                                            {{ $type->kategori }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-3 py-1 text-xs font-mono font-bold rounded bg-blue-100 text-blue-800 border border-blue-300">
                                            {{ $type->code_prefix }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="text-xs font-mono text-gray-600">
                                            {{ $type->code_prefix }}-{{ date('Y') }}-0001
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                            </svg>
                                            {{ $type->assets_count }} Aset
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($type->is_active)
                                            <span class="inline-flex items-center px-2.5 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                                <span class="w-2 h-2 bg-green-500 rounded-full mr-1.5"></span>
                                                Aktif
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                                                <span class="w-2 h-2 bg-gray-500 rounded-full mr-1.5"></span>
                                                Nonaktif
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <div class="flex gap-2">
                                            <a href="{{ route('asset-types.edit', $type) }}" class="text-yellow-600 hover:text-yellow-800 font-medium">
                                                Edit
                                            </a>
                                            @if($type->assets_count == 0)
                                                <button onclick="openDeleteModal('{{ $type->id }}', '{{ $type->name }}')" class="text-red-600 hover:text-red-800 font-medium">
                                                    Hapus
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-12 text-center">
                                        <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                        </svg>
                                        <p class="text-gray-500 text-sm">Belum ada data jenis aset</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Asset Types Cards - Mobile -->
        <div class="lg:hidden space-y-4">
            @forelse($assetTypes as $type)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
                    <div class="flex items-start mb-3">
                        <div class="flex-shrink-0 mr-3">
                            <div class="w-12 h-12 rounded-lg bg-blue-100 flex items-center justify-center">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-sm font-bold text-gray-900">{{ $type->name }}</h3>
                            @if($type->description)
                                <p class="text-xs text-gray-500 mt-1">{{ $type->description }}</p>
                            @endif
                        </div>
                        <div class="ml-2">
                            @if($type->is_active)
                                <span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                    <span class="w-1.5 h-1.5 bg-green-500 rounded-full mr-1"></span>
                                    Aktif
                                </span>
                            @else
                                <span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                                    <span class="w-1.5 h-1.5 bg-gray-500 rounded-full mr-1"></span>
                                    Nonaktif
                                </span>
                            @endif
                        </div>
                    </div>
                    
                    <div class="space-y-2 mb-3 pl-15">
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
                                $colorClass = $badgeColors[$type->kategori] ?? 'bg-gray-100 text-gray-800';
                            @endphp
                            <span class="px-2 py-1 text-xs font-semibold rounded {{ $colorClass }}">
                                {{ $type->kategori }}
                            </span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Prefix Kode:</span>
                            <span class="font-mono font-bold text-blue-800">{{ $type->code_prefix }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Contoh Kode:</span>
                            <span class="font-mono text-xs text-gray-700">{{ $type->code_prefix }}-{{ date('Y') }}-0001</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Jumlah Aset:</span>
                            <span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                {{ $type->assets_count }} Aset
                            </span>
                        </div>
                    </div>

                    <div class="flex gap-2 pt-3 border-t border-gray-200">
                        <a href="{{ route('asset-types.edit', $type) }}" class="flex-1 text-center px-3 py-2 text-sm font-medium text-yellow-600 bg-yellow-50 rounded-lg hover:bg-yellow-100">
                            Edit
                        </a>
                        @if($type->assets_count == 0)
                            <button onclick="openDeleteModal('{{ $type->id }}', '{{ $type->name }}')" class="flex-1 px-3 py-2 text-sm font-medium text-red-600 bg-red-50 rounded-lg hover:bg-red-100">
                                Hapus
                            </button>
                        @endif
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
                    <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                    <p class="text-gray-500 text-sm">Belum ada data jenis aset</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            @if ($assetTypes->hasPages())
                <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
                    <div class="text-sm text-gray-600">
                        Showing {{ $assetTypes->firstItem() }} to {{ $assetTypes->lastItem() }} of {{ $assetTypes->total() }} results
                    </div>
                    <nav class="flex items-center gap-1">
                        @if ($assetTypes->onFirstPage())
                            <span class="px-3 py-2 bg-gray-100 text-gray-400 rounded-lg cursor-not-allowed">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                </svg>
                            </span>
                        @else
                            <a href="{{ $assetTypes->previousPageUrl() }}" class="px-3 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                </svg>
                            </a>
                        @endif
                        @foreach ($assetTypes->getUrlRange(1, $assetTypes->lastPage()) as $page => $url)
                            @if ($page == $assetTypes->currentPage())
                                <span class="px-4 py-2 bg-blue-600 text-white rounded-lg font-medium min-w-[44px] text-center">{{ $page }}</span>
                            @else
                                <a href="{{ $url }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors font-medium min-w-[44px] text-center">{{ $page }}</a>
                            @endif
                        @endforeach
                        @if ($assetTypes->hasMorePages())
                            <a href="{{ $assetTypes->nextPageUrl() }}" class="px-3 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        @else
                            <span class="px-3 py-2 bg-gray-100 text-gray-400 rounded-lg cursor-not-allowed">
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
                    Apakah Anda yakin ingin menghapus jenis aset <strong id="deleteAssetTypeName"></strong>? Tindakan ini tidak dapat dibatalkan.
                </p>
                <form id="deleteForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="flex gap-3">
                        <button type="button" onclick="closeDeleteModal()" class="flex-1 px-4 py-2.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors font-medium">Batal</button>
                        <button type="submit" class="flex-1 px-4 py-2.5 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors font-medium">Ya, Hapus</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        const tableContainer = document.getElementById('tableContainer');
        const scrollLeftBtn = document.getElementById('scrollLeft');
        const scrollRightBtn = document.getElementById('scrollRight');

        function updateScrollButtons() {
            const maxScroll = tableContainer.scrollWidth - tableContainer.clientWidth;
            const currentScroll = tableContainer.scrollLeft;
            if (currentScroll > 0) {
                scrollLeftBtn.classList.remove('hidden');
                scrollLeftBtn.classList.add('flex');
            } else {
                scrollLeftBtn.classList.remove('flex');
                scrollLeftBtn.classList.add('hidden');
            }
            if (currentScroll < maxScroll - 5) {
                scrollRightBtn.classList.remove('hidden');
                scrollRightBtn.classList.add('flex');
            } else {
                scrollRightBtn.classList.remove('flex');
                scrollRightBtn.classList.add('hidden');
            }
        }

        scrollLeftBtn.addEventListener('click', () => {
            tableContainer.scrollBy({ left: -300, behavior: 'smooth' });
        });
        scrollRightBtn.addEventListener('click', () => {
            tableContainer.scrollBy({ left: 300, behavior: 'smooth' });
        });
        tableContainer.addEventListener('scroll', updateScrollButtons);
        window.addEventListener('resize', updateScrollButtons);
        updateScrollButtons();

        function openDeleteModal(typeId, typeName) {
            document.getElementById('deleteAssetTypeName').textContent = typeName;
            document.getElementById('deleteForm').action = `/asset-types/${typeId}`;
            document.getElementById('deleteModal').style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }
        function closeDeleteModal() {
            document.getElementById('deleteModal').style.display = 'none';
            document.body.style.overflow = '';
        }
        document.getElementById('deleteModal').addEventListener('click', function(e) {
            if (e.target === this) closeDeleteModal();
        });
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') closeDeleteModal();
        });
    </script>
</x-app-layout>