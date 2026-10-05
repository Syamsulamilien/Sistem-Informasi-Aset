<x-app-layout>
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center gap-3 mb-2">
                <a href="{{ route('users.index') }}" class="inline-flex items-center text-gray-600 hover:text-gray-900 transition-colors">
                    <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Kembali
                </a>
            </div>
            <h1 class="text-2xl font-bold text-gray-900">Edit User</h1>
            <p class="mt-1 text-sm text-gray-600">Perbarui informasi user</p>
        </div>

        <!-- Form Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <form action="{{ route('users.update', $user->id) }}" method="POST" class="p-6 space-y-6">
                @csrf
                @method('PATCH')

                <!-- Nama Lengkap -->
                <div>
                    <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">
                        Nama Lengkap <span class="text-red-500">*</span>
                    </label>
                    @php
                        $nameClass = $errors->has('name') 
                            ? 'block w-full px-4 py-3 bg-gray-50 border-2 border-red-300 rounded-xl focus:ring-0 focus:border-blue-500 focus:bg-white transition-all placeholder-gray-400 text-gray-900'
                            : 'block w-full px-4 py-3 bg-gray-50 border-2 border-gray-200 rounded-xl focus:ring-0 focus:border-blue-500 focus:bg-white transition-all placeholder-gray-400 text-gray-900';
                    @endphp
                    <input type="text" 
                           name="name" 
                           id="name" 
                           value="{{ old('name', $user->name) }}"
                           class="{{ $nameClass }}" 
                           placeholder="Masukkan nama lengkap"
                           required>
                    @error('name')
                        <p class="mt-2 text-sm text-red-600 flex items-start">
                            <svg class="w-4 h-4 mr-1 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Username -->
                <div>
                    <label for="username" class="block text-sm font-semibold text-gray-700 mb-2">
                        Username <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        @php
                            $usernameClass = $errors->has('username') 
                                ? 'block w-full pl-12 pr-4 py-3 bg-gray-50 border-2 border-red-300 rounded-xl focus:ring-0 focus:border-blue-500 focus:bg-white transition-all placeholder-gray-400 text-gray-900'
                                : 'block w-full pl-12 pr-4 py-3 bg-gray-50 border-2 border-gray-200 rounded-xl focus:ring-0 focus:border-blue-500 focus:bg-white transition-all placeholder-gray-400 text-gray-900';
                        @endphp
                        <input type="text" 
                               name="username" 
                               id="username" 
                               value="{{ old('username', $user->username) }}"
                               class="{{ $usernameClass }}" 
                               placeholder="Masukkan username (tanpa spasi)"
                               required>
                    </div>
                    @error('username')
                        <p class="mt-2 text-sm text-red-600 flex items-start">
                            <svg class="w-4 h-4 mr-1 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror>
                    <p class="mt-1 text-xs text-gray-500">Username hanya boleh berisi huruf, angka, underscore (_) dan dash (-)</p>
                </div>

                <!-- Role (TAMBAHAN BARU) -->
                <div>
                    <label for="role" class="block text-sm font-semibold text-gray-700 mb-2">
                        Role / Jabatan <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        @php
                            $roleClass = $errors->has('role') 
                                ? 'block w-full pl-12 pr-4 py-3 bg-gray-50 border-2 border-red-300 rounded-xl focus:ring-0 focus:border-blue-500 focus:bg-white transition-all text-gray-900'
                                : 'block w-full pl-12 pr-4 py-3 bg-gray-50 border-2 border-gray-200 rounded-xl focus:ring-0 focus:border-blue-500 focus:bg-white transition-all text-gray-900';
                        @endphp
                        <select name="role" 
                                id="role" 
                                class="{{ $roleClass }}"
                                required>
                            <option value="teknisi" {{ old('role', $user->role) == 'teknisi' ? 'selected' : '' }}>Teknisi</option>
                            <option value="viewer" {{ old('role', $user->role) == 'viewer' ? 'selected' : '' }}>Viewer</option>
                            <option value="laboran" {{ old('role', $user->role) == 'laboran' ? 'selected' : '' }}>Laboran</option>
                            <option value="user_biasa" {{ old('role', $user->role) == 'user_biasa' ? 'selected' : '' }}>User Biasa</option>
                        </select>
                    </div>
                    @error('role')
                        <p class="mt-2 text-sm text-red-600 flex items-start">
                            <svg class="w-4 h-4 mr-1 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror>
                    <p class="mt-1 text-xs text-gray-500">Teknisi dapat melakukan maintenance, Viewer hanya dapat melihat data</p>
                </div>

                <!-- Divider -->
                <div class="border-t border-gray-200 pt-6">
                    <h3 class="text-base font-semibold text-gray-900 mb-4">Ganti Password (Opsional)</h3>
                    <p class="text-sm text-gray-600 mb-4">Kosongkan jika tidak ingin mengubah password</p>
                </div>

                <!-- Password Baru -->
                <div>
                    <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">
                        Password Baru
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                        </div>
                        @php
                            $passwordClass = $errors->has('password') 
                                ? 'block w-full pl-12 pr-4 py-3 bg-gray-50 border-2 border-red-300 rounded-xl focus:ring-0 focus:border-blue-500 focus:bg-white transition-all placeholder-gray-400 text-gray-900'
                                : 'block w-full pl-12 pr-4 py-3 bg-gray-50 border-2 border-gray-200 rounded-xl focus:ring-0 focus:border-blue-500 focus:bg-white transition-all placeholder-gray-400 text-gray-900';
                        @endphp
                        <input type="password" 
                               name="password" 
                               id="password" 
                               class="{{ $passwordClass }}" 
                               placeholder="Minimal 8 karakter">
                    </div>
                    @error('password')
                        <p class="mt-2 text-sm text-red-600 flex items-start">
                            <svg class="w-4 h-4 mr-1 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror>
                </div>

                <!-- Konfirmasi Password -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-2">
                        Konfirmasi Password Baru
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                            </svg>
                        </div>
                        <input type="password" 
                               name="password_confirmation" 
                               id="password_confirmation" 
                               class="block w-full pl-12 pr-4 py-3 bg-gray-50 border-2 border-gray-200 rounded-xl focus:ring-0 focus:border-blue-500 focus:bg-white transition-all placeholder-gray-400 text-gray-900" 
                               placeholder="Ketik ulang password">
                    </div>
                    <p class="mt-1 text-xs text-gray-500">Pastikan password sama dengan yang di atas</p>
                </div>

                <!-- Buttons -->
                <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-200">
                    <a href="{{ route('users.index') }}" 
                       class="px-6 py-2.5 bg-white text-gray-700 rounded-lg hover:bg-gray-50 transition-all duration-200 text-sm font-medium border border-gray-300">
                        Batal
                    </a>
                    <button type="submit" 
                            class="px-6 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-all duration-200 text-sm font-medium shadow-sm hover:shadow-md">
                        <svg class="w-4 h-4 inline-block mr-1 -mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Update User
                    </button>
                </div>
            </form>
        </div>

        <!-- Info Card -->
        <div class="mt-6 bg-yellow-50 border border-yellow-200 rounded-xl p-4">
            <div class="flex">
                <svg class="w-5 h-5 text-yellow-600 mr-3 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-1.964-1.333-2.732 0L3.082 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
                <div class="text-sm text-yellow-800">
                    <p class="font-semibold mb-1">Perhatian:</p>
                    <ul class="list-disc list-inside space-y-1 text-yellow-700">
                        <li>Jika password diubah, user harus login ulang dengan password baru</li>
                        <li>Perubahan data akan langsung tersimpan setelah tombol "Update User" diklik</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>