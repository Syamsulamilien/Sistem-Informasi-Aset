<?php

namespace App\View\Components;

use Illuminate\Support\Facades\Route;
use Illuminate\View\Component;
use Illuminate\View\View;

class AppLayout extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public ?string $title = null
    ) {}

    /**
     * Get the view / contents that represents the component.
     */
    public function render(): View
    {
        $pageTitle = $this->title ?? $this->resolvePageTitle();

        return view('layouts.app', [
            'pageTitle' => $pageTitle,
        ]);
    }

    /**
     * Resolve page title automatically from active route or path.
     */
    protected function resolvePageTitle(): string
    {
        $routeName = Route::currentRouteName() ?? request()->route()?->getName();

        $titles = [
            'dashboard' => 'Dashboard',
            'assets.index' => 'Daftar Aset',
            'assets.create' => 'Tambah Aset',
            'assets.edit' => 'Edit Aset',
            'assets.show' => 'Detail Aset',
            'assets.qrcode' => 'Cetak QR Code Aset',
            'asset-types.index' => 'Kategori Aset',
            'asset-types.create' => 'Tambah Kategori Aset',
            'asset-types.edit' => 'Edit Kategori Aset',
            'locations.index' => 'Lokasi & Ruangan',
            'locations.create' => 'Tambah Ruangan',
            'locations.edit' => 'Edit Ruangan',
            'locations.show' => 'Detail Ruangan',
            'consumables.index' => 'Barang Habis Pakai',
            'consumables.create' => 'Tambah Barang Habis Pakai',
            'consumables.edit' => 'Edit Barang Habis Pakai',
            'consumables.show' => 'Detail Barang Habis Pakai',
            'consumables.transaction' => 'Transaksi Barang Habis Pakai',
            'borrowings.index' => 'Peminjaman Aset',
            'borrowings.create' => 'Tambah Peminjaman',
            'borrowings.edit' => 'Edit Peminjaman',
            'borrowings.show' => 'Detail Peminjaman',
            'maintenance.index' => 'Pemeliharaan Aset',
            'maintenance.create' => 'Jadwal Pemeliharaan Baru',
            'maintenance.edit' => 'Edit Pemeliharaan',
            'maintenance.show' => 'Detail Pemeliharaan',
            'maintenance.upcoming' => 'Jadwal Pemeliharaan Mendatang',
            'maintenance.overdue' => 'Pemeliharaan Terlambat',
            'maintenance.asset-history' => 'Riwayat Pemeliharaan',
            'reports.index' => 'Laporan Rekapitulasi Aset',
            'reports.view' => 'Pratinjau Laporan',
            'users.index' => 'Manajemen Pengguna',
            'users.create' => 'Tambah Pengguna',
            'users.edit' => 'Edit Pengguna',
            'profile.edit' => 'Profil Saya',
            'change-password' => 'Ubah Password',
            'settings' => 'Settings',
        ];

        if ($routeName && isset($titles[$routeName])) {
            return $titles[$routeName];
        }

        if ($routeName) {
            $parts = explode('.', $routeName);
            $action = end($parts);
            $resource = $parts[0] ?? '';
            $resourceName = ucwords(str_replace(['-', '_'], ' ', $resource));
            return match ($action) {
                'index' => 'Daftar ' . $resourceName,
                'create' => 'Tambah ' . $resourceName,
                'edit' => 'Edit ' . $resourceName,
                'show' => 'Detail ' . $resourceName,
                default => ucwords(str_replace(['-', '_', '.'], ' ', $routeName))
            };
        }

        // Fallback berdasarkan pola URL Request
        if (request()->is('dashboard*')) return 'Dashboard';
        if (request()->is('assets/create*')) return 'Tambah Aset';
        if (request()->is('assets/*/edit*')) return 'Edit Aset';
        if (request()->is('assets*')) return 'Daftar Aset';
        if (request()->is('asset-types*')) return 'Kategori Aset';
        if (request()->is('locations*')) return 'Lokasi & Ruangan';
        if (request()->is('consumables*')) return 'Barang Habis Pakai';
        if (request()->is('borrowings*')) return 'Peminjaman Aset';
        if (request()->is('maintenance*')) return 'Pemeliharaan Aset';
        if (request()->is('reports*')) return 'Laporan Aset';
        if (request()->is('users*')) return 'Manajemen Pengguna';
        if (request()->is('profile*')) return 'Profil Saya';
        if (request()->is('change-password*')) return 'Ubah Password';

        return '';
    }
}
