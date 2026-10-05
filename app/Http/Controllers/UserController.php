<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // Tampilkan daftar user
    public function index()
    {
        // ✅ DIPERBAIKI: Tampilkan semua user kecuali admin
        $users = User::where('role', '!=', 'admin')->latest()->paginate(10);
        return view('users.index', compact('users'));
    }

    // Form tambah user
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
            'role' => 'required|in:teknisi,viewer,user_biasa,laboran', // ✅ TAMBAHAN: Validasi role
        ], [
            'username.alpha_dash' => 'Username hanya boleh berisi huruf, angka, dash dan underscore.',
            'username.unique' => 'Username sudah digunakan.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'role.required' => 'Role harus dipilih.',
            'role.in' => 'Role harus teknisi, viewer, user_biasa, atau laboran.',
        ]);

        User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->username . '@local.dev',
            'password' => Hash::make($request->password),
            'role' => $request->role, // ✅ TAMBAHAN: Ambil dari input
        ]);

        return redirect()->route('users.index')
            ->with('success', 'User berhasil ditambahkan!');
    }

    // Tampilkan detail user
    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }

    // Form edit user
    public function edit(User $user)
    {
        // Cegah edit admin
        if ($user->role === 'admin') {
            return redirect()->route('users.index')
                ->with('error', 'Tidak bisa mengedit akun admin!');
        }

        return view('users.edit', compact('user'));
    }

    // Update user
    public function update(Request $request, User $user)
    {
        // Cegah edit admin
        if ($user->role === 'admin') {
            return redirect()->route('users.index')
                ->with('error', 'Tidak bisa mengedit akun admin!');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $user->id . '|alpha_dash',
            'password' => 'nullable|min:8|confirmed',
            'role' => 'required|in:teknisi,viewer,user_biasa,laboran', // ✅ TAMBAHAN: Validasi role
        ], [
            'username.alpha_dash' => 'Username hanya boleh berisi huruf, angka, dash dan underscore.',
            'username.unique' => 'Username sudah digunakan.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'role.required' => 'Role harus dipilih.',
            'role.in' => 'Role harus teknisi, viewer, user_biasa, atau laboran.',
        ]);

        $data = [
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->username . '@local.dev',
            'role' => $request->role, // ✅ TAMBAHAN: Update role
        ];

        // Update password hanya jika diisi
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('users.index')
            ->with('success', 'User berhasil diupdate!');
    }

    // Hapus user
    public function destroy(User $user)
    {
        // Cegah hapus diri sendiri
        if ($user->id === auth()->id()) {
            return redirect()->route('users.index')
                ->with('error', 'Tidak bisa menghapus akun sendiri!');
        }

        // Cegah hapus admin
        if ($user->role === 'admin') {
            return redirect()->route('users.index')
                ->with('error', 'Tidak bisa menghapus akun admin!');
        }

        $user->delete();
        
        return redirect()->route('users.index')
            ->with('success', 'User berhasil dihapus!');
    }
}