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
     * Wajib verifikasi password saat ini sebelum ganti password (cegah session hijack).
     */
    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $rules = [
            'name' => ['required', 'string', 'max:255'],
        ];

        $messages = [
            'name.required' => 'Nama lengkap wajib diisi.',
        ];

        // Validasi password hanya jika field password diisi
        if ($request->filled('password')) {
            $rules['current_password'] = ['required', 'string'];
            $rules['password'] = ['required', 'confirmed', Password::min(8)
                ->mixedCase()
                ->numbers()];

            $messages['current_password.required'] = 'Password saat ini wajib diisi untuk mengganti password.';
            $messages['password.min'] = 'Password baru minimal harus 8 karakter.';
            $messages['password.confirmed'] = 'Konfirmasi password baru tidak cocok.';
        }

        $validated = $request->validate($rules, $messages);

        // Verifikasi password lama sebelum update password baru
        if ($request->filled('password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'Password saat ini tidak sesuai.'])->withInput();
            }
        }

        $user->name = $validated['name'];

        if ($request->filled('password')) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return redirect()->route('settings')->with('success', 'Informasi akun berhasil disimpan!');
    }
}

