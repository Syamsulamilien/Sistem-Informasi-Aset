<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8 space-y-6 sm:space-y-8">
        
        <!-- Header Judul Halaman -->
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 tracking-tight">Settings</h1>
            <p class="text-xs sm:text-sm text-gray-500 mt-1">Kelola informasi akun Anda dan manajemen pengguna sistem</p>
        </div>

        <!-- ======================================================== -->
        <!-- KARTU 1: PROFIL AKUN SAYA & GANTI PASSWORD (SEMUA ROLE)   -->
        <!-- ======================================================== -->
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 sm:p-8 transition-all hover:shadow-md">
            <form action="{{ route('settings.update') }}" method="POST">
                @csrf
                @method('PUT')

                <!-- Sub-bagian 1: Informasi User -->
                <div class="space-y-6">
                    <div>
                        <h2 class="text-lg font-bold text-gray-900 tracking-tight">Profil Akun Saya</h2>
                        <p class="text-xs text-gray-500 mt-0.5">Data identitas akun Anda yang sedang aktif saat ini</p>
                    </div>

                    <!-- Input: Nama Lengkap (Dapat diubah oleh semua role) -->
                    <div>
                        <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">Nama Lengkap</label>
                        <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required
                               class="w-full px-4 py-2.5 rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-gray-900 text-sm transition-colors shadow-sm"
                               placeholder="Masukkan nama lengkap">
                    </div>

                    <!-- Row 2 Kolom: Username & Role -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Username</label>
                            <input type="text" value="{{ $user->username }}" readonly disabled
                                   class="w-full px-4 py-2.5 rounded-xl border border-gray-200 bg-gray-100 text-gray-500 text-sm font-medium cursor-not-allowed shadow-inner"
                                   title="Username tidak dapat diubah">
                            <p class="text-[11px] text-gray-400 mt-1">Username ditetapkan oleh Administrator dan tidak dapat diubah</p>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Role</label>
                            <div class="w-full px-4 py-2.5 rounded-xl border border-gray-200 bg-gray-100 text-gray-700 text-sm font-semibold flex items-center justify-between cursor-not-allowed shadow-inner">
                                <span class="capitalize">
                                    {{ match($user->role) {
                                        'super_admin' => 'Super Administrator',
                                        'admin' => 'Administrator',
                                        'pimpinan', 'pimpinan_sekolah' => 'Pimpinan Sekolah',
                                        'guru' => 'Guru',
                                        'siswa' => 'Siswa',
                                        default => str_replace('_', ' ', $user->role)
                                    } }}
                                </span>
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </div>
                            <p class="text-[11px] text-gray-400 mt-1">Hak akses akun diatur langsung oleh Super Administrator</p>
                        </div>
                    </div>
                </div>

                <!-- Pemisah Garis Halus -->
                <div class="border-t border-gray-100 my-8"></div>

                <!-- Sub-bagian 2: Ganti Password -->
                <div class="space-y-6">
                    <div>
                        <h2 class="text-lg font-bold text-gray-900 tracking-tight">Ganti Password</h2>
                        <p class="text-xs text-gray-500 mt-0.5">Kosongkan jika Anda tidak ingin mengubah password akun saat ini</p>
                    </div>

                    @error('current_password')
                        <div class="flex items-center gap-2 px-4 py-3 bg-red-50 border border-red-200 rounded-xl text-sm text-red-700">
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            {{ $message }}
                        </div>
                    @enderror

                    <!-- Field Password Saat Ini -->
                    <div>
                        <label for="current_password" class="block text-sm font-semibold text-gray-700 mb-2">Password Saat Ini</label>
                        <input type="password" id="current_password" name="current_password" autocomplete="current-password"
                               class="w-full px-4 py-2.5 rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-gray-900 text-sm placeholder-gray-400 transition-colors shadow-sm @error('current_password') border-red-400 @enderror"
                               placeholder="Masukkan password saat ini untuk ganti password">
                        <p class="text-[11px] text-gray-400 mt-1">Wajib diisi hanya jika Anda ingin mengubah password</p>
                    </div>

                    <!-- Row 2 Kolom: Password Baru & Konfirmasi Password Baru -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">Password Baru</label>
                            <input type="password" id="password" name="password" autocomplete="new-password"
                                   class="w-full px-4 py-2.5 rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-gray-900 text-sm placeholder-gray-400 transition-colors shadow-sm"
                                   placeholder="Min. 8 karakter, huruf besar+kecil+angka">
                        </div>

                        <div>
                            <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-2">Konfirmasi Password Baru</label>
                            <input type="password" id="password_confirmation" name="password_confirmation" autocomplete="new-password"
                                   class="w-full px-4 py-2.5 rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-gray-900 text-sm placeholder-gray-400 transition-colors shadow-sm"
                                   placeholder="Ketik ulang password baru">
                        </div>
                    </div>
                </div>


                <!-- Tombol Aksi Batal & Simpan -->
                <div class="mt-8 pt-4 flex items-center justify-end gap-3">
                    <a href="{{ route('settings') }}" class="px-5 py-2.5 rounded-xl border border-gray-300 text-gray-700 hover:bg-gray-50 font-semibold text-sm transition-colors">
                        Batal
                    </a>
                    <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold text-sm shadow-md shadow-blue-500/20 transition-all hover:shadow-lg hover:shadow-blue-500/30 active:scale-[0.98]">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path>
                        </svg>
                        Simpan Profil Saya
                    </button>
                </div>
            </form>
        </div>


        <!-- ======================================================== -->
        <!-- KARTU 2: KELOLA PENGGUNA (HANYA MUNCUL UNTUK SUPER ADMIN) -->
        <!-- ======================================================== -->
        @if ($user->role === 'super_admin' && $users)
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 sm:p-8 transition-all hover:shadow-md">
                <!-- Header Card Kelola User -->
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
                    <div>
                        <h2 class="text-xl font-bold text-gray-900 tracking-tight">Kelola Pengguna (User Management)</h2>
                        <p class="text-xs text-gray-500 mt-0.5">Daftar seluruh akun pengguna sistem. Gunakan tombol Tambah User untuk membuat akun pengguna baru.</p>
                    </div>

                    <!-- Tombol Tambah User (Hanya Super Admin) -->
                    <button type="button" onclick="openAddUserModal()" class="inline-flex items-center gap-2 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold text-sm shadow-md shadow-blue-500/20 transition-all hover:shadow-lg hover:shadow-blue-500/30 active:scale-[0.98]">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Tambah User Baru
                    </button>
                </div>

                <!-- Tabel Kelola User -->
                <div class="overflow-x-auto rounded-2xl border border-gray-100">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50/80 text-gray-500 text-xs font-bold uppercase tracking-wider">
                            <tr>
                                <th scope="col" class="px-5 py-3.5 text-center w-16">No</th>
                                <th scope="col" class="px-5 py-3.5 text-left">Nama</th>
                                <th scope="col" class="px-5 py-3.5 text-left">Username</th>
                                <th scope="col" class="px-5 py-3.5 text-left">Role</th>
                                <th scope="col" class="px-5 py-3.5 text-left">Tanggal Dibuat</th>
                                <th scope="col" class="px-5 py-3.5 text-center w-32">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            @forelse ($users as $index => $u)
                                <tr class="hover:bg-blue-50/40 transition-colors">
                                    <td class="px-5 py-4 text-center text-gray-500 font-medium">
                                        {{ $loop->iteration + ($users->currentPage() - 1) * $users->perPage() }}
                                    </td>
                                    <td class="px-5 py-4 font-semibold text-gray-900">
                                        {{ $u->name }}
                                        @if($u->id === auth()->id())
                                            <span class="ml-1.5 inline-block text-[10px] bg-blue-100 text-blue-800 font-bold px-2 py-0.5 rounded-full">Anda</span>
                                        @endif
                                    </td>
                                    <td class="px-5 py-4 text-gray-600 font-mono text-xs">
                                        {{ $u->username }}
                                    </td>
                                    <td class="px-5 py-4">
                                        @php
                                            $badgeClasses = match($u->role) {
                                                'super_admin' => 'bg-rose-50 text-rose-700 border-rose-200',
                                                'admin' => 'bg-purple-50 text-purple-700 border-purple-200',
                                                'pimpinan', 'pimpinan_sekolah' => 'bg-teal-50 text-teal-700 border-teal-200',
                                                'guru' => 'bg-indigo-50 text-indigo-700 border-indigo-200',
                                                'siswa' => 'bg-sky-50 text-sky-700 border-sky-200',
                                                'teknisi' => 'bg-blue-50 text-blue-700 border-blue-200',
                                                'laboran' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                                'viewer' => 'bg-amber-50 text-amber-700 border-amber-200',
                                                default => 'bg-slate-50 text-slate-700 border-slate-200',
                                            };
                                            $roleLabel = match($u->role) {
                                                'super_admin' => 'Super Admin',
                                                'admin' => 'Admin',
                                                'pimpinan', 'pimpinan_sekolah' => 'Pimpinan Sekolah',
                                                'guru' => 'Guru',
                                                'siswa' => 'Siswa',
                                                'teknisi' => 'Teknisi',
                                                'laboran' => 'Laboran',
                                                'viewer' => 'Viewer',
                                                default => ucwords(str_replace('_', ' ', $u->role)),
                                            };
                                        @endphp
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold border {{ $badgeClasses }}">
                                            {{ $roleLabel }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-4 text-gray-500 text-xs">
                                        {{ $u->created_at ? $u->created_at->format('d-m-Y') : '-' }}
                                    </td>
                                    <td class="px-5 py-4 text-center">
                                        <div class="flex items-center justify-center gap-1.5">
                                            @if($u->role !== 'super_admin')
                                                <!-- Tombol Reset Password User (Bila lupa password) -->
                                                <button type="button"
                                                        onclick="openResetPasswordModal({{ $u->id }}, '{{ addslashes($u->name) }}', '{{ $u->username }}')"
                                                        class="p-1.5 text-blue-600 hover:text-blue-800 hover:bg-blue-50 rounded-lg transition-colors"
                                                        title="Reset Password (Lupa Password)">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                                                    </svg>
                                                </button>

                                                <!-- Tombol Edit User -->
                                                <button type="button"
                                                        onclick="openEditUserModal({{ json_encode(['id' => $u->id, 'name' => $u->name, 'username' => $u->username, 'role' => $u->role]) }})"
                                                        class="p-1.5 text-amber-600 hover:text-amber-800 hover:bg-amber-50 rounded-lg transition-colors"
                                                        title="Edit User">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                                    </svg>
                                                </button>

                                                <!-- Tombol Hapus User -->
                                                <button type="button"
                                                        onclick="openDeleteUserModal({{ $u->id }}, '{{ addslashes($u->name) }}', '{{ $u->username }}')"
                                                        class="p-1.5 text-rose-600 hover:text-rose-800 hover:bg-rose-50 rounded-lg transition-colors"
                                                        title="Hapus User">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                                </button>
                                            @else
                                                <span class="text-xs text-gray-400 italic">Akun Utama</span>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-5 py-8 text-center text-gray-500 text-sm">
                                        Belum ada data user tambahan yang terdaftar.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination Kelola User -->
                @if ($users->hasPages())
                    <div class="mt-6">
                        {{ $users->links() }}
                    </div>
                @endif
            </div>

            <!-- ======================================================== -->
            <!-- MODAL 1: TAMBAH USER BARU (KHUSUS ADMIN)                 -->
            <!-- ======================================================== -->
            <div id="addUserModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 hidden items-center justify-center p-4">
                <div class="bg-white rounded-3xl shadow-2xl max-w-md w-full overflow-hidden animate-fade-in border border-gray-100">
                    <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">
                        <h3 class="text-lg font-bold text-gray-900">Tambah User Baru</h3>
                        <button type="button" onclick="closeAddUserModal()" class="text-gray-400 hover:text-gray-600 p-1 rounded-lg">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>

                    <form action="{{ route('users.store') }}" method="POST" class="p-6 space-y-4">
                        @csrf
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Nama Lengkap</label>
                            <input type="text" name="name" required class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm" placeholder="Contoh: Budi Santoso">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Username</label>
                            <input type="text" name="username" required class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm font-mono" placeholder="budisantoso">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Role / Peran</label>
                            <select name="role" required class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                                <option value="admin">Admin</option>
                                <option value="pimpinan">Pimpinan Sekolah</option>
                                <option value="guru">Guru</option>
                                <option value="siswa">Siswa</option>
                                <option value="teknisi">Teknisi</option>
                                <option value="laboran">Laboran</option>
                                <option value="viewer">Viewer</option>
                                <option value="super_admin">Super Admin</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Password</label>
                            <input type="password" name="password" required minlength="8" class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm" placeholder="Minimal 8 karakter">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Konfirmasi Password</label>
                            <input type="password" name="password_confirmation" required minlength="8" class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm" placeholder="Ulangi password">
                        </div>

                        <div class="pt-4 flex items-center justify-end gap-3 border-t border-gray-100">
                            <button type="button" onclick="closeAddUserModal()" class="px-4 py-2.5 rounded-xl border border-gray-300 text-gray-700 hover:bg-gray-50 text-sm font-semibold">Batal</button>
                            <button type="submit" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-bold shadow-md shadow-blue-500/20">Simpan User</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- ======================================================== -->
            <!-- MODAL 2: EDIT USER (KHUSUS ADMIN)                        -->
            <!-- ======================================================== -->
            <div id="editUserModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 hidden items-center justify-center p-4">
                <div class="bg-white rounded-3xl shadow-2xl max-w-md w-full overflow-hidden animate-fade-in border border-gray-100">
                    <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">
                        <h3 class="text-lg font-bold text-gray-900">Edit Data User</h3>
                        <button type="button" onclick="closeEditUserModal()" class="text-gray-400 hover:text-gray-600 p-1 rounded-lg">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>

                    <form id="editUserForm" method="POST" class="p-6 space-y-4">
                        @csrf
                        @method('PUT')
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Nama Lengkap</label>
                            <input type="text" id="edit_name" name="name" required class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Username</label>
                            <input type="text" id="edit_username" name="username" required class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm font-mono">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Role / Peran</label>
                            <select id="edit_role" name="role" required class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                                <option value="admin">Admin</option>
                                <option value="pimpinan">Pimpinan Sekolah</option>
                                <option value="guru">Guru</option>
                                <option value="siswa">Siswa</option>
                                <option value="teknisi">Teknisi</option>
                                <option value="laboran">Laboran</option>
                                <option value="viewer">Viewer</option>
                                <option value="super_admin">Super Admin</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Password Baru (Opsional)</label>
                            <input type="password" name="password" minlength="8" class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm" placeholder="Kosongkan jika tidak diubah">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Konfirmasi Password Baru</label>
                            <input type="password" name="password_confirmation" minlength="8" class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm" placeholder="Ulangi password jika diubah">
                        </div>

                        <div class="pt-4 flex items-center justify-end gap-3 border-t border-gray-100">
                            <button type="button" onclick="closeEditUserModal()" class="px-4 py-2.5 rounded-xl border border-gray-300 text-gray-700 hover:bg-gray-50 text-sm font-semibold">Batal</button>
                            <button type="submit" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-bold shadow-md shadow-blue-500/20">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- ======================================================== -->

            <!-- ======================================================== -->
            <!-- MODAL 4: RESET PASSWORD USER (KHUSUS ADMIN)              -->
            <!-- ======================================================== -->
            <div id="resetPasswordModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 hidden items-center justify-center p-4">
                <div class="bg-white rounded-3xl shadow-2xl max-w-md w-full overflow-hidden animate-fade-in border border-gray-100">
                    <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center flex-shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-base font-bold text-gray-900 leading-tight">Reset Password User</h3>
                                <p class="text-xs text-gray-500">Atur ulang password jika pengguna lupa password</p>
                            </div>
                        </div>
                        <button type="button" onclick="closeResetPasswordModal()" class="text-gray-400 hover:text-gray-600 p-1 rounded-lg">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>

                    <form id="resetPasswordForm" method="POST" class="p-6 space-y-4">
                        @csrf
                        @method('PATCH')

                        <div class="p-3.5 bg-blue-50/70 border border-blue-100 rounded-2xl flex items-start gap-3">
                            <svg class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <div class="text-xs text-blue-900 leading-relaxed">
                                Reset password untuk akun pengguna: <br>
                                <span class="font-bold text-gray-900" id="resetUserName">-</span> 
                                (<span class="font-mono text-blue-700" id="resetUserUsername">-</span>)
                            </div>
                        </div>

                        <div>
                            <div class="flex items-center justify-between mb-1.5">
                                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider">Password Baru</label>
                                <button type="button" onclick="fillDefaultPassword()" class="text-[11px] font-semibold text-blue-600 hover:text-blue-800 hover:underline">
                                    Gunakan default (binatama123)
                                </button>
                            </div>
                            <div class="relative">
                                <input type="password" id="reset_password" name="password" required minlength="8"
                                       class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm pr-10"
                                       placeholder="Minimal 8 karakter">
                                <button type="button" onclick="togglePasswordVisibility('reset_password')" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600" title="Lihat / Sembunyikan Password">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                </button>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Konfirmasi Password Baru</label>
                            <div class="relative">
                                <input type="password" id="reset_password_confirmation" name="password_confirmation" required minlength="8"
                                       class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm pr-10"
                                       placeholder="Ketik ulang password baru">
                                <button type="button" onclick="togglePasswordVisibility('reset_password_confirmation')" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600" title="Lihat / Sembunyikan Password">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                </button>
                            </div>
                        </div>

                        <p class="text-[11px] text-gray-400 leading-tight">
                            Setelah di-reset, berikan password baru ini kepada pengguna bersangkutan agar dapat login kembali.
                        </p>

                        <div class="pt-4 flex items-center justify-end gap-3 border-t border-gray-100">
                            <button type="button" onclick="closeResetPasswordModal()" class="px-4 py-2.5 rounded-xl border border-gray-300 text-gray-700 hover:bg-gray-50 text-sm font-semibold">Batal</button>
                            <button type="submit" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-bold shadow-md shadow-blue-500/20">Reset Password</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Script Interaktivitas Modal Kelola User -->
            <script>
                function openAddUserModal() {
                    const modal = document.getElementById('addUserModal');
                    if (modal) {
                        modal.classList.remove('hidden');
                        modal.classList.add('flex');
                    }
                }

                function closeAddUserModal() {
                    const modal = document.getElementById('addUserModal');
                    if (modal) {
                        modal.classList.add('hidden');
                        modal.classList.remove('flex');
                    }
                }

                function openEditUserModal(user) {
                    const modal = document.getElementById('editUserModal');
                    const form = document.getElementById('editUserForm');
                    if (modal && form) {
                        form.action = `/users/${user.id}`;
                        document.getElementById('edit_name').value = user.name;
                        document.getElementById('edit_username').value = user.username;
                        document.getElementById('edit_role').value = user.role;
                        modal.classList.remove('hidden');
                        modal.classList.add('flex');
                    }
                }

                function closeEditUserModal() {
                    const modal = document.getElementById('editUserModal');
                    if (modal) {
                        modal.classList.add('hidden');
                        modal.classList.remove('flex');
                    }
                }

                function openDeleteUserModal(id, name, username) {
                    Swal.fire({
                        title: 'Hapus Akun Pengguna?',
                        html: `Apakah Anda yakin ingin menghapus akun <b>${name}</b> (<code class="text-blue-600 font-mono text-xs">${username}</code>)?<br><span class="text-xs text-gray-500 mt-1 block">Tindakan ini tidak dapat dibatalkan.</span>`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Ya, Hapus',
                        cancelButtonText: 'Batal',
                        confirmButtonColor: '#e11d48',
                        cancelButtonColor: '#94a3b8',
                        reverseButtons: true,
                        customClass: {
                            popup: 'rounded-3xl shadow-2xl border border-gray-100 font-sans p-6',
                            title: 'text-lg font-black text-gray-900',
                            confirmButton: 'rounded-xl font-bold px-5 py-2.5 shadow-md shadow-rose-500/20 text-sm',
                            cancelButton: 'rounded-xl font-semibold px-5 py-2.5 text-sm'
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            const form = document.createElement('form');
                            form.method = 'POST';
                            form.action = `/users/${id}`;
                            const csrf = document.createElement('input');
                            csrf.type = 'hidden';
                            csrf.name = '_token';
                            csrf.value = '{{ csrf_token() }}';
                            const method = document.createElement('input');
                            method.type = 'hidden';
                            method.name = '_method';
                            method.value = 'DELETE';
                            form.appendChild(csrf);
                            form.appendChild(method);
                            document.body.appendChild(form);
                            form.submit();
                        }
                    });
                }

                function openResetPasswordModal(id, name, username) {
                    const modal = document.getElementById('resetPasswordModal');
                    const form = document.getElementById('resetPasswordForm');
                    if (modal && form) {
                        form.action = `/users/${id}/reset-password`;
                        document.getElementById('resetUserName').textContent = name;
                        document.getElementById('resetUserUsername').textContent = username;
                        document.getElementById('reset_password').value = '';
                        document.getElementById('reset_password_confirmation').value = '';
                        modal.classList.remove('hidden');
                        modal.classList.add('flex');
                    }
                }

                function closeResetPasswordModal() {
                    const modal = document.getElementById('resetPasswordModal');
                    if (modal) {
                        modal.classList.add('hidden');
                        modal.classList.remove('flex');
                    }
                }

                function fillDefaultPassword() {
                    document.getElementById('reset_password').value = 'binatama123';
                    document.getElementById('reset_password_confirmation').value = 'binatama123';
                }

                function togglePasswordVisibility(fieldId) {
                    const field = document.getElementById(fieldId);
                    if (field) {
                        field.type = field.type === 'password' ? 'text' : 'password';
                    }
                }

                // Buka kembali modal tambah user jika ada error validasi
                @if ($errors->any() && old('username') && !old('current_password'))
                    openAddUserModal();
                @endif

                // Tutup modal jika tombol Escape ditekan
                document.addEventListener('keydown', function (e) {
                    if (e.key === 'Escape') {
                        closeAddUserModal();
                        closeEditUserModal();
                        closeResetPasswordModal();
                    }
                });
            </script>
        @endif

    </div>
</x-app-layout>
