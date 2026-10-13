<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Login - {{ config('app.name', 'SIASET') }}</title>

    <!-- Favicon Logo Website di Tab Browser -->
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/favicon.png') }}">

    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Styles & Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Inter', system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
        }
    </style>
</head>
<body class="min-h-screen bg-slate-50 text-slate-900 antialiased selection:bg-blue-600 selection:text-white flex items-center justify-center p-4 relative overflow-x-hidden">

    <!-- Ambient Subtle Background Glow -->
    <div class="fixed inset-0 pointer-events-none overflow-hidden z-0" aria-hidden="true">
        <div class="absolute -top-40 -right-40 w-96 h-96 bg-blue-400/10 rounded-full blur-3xl"></div>
        <div class="absolute top-1/2 -left-40 w-96 h-96 bg-indigo-400/10 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-40 right-1/3 w-96 h-96 bg-cyan-400/10 rounded-full blur-3xl"></div>
    </div>

    <!-- Container Utama: Kartu Login & Info Hak Cipta Pas 1 Halaman -->
    <main class="relative z-10 w-full max-w-[420px] my-auto">
        
        <!-- Kartu Login Elegan & Proporsional -->
        <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xl shadow-slate-200/60 p-6 sm:p-8 transition-all">
            
            <!-- Logo & Identitas Sistem Lengkap -->
            <div class="text-center mb-6">
                <!-- Logo Sekolah -->
                <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-slate-50 border border-slate-100 p-2 mb-2.5 shadow-xs">
                    <img src="{{ asset('images/logobinatama.png') }}" alt="Logo SMK Kesehatan Binatama" class="w-full h-full object-contain" width="56" height="56" loading="eager" decoding="sync">
                </div>

                <!-- Label Identitas Instansi -->
                <div>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-blue-50 text-blue-700 border border-blue-100 mb-1.5">
                        SMK Kesehatan Binatama
                    </span>
                </div>

                <!-- Judul Utama -->
                <h1 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">
                    Selamat Datang di SIASET
                </h1>
                <p class="text-xs text-slate-500 mt-1 max-w-xs mx-auto leading-relaxed">
                    Sistem Informasi Pengelolaan & Pendataan Aset Sekolah
                </p>
            </div>

            <!-- Notifikasi Status Flash Session -->
            @if (session('status'))
                <div class="mb-4 p-3 bg-emerald-50 border border-emerald-200 rounded-xl text-emerald-800 text-xs font-semibold flex items-center gap-2">
                    <svg class="w-4 h-4 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <span>{{ session('status') }}</span>
                </div>
            @endif

            <!-- Form Login -->
            <form method="POST" action="{{ route('login') }}" class="space-y-3.5">
                @csrf

                <!-- Field: Username -->
                <div>
                    <label for="username" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                        Username
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <input 
                            type="text" 
                            id="username" 
                            name="username" 
                            value="{{ old('username') }}" 
                            required 
                            autofocus 
                            autocomplete="username"
                            class="w-full pl-10 pr-3.5 py-2.5 bg-slate-50/70 border @error('username') border-rose-300 bg-rose-50/30 text-rose-900 @else border-slate-200 text-slate-900 @enderror rounded-xl text-sm placeholder-slate-400 focus:bg-white focus:border-blue-600 focus:ring-4 focus:ring-blue-100 transition-all outline-none"
                            placeholder="Masukkan username">
                    </div>
                    @error('username')
                        <p class="mt-1 text-xs text-rose-600 flex items-center gap-1 font-medium">
                            <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <span>{{ $message }}</span>
                        </p>
                    @enderror
                </div>

                <!-- Field: Password -->
                <div>
                    <label for="password" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                        Password
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                        </div>
                        <input 
                            type="password" 
                            id="password" 
                            name="password" 
                            required 
                            autocomplete="current-password"
                            class="w-full pl-10 pr-10 py-2.5 bg-slate-50/70 border @error('password') border-rose-300 bg-rose-50/30 text-rose-900 @else border-slate-200 text-slate-900 @enderror rounded-xl text-sm placeholder-slate-400 focus:bg-white focus:border-blue-600 focus:ring-4 focus:ring-blue-100 transition-all outline-none"
                            placeholder="••••••••">
                        <button 
                            type="button" 
                            onclick="togglePasswordVisibility()" 
                            class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600 transition-colors"
                            title="Lihat / Sembunyikan Password">
                            <svg id="eye-icon-show" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                            <svg id="eye-icon-hide" class="w-4 h-4 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"></path>
                            </svg>
                        </button>
                    </div>
                    @error('password')
                        <p class="mt-1 text-xs text-rose-600 flex items-center gap-1 font-medium">
                            <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <span>{{ $message }}</span>
                        </p>
                    @enderror
                </div>

                <!-- Remember Me & Lupa Password -->
                <div class="flex items-center justify-between text-xs pt-0.5">
                    <label for="remember_me" class="inline-flex items-center cursor-pointer select-none text-slate-600 hover:text-slate-800 transition-colors">
                        <input 
                            id="remember_me" 
                            type="checkbox" 
                            name="remember" 
                            class="w-4 h-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500 cursor-pointer">
                        <span class="ml-2 font-medium">Ingat saya</span>
                    </label>

                    <span class="text-slate-400 cursor-default" title="Hubungi Administrator untuk reset password">
                        Lupa password?
                    </span>
                </div>

                <!-- Tombol Masuk -->
                <div class="pt-1.5">
                    <button 
                        type="submit" 
                        class="w-full py-2.5 sm:py-3 px-4 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-bold text-sm rounded-xl shadow-lg shadow-blue-500/25 hover:shadow-blue-500/35 active:scale-[0.99] transition-all flex items-center justify-center gap-2 group">
                        <span>Masuk ke Sistem</span>
                        <svg class="w-4 h-4 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                        </svg>
                    </button>
                </div>
            </form>

            <!-- Bantuan Akses -->
            <div class="mt-5 pt-3.5 border-t border-slate-100 text-center">
                <p class="text-[11px] text-slate-400 leading-relaxed">
                    Belum memiliki akun atau lupa password? Hubungi <span class="font-semibold text-slate-600">Administrator</span>.
                </p>
            </div>

        </div>

        <!-- Copyright Pas di Bawah Kartu (Tanpa Scrollbar) -->
        <p class="text-center text-[11px] text-slate-400 mt-3 font-medium">
            &copy; {{ date('Y') }} SMK Kesehatan Binatama &bull; SIASET
        </p>

    </main>

    <!-- Script Toggle Password -->
    <script>
        function togglePasswordVisibility() {
            const passwordInput = document.getElementById('password');
            const eyeShow = document.getElementById('eye-icon-show');
            const eyeHide = document.getElementById('eye-icon-hide');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeShow.classList.add('hidden');
                eyeHide.classList.remove('hidden');
            } else {
                passwordInput.type = 'password';
                eyeShow.classList.remove('hidden');
                eyeHide.classList.add('hidden');
            }
        }
    </script>
</body>
</html>