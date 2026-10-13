<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class SettingController extends Controller
{
    /**
     * Tampilkan halaman Settings terpadu (Profil, Password, dan Kelola User jika Admin).
     */
    public function index(Request $request)
    {
        $user = $request->user();

        // Hanya Administrator yang memuat data daftar user untuk Kelola User
        $users = null;
        if ($user->role === 'admin') {
            $users = User::latest()->paginate(10)->withQueryString();
        }

        return view('settings.index', compact('user', 'users'));
    }

    /**
     * Update profil dan password akun yang sedang login.
     * Semua role diizinkan mengubah nama dan password akunnya sendiri.
     */
    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'password' => ['nullable', 'confirmed', Password::min(8)],
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'password.min' => 'Password baru minimal harus 8 karakter.',
            'password.confirmed' => 'Konfirmasi password baru tidak cocok.',
        ]);

        $user->name = $validated['name'];

        if ($request->filled('password')) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return redirect()->route('settings')->with('success', 'Informasi akun dan password berhasil disimpan!');
    }
}
