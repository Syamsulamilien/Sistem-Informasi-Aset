<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @php
        $siteName = config('app.name', 'SIASET');
        $customSection = trim(View::getSection('title', ''));
        $slotTitle = isset($title) && trim((string)$title) !== '' ? trim((string)$title) : null;
        $activeTitle = $customSection ?: ($slotTitle ?: ($pageTitle ?? ''));
        $fullTitle = !empty($activeTitle) ? $activeTitle . ' - ' . $siteName : $siteName;
    @endphp
    <title>{{ $fullTitle }}</title>
    <!-- Favicon Logo Website di Tab Browser -->
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/favicon.png') }}">
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Preload Logo untuk rendering instan -->
    <link rel="preload" as="image" href="{{ asset('images/logobinatama.png') }}" fetchpriority="high">

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
        html, body {
            overflow-x: hidden;
            max-width: 100vw;
        }
        #sidebar {
            overflow-x: hidden !important;
        }
        #sidebar nav {
            overflow-x: hidden !important;
            scrollbar-width: none !important;
            -ms-overflow-style: none !important;
        }
        #sidebar nav::-webkit-scrollbar {
            display: none !important;
            width: 0 !important;
            height: 0 !important;
        }
        .sidebar-item {
            box-sizing: border-box;
            width: calc(100% - 1.5rem);
        }
        @media (min-width: 1024px) {
            html.sidebar-collapsed #sidebar {
                width: 5rem !important;
                overflow-x: hidden !important;
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
                padding-left: 0 !important;
                padding-right: 0 !important;
                margin-left: auto !important;
                margin-right: auto !important;
                width: 3.5rem !important;
                height: 2.75rem !important;
                box-sizing: border-box !important;
                flex-shrink: 0 !important;
            }
            html.sidebar-collapsed .sidebar-item svg {
                margin-right: 0 !important;
            }
            html.sidebar-collapsed .sidebar-header-expanded {
                display: none !important;
            }
            html.sidebar-collapsed .sidebar-header-collapsed {
                display: flex !important;
                padding-left: 0 !important;
                padding-right: 0 !important;
                justify-content: center !important;
                width: 100% !important;
            }
        }
    </style>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-50 text-gray-900 selection:bg-blue-600 selection:text-white overflow-x-hidden">
    <div class="min-h-screen flex bg-gray-50 w-full overflow-x-hidden">
        {{-- Sidebar --}}
        @include('layouts.sidebar')

        {{-- Main Content Wrapper --}}
        <div id="main-content-wrapper" class="flex-1 lg:ml-64 flex flex-col min-h-screen min-w-0 overflow-x-hidden transition-[margin] duration-300">
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

                    <!-- Sisi Kanan: Sapaan User, Role & Profil (Klik ke Settings) -->
                    <a href="{{ route('settings') }}" title="Buka Pengaturan Akun" class="flex items-center gap-2.5 sm:gap-3 p-1 sm:p-1.5 rounded-2xl hover:bg-gray-100/80 transition-colors">
                        <div class="text-right leading-tight">
                            <p class="text-xs sm:text-sm font-semibold text-gray-800">
                                Hallo, <span class="text-blue-600 font-bold">{{ Auth::user()->name }}</span>
                            </p>
                            <div class="flex items-center justify-end gap-1.5 mt-0.5">
                                @php
                                    $roleBadge = match(Auth::user()->role) {
                                        'super_admin' => 'bg-rose-100 text-rose-700 border-rose-200',
                                        'admin' => 'bg-purple-100 text-purple-700 border-purple-200',
                                        'pimpinan', 'pimpinan_sekolah' => 'bg-teal-100 text-teal-800 border-teal-200',
                                        'teknisi' => 'bg-blue-100 text-blue-700 border-blue-200',
                                        'laboran' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                                        default => 'bg-gray-100 text-gray-700 border-gray-200',
                                    };
                                    $roleDisplay = match(Auth::user()->role) {
                                        'super_admin' => 'SUPER ADMIN',
                                        'admin' => 'ADMIN',
                                        'pimpinan', 'pimpinan_sekolah' => 'PIMPINAN SEKOLAH',
                                        default => strtoupper(str_replace('_', ' ', Auth::user()->role)),
                                    };
                                @endphp
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider border {{ $roleBadge }}">
                                    {{ $roleDisplay }}
                                </span>
                            </div>
                        </div>

                        <!-- Avatar Inisial -->
                        <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-full bg-gradient-to-tr from-blue-600 to-indigo-600 text-white flex items-center justify-center font-bold text-xs sm:text-sm shadow-md ring-2 ring-white flex-shrink-0">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                    </a>
                </div>
            </header>

            {{-- Main Content Slot --}}
            <main class="flex-1">
                {{ $slot }}
            </main>
        </div>
    </div>

    {{-- SweetAlert2 Notifikasi Global & Pop-up --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: @js(session('success')),
                    timer: 3000,
                    timerProgressBar: true,
                    confirmButtonText: 'Tutup',
                    confirmButtonColor: '#2563eb',
                    customClass: {
                        popup: 'rounded-3xl shadow-2xl border border-gray-100 font-sans p-6',
                        title: 'text-xl font-black text-gray-900',
                        htmlContainer: 'text-sm text-gray-600 mt-1',
                        confirmButton: 'rounded-xl font-bold px-6 py-2.5 shadow-md shadow-blue-500/20 text-sm'
                    }
                });
            @endif

            @if (session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Perhatian',
                    text: @js(session('error')),
                    confirmButtonText: 'Tutup',
                    confirmButtonColor: '#e11d48',
                    customClass: {
                        popup: 'rounded-3xl shadow-2xl border border-gray-100 font-sans p-6',
                        title: 'text-xl font-black text-gray-900',
                        htmlContainer: 'text-sm text-gray-600 mt-1',
                        confirmButton: 'rounded-xl font-bold px-6 py-2.5 shadow-md shadow-rose-500/20 text-sm'
                    }
                });
            @endif

            @if ($errors->any())
                Swal.fire({
                    icon: 'error',
                    title: 'Validasi Gagal',
                    html: @js(implode('<br>', $errors->all())),
                    confirmButtonText: 'Tutup',
                    confirmButtonColor: '#e11d48',
                    customClass: {
                        popup: 'rounded-3xl shadow-2xl border border-gray-100 font-sans p-6',
                        title: 'text-xl font-black text-gray-900',
                        htmlContainer: 'text-sm text-gray-600 mt-1 leading-relaxed',
                        confirmButton: 'rounded-xl font-bold px-6 py-2.5 shadow-md shadow-rose-500/20 text-sm'
                    }
                });
            @endif

            @if (session('warning'))
                Swal.fire({
                    icon: 'warning',
                    title: 'Peringatan',
                    text: @js(session('warning')),
                    confirmButtonText: 'Tutup',
                    confirmButtonColor: '#f59e0b',
                    customClass: {
                        popup: 'rounded-3xl shadow-2xl border border-gray-100 font-sans p-6',
                        title: 'text-xl font-black text-gray-900',
                        htmlContainer: 'text-sm text-gray-600 mt-1',
                        confirmButton: 'rounded-xl font-bold px-6 py-2.5 text-sm'
                    }
                });
            @endif

            @if (session('info'))
                Swal.fire({
                    icon: 'info',
                    title: 'Informasi',
                    text: @js(session('info')),
                    confirmButtonText: 'Tutup',
                    confirmButtonColor: '#2563eb',
                    customClass: {
                        popup: 'rounded-3xl shadow-2xl border border-gray-100 font-sans p-6',
                        title: 'text-xl font-black text-gray-900',
                        htmlContainer: 'text-sm text-gray-600 mt-1',
                        confirmButton: 'rounded-xl font-bold px-6 py-2.5 text-sm'
                    }
                });
            @endif
        });
    </script>

    {{-- Stack untuk scripts tambahan --}}
    @stack('scripts')
</body>
</html>