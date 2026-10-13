@php
    $logoSrc = asset('images/logobinatama.png');
@endphp

<!-- Overlay Latar Belakang Gelap (Mobile) -->
<div id="sidebar-overlay" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-40 hidden lg:hidden transition-opacity duration-300"></div>

<!-- Sidebar Navigasi -->
<aside id="sidebar" class="fixed inset-y-0 left-0 transform -translate-x-full lg:translate-x-0 transition-[width,transform] duration-300 ease-in-out z-50 w-64 bg-[#142238] border-r border-slate-800 flex flex-col shadow-2xl lg:shadow-none">

    <!-- Header Logo & Nama Sekolah -->
    <div class="px-4 py-4 border-b border-white/10 flex-shrink-0">
        <!-- Expanded Mode Header -->
        <div class="sidebar-header-expanded flex items-center justify-between">
            <div class="flex items-center gap-3 min-w-0">
                <img src="{{ $logoSrc }}" alt="Logo" class="w-12 h-12 object-contain flex-shrink-0 aspect-square" width="48" height="48" loading="eager" decoding="sync" fetchpriority="high">
                <div class="leading-tight min-w-0 sidebar-brand-text">
                    <h2 class="text-base font-black text-white tracking-wider">SIASET</h2>
                    <p class="text-[11px] text-blue-200/90 font-medium truncate">SMK Kesehatan Binatama</p>
                </div>
            </div>
            
            <!-- Tombol Tutup Sidebar Khusus Mobile -->
            <button id="close-sidebar" class="lg:hidden p-1.5 rounded-lg text-slate-300 hover:text-white hover:bg-white/10 transition-colors focus:outline-none" aria-label="Tutup Menu">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <!-- Collapsed Mode Header (Hanya Logo di tengah tanpa tombol) -->
        <div class="sidebar-header-collapsed hidden items-center justify-center">
            <img src="{{ $logoSrc }}" alt="Logo" class="w-12 h-12 object-contain flex-shrink-0 aspect-square" width="48" height="48" loading="eager" decoding="sync" fetchpriority="high">
        </div>
    </div>

    <!-- Navigation Menu Items -->
    <nav class="flex-1 overflow-y-auto py-4 space-y-1">
        @php
            $baseClass = 'sidebar-item flex items-center px-4 py-2.5 mx-3 rounded-xl text-sm font-semibold transition-all duration-200';
            $activeClass = 'bg-blue-600 text-white shadow-md shadow-blue-600/30 font-bold';
            $inactiveClass = 'text-slate-300 hover:bg-white/10 hover:text-white';
        @endphp

        <!-- Dashboard -->
        <a href="{{ route('dashboard') }}" title="Dashboard" class="{{ $baseClass }} {{ request()->routeIs('dashboard') ? $activeClass : $inactiveClass }}">
            <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
            <span class="sidebar-text truncate">Dashboard</span>
        </a>

        <!-- Master Aset -->
        <a href="{{ route('asset-types.index') }}" title="Master Aset" class="{{ $baseClass }} {{ request()->routeIs('asset-types.*') ? $activeClass : $inactiveClass }}">
            <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
            <span class="sidebar-text truncate">Master Aset</span>
        </a>

        <!-- Data Aset -->
        <a href="{{ route('assets.index') }}" title="Data Aset" class="{{ $baseClass }} {{ request()->routeIs('assets.*') ? $activeClass : $inactiveClass }}">
            <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
            <span class="sidebar-text truncate">Aset</span>
        </a>

        <!-- Barang Habis Pakai -->
        <a href="{{ route('consumables.index') }}" title="Barang Habis Pakai" class="{{ $baseClass }} {{ request()->routeIs('consumables.*') ? $activeClass : $inactiveClass }}">
            <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
            <span class="sidebar-text truncate">Barang Habis Pakai</span>
        </a>

        <!-- Master Lokasi -->
        <a href="{{ route('locations.index') }}" title="Lokasi & Ruangan" class="{{ $baseClass }} {{ request()->routeIs('locations.*') ? $activeClass : $inactiveClass }}">
            <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
            <span class="sidebar-text truncate">Lokasi & Ruangan</span>
        </a>

        <!-- Maintenance / Perawatan -->
        <a href="{{ route('maintenance.index') }}" title="Maintenance" class="{{ $baseClass }} {{ request()->routeIs('maintenance.*') ? $activeClass : $inactiveClass }}">
            <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"></path></svg>
            <span class="sidebar-text truncate">Maintenance</span>
        </a>

        <!-- Peminjaman -->
        <a href="{{ route('borrowings.index') }}" title="Peminjaman" class="{{ $baseClass }} {{ request()->routeIs('borrowings.*') ? $activeClass : $inactiveClass }}">
            <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
            <span class="sidebar-text truncate">Peminjaman</span>
        </a>

        <!-- Laporan -->
        <a href="{{ route('reports.index') }}" title="Laporan" class="{{ $baseClass }} {{ request()->routeIs('reports.*') ? $activeClass : $inactiveClass }}">
            <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            <span class="sidebar-text truncate">Laporan</span>
        </a>
    </nav>

    <!-- Bagian Bawah: Settings & Keluar -->
    <div class="py-3 border-t border-white/10 space-y-1 flex-shrink-0">
        <a href="{{ route('settings') }}" title="Settings" class="{{ $baseClass }} {{ request()->routeIs('settings*') ? $activeClass : $inactiveClass }}">
            <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
            <span class="sidebar-text truncate">Settings</span>
        </a>

        <form method="POST" action="{{ route('logout') }}" class="m-0 p-0 block">
            @csrf
            <button type="submit" title="Keluar" class="{{ $baseClass }} text-rose-300 hover:bg-rose-500/20 hover:text-rose-200 text-left">
                <svg class="w-5 h-5 mr-3 flex-shrink-0 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                <span class="sidebar-text truncate">Keluar</span>
            </button>
        </form>
    </div>
</aside>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const mobileMenuButton = document.getElementById('mobile-menu-button');
        const desktopSidebarToggle = document.getElementById('desktop-sidebar-toggle');
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebar-overlay');
        const closeSidebar = document.getElementById('close-sidebar');

        // Toggle Minimize Sidebar Desktop (Hanya melalui tombol di header atas)
        function toggleDesktopSidebar() {
            const isCurrentlyCollapsed = document.documentElement.classList.toggle('sidebar-collapsed');
            try {
                localStorage.setItem('sidebar_collapsed', isCurrentlyCollapsed ? 'true' : 'false');
            } catch (e) {}
        }

        if (desktopSidebarToggle) {
            desktopSidebarToggle.addEventListener('click', toggleDesktopSidebar);
        }

        // Mobile Sidebar Controls
        function openSidebar() {
            if (sidebar) sidebar.classList.remove('-translate-x-full');
            if (overlay) overlay.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeSidebarFunc() {
            if (sidebar) sidebar.classList.add('-translate-x-full');
            if (overlay) overlay.classList.add('hidden');
            document.body.style.overflow = '';
        }

        if (mobileMenuButton) {
            mobileMenuButton.addEventListener('click', openSidebar);
        }

        if (closeSidebar) {
            closeSidebar.addEventListener('click', closeSidebarFunc);
        }

        if (overlay) {
            overlay.addEventListener('click', closeSidebarFunc);
        }

        if (sidebar) {
            sidebar.querySelectorAll('nav a').forEach(link => {
                link.addEventListener('click', () => {
                    if (window.innerWidth < 1024) closeSidebarFunc();
                });
            });
        }

        window.addEventListener('resize', () => {
            if (window.innerWidth >= 1024) {
                closeSidebarFunc();
            }
        });
    });
</script>