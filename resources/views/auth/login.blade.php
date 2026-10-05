<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>RSU PKU MUHAMMADIYAH BANTUL - Login</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />
        <script src="https://cdn.tailwindcss.com"></script>
        
        <style>
            body {
                font-family: 'Inter', sans-serif;
            }
            
            @keyframes fadeIn {
                from { opacity: 0; transform: translateY(20px); }
                to { opacity: 1; transform: translateY(0); }
            }
            
            @keyframes slideInLeft {
                from { opacity: 0; transform: translateX(-30px); }
                to { opacity: 1; transform: translateX(0); }
            }
            
            .animate-fade-in {
                animation: fadeIn 0.6s ease-out;
            }
            
            .animate-slide-left {
                animation: slideInLeft 0.6s ease-out;
            }
            
            .glass-effect {
                backdrop-filter: blur(10px);
                background: rgba(255, 255, 255, 0.95);
            }

            .bg-image-container {
                background-image: url('{{ asset("images/Foto3.jpg") }}');
                background-size: cover;
                background-position: center;
                background-repeat: no-repeat;
                position: relative;
            }

            /* Overlay untuk menggelapkan/mencerahkan foto */
            .bg-overlay {
                position: absolute;
                inset: 0;
                background: linear-gradient(135deg, rgba(59, 130, 246, 0.7), rgba(20, 184, 166, 0.6), rgba(16, 185, 129, 0.7));
                /* Atau gunakan overlay gelap: background: rgba(0, 0, 0, 0.5); */
            }

            @media (max-width: 1024px) {
                .glass-effect {
                    background: rgba(255, 255, 255, 0.98);
                }
            }
        </style>
    </head>
    <body class="antialiased">
        <div class="min-h-screen flex items-center justify-center bg-image-container p-4 relative overflow-hidden">
            <!-- Overlay Gradient (opsional, bisa dihapus jika ingin foto terlihat jelas) -->
            <div class="bg-overlay"></div>

            <!-- Main Container -->
            <div class="relative z-10 w-full max-w-7xl mx-auto animate-fade-in">
                <div class="flex flex-col lg:flex-row items-center justify-center lg:justify-between gap-8 lg:gap-20">
                    
                    <!-- Left Side - Welcome Text (Desktop Only) -->
                    <div class="hidden lg:block flex-1 text-white max-w-xl animate-slide-left">
                        <!-- Logo -->
                        <div class="mb-12">
                            <div class="flex items-center space-x-4">
                                <div class="w-16 h-16 bg-white/20 backdrop-blur-lg rounded-2xl flex items-center justify-center shadow-2xl border border-white/30">
                                    <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-12 h-12 object-contain">
                                </div>
                                <span class="text-3xl font-bold drop-shadow-lg">RSU PKU MUHAMMADIYAH BANTUL</span>
                            </div>
                        </div>

                        <!-- Welcome Text -->
                        <div>
                            <h1 class="text-6xl font-extrabold mb-6 leading-tight drop-shadow-lg">
                                Asset,<br/>
                                <span class="bg-gradient-to-r from-white to-cyan-100 bg-clip-text text-transparent">
                                    Monitoring
                                </span>
                            </h1>
                            <p class="text-xl text-white/95 leading-relaxed font-medium mb-10 drop-shadow-md">
                                Sign in to access your account and continue your journey with us.
                            </p>
                            
                            <!-- Feature Points -->
                            <div class="space-y-4">
                                <div class="flex items-center space-x-3">
                                    <div class="w-8 h-8 rounded-full bg-white/20 backdrop-blur flex items-center justify-center flex-shrink-0">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    </div>
                                    <span class="text-lg text-white/90 drop-shadow">Secure and encrypted</span>
                                </div>
                                <div class="flex items-center space-x-3">
                                    <div class="w-8 h-8 rounded-full bg-white/20 backdrop-blur flex items-center justify-center flex-shrink-0">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    </div>
                                    <span class="text-lg text-white/90 drop-shadow">Fast and reliable</span>
                                </div>
                                <div class="flex items-center space-x-3">
                                    <div class="w-8 h-8 rounded-full bg-white/20 backdrop-blur flex items-center justify-center flex-shrink-0">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    </div>
                                    <span class="text-lg text-white/90 drop-shadow">24/7 support available</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Side - Login Card -->
                    <div class="w-full lg:w-auto max-w-md lg:max-w-none">
                        <!-- Mobile Logo -->
                        <div class="lg:hidden text-center mb-8">
                            <div class="flex flex-col items-center justify-center mb-6">
                                <div class="w-16 h-16 bg-white/20 backdrop-blur-lg rounded-2xl flex items-center justify-center shadow-xl border border-white/30 mb-3">
                                    <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-12 h-12 object-contain">
                                </div>
                                <h2 class="text-xl font-bold text-white drop-shadow-lg leading-tight">
                                    RSU PKU MUHAMMADIYAH<br/>BANTUL
                                </h2>
                            </div>
                            <h1 class="text-3xl font-bold text-white drop-shadow-lg mb-2">Welcome Back!</h1>
                            <p class="text-white/90 text-sm drop-shadow">Sign in to continue</p>
                        </div>

                        <div class="glass-effect rounded-3xl shadow-2xl p-6 sm:p-8 lg:p-10 w-full lg:w-[480px] border border-white/20">
                            <!-- Header (Desktop Only) -->
                            <div class="hidden lg:block mb-8">
                                <h2 class="text-3xl font-bold text-gray-800 mb-2">Sign In</h2>
                                <p class="text-gray-600">Enter your credentials to access your account</p>
                            </div>

                            <!-- Mobile Header -->
                            <div class="lg:hidden mb-6">
                                <h2 class="text-2xl font-bold text-gray-800 text-center">Sign In</h2>
                            </div>

                            <!-- Session Status -->
                            @if (session('status'))
                                <div class="mb-6 p-3 bg-green-50 border border-green-200 rounded-xl">
                                    <p class="font-medium text-sm text-green-700">{{ session('status') }}</p>
                                </div>
                            @endif

                            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                                @csrf
                                <!-- Username -->
                                <div>
                                    <label for="username" class="block text-sm font-semibold text-gray-700 mb-2">Username</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 sm:pl-4 flex items-center pointer-events-none">
                                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                            </svg>
                                        </div>
                                        <input 
                                            id="username" 
                                            class="block w-full pl-11 sm:pl-12 pr-3 sm:pr-4 py-3 sm:py-3.5 bg-gray-50 border-2 border-gray-200 rounded-xl focus:ring-0 focus:border-teal-500 focus:bg-white transition-all placeholder-gray-400 text-gray-900 text-sm sm:text-base" 
                                            type="text" 
                                            name="username" 
                                            value="{{ old('username') }}"
                                            required 
                                            autofocus 
                                            autocomplete="username"
                                            placeholder="Enter your username" />
                                    </div>
                                    @error('username')
                                        <p class="mt-2 text-xs sm:text-sm text-red-600 flex items-start">
                                            <svg class="w-4 h-4 mr-1 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                            </svg>
                                            <span>{{ $message }}</span>
                                        </p>
                                    @enderror
                                </div>

                                <!-- Password -->
                                <div>
                                    <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">Password</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 sm:pl-4 flex items-center pointer-events-none">
                                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                            </svg>
                                        </div>
                                        <input 
                                            id="password" 
                                            class="block w-full pl-11 sm:pl-12 pr-3 sm:pr-4 py-3 sm:py-3.5 bg-gray-50 border-2 border-gray-200 rounded-xl focus:ring-0 focus:border-teal-500 focus:bg-white transition-all placeholder-gray-400 text-gray-900 text-sm sm:text-base"
                                            type="password"
                                            name="password"
                                            required 
                                            autocomplete="current-password"
                                            placeholder="••••••••••••" />
                                    </div>
                                    @error('password')
                                        <p class="mt-2 text-xs sm:text-sm text-red-600 flex items-start">
                                            <svg class="w-4 h-4 mr-1 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                            </svg>
                                            <span>{{ $message }}</span>
                                        </p>
                                    @enderror
                                </div>

                                <!-- Remember Me & Forgot Password -->
                                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 text-sm pt-1">
                                    <label for="remember_me" class="inline-flex items-center cursor-pointer group">
                                        <input 
                                            id="remember_me" 
                                            type="checkbox" 
                                            class="rounded border-gray-300 text-teal-600 focus:ring-teal-500 cursor-pointer transition-all" 
                                            name="remember">
                                        <span class="ml-2 text-gray-700 group-hover:text-gray-900 transition-colors font-medium">Remember me</span>
                                    </label>

                                    @if (Route::has('password.request'))
                                        <a class="font-semibold text-teal-600 hover:text-teal-700 transition-colors text-center sm:text-left" href="{{ route('password.request') }}">
                                            Forgot password?
                                        </a>
                                    @endif
                                </div>

                                <!-- Login Button -->
                                <div class="pt-2">
                                    <button type="submit" class="w-full py-3.5 sm:py-4 px-6 bg-gradient-to-r from-blue-600 to-teal-500 hover:from-blue-700 hover:to-teal-600 text-white font-bold rounded-xl transition-all shadow-lg shadow-teal-500/40 hover:shadow-xl hover:shadow-teal-500/50 transform hover:-translate-y-0.5 text-sm sm:text-base">
                                        Sign In
                                    </button>
                                </div>

                                <!-- Divider -->
                                <div class="relative py-3">
                                    <div class="absolute inset-0 flex items-center">
                                        <div class="w-full border-t border-gray-300"></div>
                                    </div>
                                    <div class="relative flex justify-center text-sm">
                                        <span class="px-4 bg-white text-gray-600 font-medium"></span>
                                    </div>
                                </div>

                                <!-- Sign Up Button -->
                                @if (Route::has('register'))
                                    <!-- <div>
                                        <a href="{{ route('register') }}" class="w-full py-3.5 sm:py-4 px-6 bg-white hover:bg-gray-50 text-teal-600 font-bold rounded-xl transition-all border-2 border-teal-500 hover:border-teal-600 flex items-center justify-center shadow-md hover:shadow-lg text-sm sm:text-base">
                                            Create New Account
                                        </a>
                                    </div> -->
                                @endif
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>