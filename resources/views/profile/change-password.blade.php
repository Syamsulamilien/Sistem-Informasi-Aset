<x-app-layout>
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Ganti Password</h1>
            <p class="mt-1 text-sm text-gray-600">Ubah password akun Anda untuk keamanan yang lebih baik</p>
        </div>

        <!-- Success Message -->
        @if(session('success'))
            <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg flex items-center">
                <svg class="w-5 h-5 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                <span class="font-medium">{{ session('success') }}</span>
            </div>
        @endif

        <!-- Form Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <form method="POST" action="{{ route('update-password') }}">
                @csrf

                <div class="p-6 sm:p-8">
                    <div class="space-y-6">
                        <!-- Password Lama -->
                        <div>
                            <label for="current_password" class="block text-sm font-medium text-gray-700 mb-2">
                                Password Saat Ini <span class="text-red-500">*</span>
                            </label>
                            <input type="password"
                                   name="current_password"
                                   id="current_password"
                                   required
                                   class="w-full px-4 py-2.5 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors {{ $errors->has('current_password') ? 'border-red-500' : 'border-gray-300' }}"
                                   placeholder="Masukkan password saat ini">
                            @error('current_password')
                                <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Password Baru -->
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                                Password Baru <span class="text-red-500">*</span>
                            </label>
                            <input type="password"
                                   name="password"
                                   id="password"
                                   required
                                   class="w-full px-4 py-2.5 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors {{ $errors->has('password') ? 'border-red-500' : 'border-gray-300' }}"
                                   placeholder="Masukkan password baru">
                            @error('password')
                                <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1.5 text-xs text-gray-500">Minimal 8 karakter</p>
                        </div>

                        <!-- Konfirmasi Password Baru -->
                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                                Konfirmasi Password Baru <span class="text-red-500">*</span>
                            </label>
                            <input type="password"
                                   name="password_confirmation"
                                   id="password_confirmation"
                                   required
                                   class="w-full px-4 py-2.5 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors {{ $errors->has('password_confirmation') ? 'border-red-500' : 'border-gray-300' }}"
                                   placeholder="Ulangi password baru">
                            @error('password_confirmation')
                                <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Info Box -->
                    <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-blue-600 mt-0.5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                            </svg>
                            <div class="flex-1 text-sm text-blue-800">
                                <p class="font-semibold mb-1">Tips Keamanan Password:</p>
                                <ul class="list-disc list-inside space-y-1 text-xs">
                                    <li>Gunakan kombinasi huruf besar, huruf kecil, angka, dan simbol</li>
                                    <li>Minimal 8 karakter atau lebih</li>
                                    <li>Jangan gunakan password yang mudah ditebak</li>
                                    <li>Ubah password secara berkala</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer Buttons -->
                <div class="bg-gray-50 px-6 py-4 sm:px-8 sm:py-5 flex flex-col sm:flex-row gap-3 sm:justify-end">
                    <a href="{{ route('dashboard') }}"
                       class="w-full sm:w-auto px-6 py-2.5 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors text-center font-medium">
                        Batal
                    </a>
                    <button type="submit"
                            class="w-full sm:w-auto px-6 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium">
                        Simpan Password Baru
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
