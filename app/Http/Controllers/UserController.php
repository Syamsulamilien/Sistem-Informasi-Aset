<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // Tampilkan daftar user -> dialihkan ke menu Setting terpadu
    public function index()
    {
        return redirect()->route('settings');
    }

    // Form tambah user (fallback standalone)
    public function create()
    {
        return view('users.create');
    }

    // Simpan user baru
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username|alpha_dash',
            'password' => 'required|min:8|confirmed',
            'role' => 'required|in:super_admin,admin,pimpinan,pimpinan_sekolah,teknisi,viewer,user_biasa,laboran',
        ], [
            'username.alpha_dash' => 'Username hanya boleh berisi huruf, angka, dash dan underscore.',
            'username.unique' => 'Username sudah digunakan.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'role.required' => 'Role harus dipilih.',
            'role.in' => 'Role yang dipilih tidak valid.',
        ]);

        User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->username . '@smkbinatama.sch.id',
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return redirect()->route('settings')
            ->with('success', 'User baru berhasil ditambahkan!');
    }

    // Tampilkan detail user
    public function show(User $user)
    {
        return redirect()->route('settings');
    }

    // Form edit user (fallback standalone)
    public function edit(User $user)
    {
        // Cegah edit akun Super Admin lain
        if ($user->role === 'super_admin' && $user->id !== auth()->id()) {
            return redirect()->route('settings')
                ->with('error', 'Tidak bisa mengedit akun Super Admin lain!');
        }

        return view('users.edit', compact('user'));
    }

    // Update user
    public function update(Request $request, User $user)
    {
        // Cegah edit akun Super Admin lain
        if ($user->role === 'super_admin' && $user->id !== auth()->id()) {
            return redirect()->route('settings')
                ->with('error', 'Tidak bisa mengedit akun Super Admin lain!');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $user->id . '|alpha_dash',
            'password' => 'nullable|min:8|confirmed',
            'role' => 'required|in:super_admin,admin,pimpinan,pimpinan_sekolah,teknisi,viewer,user_biasa,laboran',
        ], [
            'username.alpha_dash' => 'Username hanya boleh berisi huruf, angka, dash dan underscore.',
            'username.unique' => 'Username sudah digunakan.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'role.required' => 'Role harus dipilih.',
            'role.in' => 'Role yang dipilih tidak valid.',
        ]);

        $data = [
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->username . '@smkbinatama.sch.id',
            'role' => $request->role,
        ];

        // Update password hanya jika diisi
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('settings')
            ->with('success', 'Data user berhasil diperbarui!');
    }

    // Hapus user
    public function destroy(User $user)
    {
        // Cegah hapus diri sendiri
        if ($user->id === auth()->id()) {
            return redirect()->route('settings')
                ->with('error', 'Tidak bisa menghapus akun sendiri!');
        }

        // Cegah hapus akun Super Admin
        if ($user->role === 'super_admin') {
            return redirect()->route('settings')
                ->with('error', 'Tidak bisa menghapus akun Super Admin!');
        }

        $user->delete();
        
        return redirect()->route('settings')
            ->with('success', 'User berhasil dihapus!');
    }

    // Reset password user jika user lupa password (khusus Super Admin)
    public function resetPassword(Request $request, User $user)
    {
        // Cegah reset akun Super Admin lain
        if ($user->role === 'super_admin' && $user->id !== auth()->id()) {
            return redirect()->route('settings')
                ->with('error', 'Tidak dapat mereset password akun Super Admin lain!');
        }

        $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ], [
            'password.required' => 'Password baru wajib diisi.',
            'password.min' => 'Password baru minimal harus 8 karakter.',
            'password.confirmed' => 'Konfirmasi password baru tidak cocok.',
        ]);

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('settings')
            ->with('success', "Password untuk pengguna '{$user->name}' ({$user->username}) berhasil di-reset!");
    }
}