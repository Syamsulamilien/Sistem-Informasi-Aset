<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'SIASET') }}</title>
    <!-- Preload Logo untuk rendering instan -->
    <link rel="preload" as="image" href="{{ asset('images/logo.png') }}" fetchpriority="high">

    <!-- Script cegah flash minimize sidebar desktop -->
    <script>
        (function() {
            try {
                if (localStorage.getItem('sidebar_collapsed') === 'true') {
                    document.documentElement.classList.add('sidebar-collapsed');
                }
            } catch (e) {}
        })();
    </script>

    <!-- Inline Style untuk Anti-Flicker Collapse Desktop -->
    <style>
        @media (min-width: 1024px) {
            html.sidebar-collapsed #sidebar {
                width: 5rem !important;
            }
            html.sidebar-collapsed #main-content-wrapper {
                margin-left: 5rem !important;
            }
            html.sidebar-collapsed .sidebar-text,
            html.sidebar-collapsed .sidebar-brand-text {
                display: none !important;
            }
            html.sidebar-collapsed .sidebar-item {
                justify-content: center !important;
                padding-left: 0.5rem !important;
                padding-right: 0.5rem !important;
                margin-left: 0.5rem !important;
                margin-right: 0.5rem !important;
            }
            html.sidebar-collapsed .sidebar-item svg {
                margin-right: 0 !important;
            }
            html.sidebar-collapsed .sidebar-header-expanded {
                display: none !important;
            }
            html.sidebar-collapsed .sidebar-header-collapsed {
                display: flex !important;
            }
        }
    </style>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-50 text-gray-900 selection:bg-blue-600 selection:text-white">
    <div class="min-h-screen flex bg-gray-50">
        {{-- Sidebar --}}
        @include('layouts.sidebar')

        {{-- Main Content Wrapper --}}
        <div id="main-content-wrapper" class="flex-1 lg:ml-64 flex flex-col min-h-screen min-w-0 transition-[margin] duration-300">
            {{-- Sticky Top Header (Konsisten di semua halaman) --}}
            <header class="sticky top-0 z-30 bg-white/95 backdrop-blur-md border-b border-gray-200 shadow-sm">
                <div class="flex items-center justify-between h-16 px-4 sm:px-6 lg:px-8">
                    <!-- Sisi Kiri: Tombol Hamburger Mobile / Toggle Desktop & Brand -->
                    <div class="flex items-center gap-3">
                        <!-- Hamburger Mobile: HANYA ikon hamburger tanpa logo/teks di layar HP -->
                        <button id="mobile-menu-button" type="button" class="lg:hidden p-2 rounded-xl text-gray-600 hover:text-gray-900 hover:bg-gray-100 transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500" aria-label="Buka Menu">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                            </svg>
                        </button>

                        <!-- Toggle Minimize Sidebar Desktop -->
                        <button id="desktop-sidebar-toggle" type="button" class="hidden lg:flex p-2 rounded-xl text-gray-600 hover:text-gray-900 hover:bg-gray-100 transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500" title="Perkecil / Perbesar Sidebar">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"></path>
                            </svg>
                        </button>

                        <!-- Brand Header Desktop -->
                        <div class="hidden lg:flex items-center gap-2 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                            <span class="w-2 h-2 rounded-full bg-emerald-500 inline-block animate-pulse"></span>
                            <span>SMK Kesehatan Binatama • Sistem Informasi Aset</span>
                        </div>
                    </div>

                    <!-- Sisi Kanan: Sapaan User, Role & Profil -->
                    <div class="flex items-center gap-3">
                        <div class="text-right leading-tight">
                            <p class="text-sm font-semibold text-gray-800">
                                Hallo, <span class="text-blue-600 font-bold">{{ Auth::user()->name }}</span>
                            </p>
                            <div class="flex items-center justify-end gap-1.5 mt-0.5">
                                @php
                                    $roleBadge = match(Auth::user()->role) {
                                        'admin' => 'bg-purple-100 text-purple-700 border-purple-200',
                                        'teknisi' => 'bg-blue-100 text-blue-700 border-blue-200',
                                        'laboran' => 'bg-amber-100 text-amber-700 border-amber-200',
                                        default => 'bg-gray-100 text-gray-700 border-gray-200',
                                    };
                                @endphp
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider border {{ $roleBadge }}">
                                    {{ Auth::user()->role }}
                                </span>
                            </div>
                        </div>

                        <!-- Avatar Inisial -->
                        <div class="w-10 h-10 rounded-full bg-gradient-to-tr from-blue-600 to-indigo-600 text-white flex items-center justify-center font-bold text-sm shadow-md ring-2 ring-white flex-shrink-0">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                    </div>
                </div>
            </header>

            {{-- Main Content Slot --}}
            <main class="flex-1">
                {{-- Alert Messages --}}
                @if (session('success'))
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
                        <div class="p-4 bg-emerald-50 border border-emerald-300 text-emerald-800 rounded-xl flex items-center gap-3 shadow-sm">
                            <svg class="w-5 h-5 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            <span class="text-sm font-medium">{{ session('success') }}</span>
                        </div>
                    </div>
                @endif

                @if (session('error'))
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
                        <div class="p-4 bg-red-50 border border-red-300 text-red-800 rounded-xl flex items-center gap-3 shadow-sm">
                            <svg class="w-5 h-5 text-red-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            <span class="text-sm font-medium">{{ session('error') }}</span>
                        </div>
                    </div>
                @endif

                {{ $slot }}
            </main>
        </div>
    </div>

    {{-- Stack untuk scripts tambahan --}}
    @stack('scripts')
</body>
</html>