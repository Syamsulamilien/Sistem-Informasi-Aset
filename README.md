<div align="center">

<img src="public/images/logobinatama.png" alt="Logo SMK Kesehatan Binatama" width="100"/>

# SIASET
### Sistem Informasi Aset
**SMK Kesehatan Binatama**

[![Laravel](https://img.shields.io/badge/Laravel-12.x-FF2D20?style=flat-square&logo=laravel&logoColor=white)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?style=flat-square&logo=php&logoColor=white)](https://php.net)
[![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-3.x-06B6D4?style=flat-square&logo=tailwindcss&logoColor=white)](https://tailwindcss.com)
[![Alpine.js](https://img.shields.io/badge/Alpine.js-3.x-8BC0D0?style=flat-square&logo=alpinedotjs&logoColor=white)](https://alpinejs.dev)
[![MySQL](https://img.shields.io/badge/MySQL-8.x-4479A1?style=flat-square&logo=mysql&logoColor=white)](https://mysql.com)
[![Vite](https://img.shields.io/badge/Vite-7.x-646CFF?style=flat-square&logo=vite&logoColor=white)](https://vitejs.dev)

> Sistem manajemen aset dan inventaris berbasis web untuk SMK Kesehatan Binatama — mengelola aset, peminjaman, perawatan, barang habis pakai, dan laporan secara terpusat.

</div>

---

## 📋 Daftar Isi

- [Fitur Utama](#-fitur-utama)
- [Teknologi](#-teknologi)
- [Persyaratan Sistem](#-persyaratan-sistem)
- [Instalasi](#-instalasi)
- [Konfigurasi](#-konfigurasi)
- [Menjalankan Aplikasi](#-menjalankan-aplikasi)
- [Struktur Role & Akses](#-struktur-role--akses)
- [Modul Aplikasi](#-modul-aplikasi)
- [Struktur Direktori](#-struktur-direktori)
- [Keamanan](#-keamanan)
- [Kontribusi](#-kontribusi)

---

## ✨ Fitur Utama

| Fitur | Deskripsi |
|---|---|
| 📦 **Manajemen Aset** | CRUD aset lengkap dengan foto, QR Code, kode unik, dan riwayat perubahan |
| 🏷️ **Kategori & Tipe** | Klasifikasi aset berdasarkan jenis dan kategori (Perabot, Elektronik, dll.) |
| 📍 **Manajemen Lokasi** | Pencatatan lokasi aset per ruangan, gedung, dan lantai |
| 🔧 **Pemeliharaan** | Pencatatan jadwal dan riwayat perawatan/perbaikan aset |
| 🤝 **Peminjaman** | Sistem peminjaman dan pengembalian aset dengan status real-time |
| 🧪 **Barang Habis Pakai** | Manajemen stok consumable dengan transaksi masuk/keluar |
| 📊 **Laporan & Ekspor** | Ekspor data ke Excel (.xlsx) dan cetak PDF |
| 📥 **Import Data** | Import aset dan lokasi massal dari file Excel |
| 📱 **QR Code Scanner** | Generate dan scan QR Code per aset untuk akses cepat |
| 👥 **Manajemen Pengguna** | CRUD pengguna dengan sistem role bertingkat |
| 🔔 **Notifikasi SweetAlert2** | Popup notifikasi modern untuk semua aksi |
| 📈 **Dashboard Statistik** | Ringkasan data aset, peminjaman, dan pemeliharaan |

---

## 🛠️ Teknologi

### Backend
- **[Laravel 12.x](https://laravel.com)** — PHP Framework
- **[PHP 8.2+](https://php.net)** — Server-side language
- **[MySQL 8.x](https://mysql.com)** — Database
- **[Laravel Breeze](https://github.com/laravel/breeze)** — Authentication scaffolding
- **[Maatwebsite Excel](https://github.com/Maatwebsite/Laravel-Excel)** — Import/Ekspor Excel
- **[barryvdh/laravel-dompdf](https://github.com/barryvdh/laravel-dompdf)** — Generate PDF
- **[SimpleSoftwareIO/simple-qrcode](https://github.com/SimpleSoftwareIO/simple-qrcode)** — QR Code generator

### Frontend
- **[Tailwind CSS 3.x](https://tailwindcss.com)** — Utility-first CSS framework
- **[Alpine.js 3.x](https://alpinejs.dev)** — Lightweight JS framework
- **[SweetAlert2](https://sweetalert2.github.io)** — Beautiful popup alerts
- **[Vite 7.x](https://vitejs.dev)** — Frontend build tool

---

## 💻 Persyaratan Sistem

Sebelum instalasi, pastikan sistem Anda memenuhi persyaratan berikut:

- **PHP** >= 8.2 (dengan ekstensi: `pdo`, `mbstring`, `openssl`, `tokenizer`, `xml`, `gd`, `zip`)
- **Composer** >= 2.x
- **Node.js** >= 18.x & **NPM** >= 9.x
- **MySQL** >= 8.x
- **Git**

---

## 🚀 Instalasi

### 1. Clone Repository

```bash
git clone https://github.com/Syamsulamilien/Sistem-Informasi-Aset.git
cd Sistem-Informasi-Aset
```

### 2. Install Dependensi PHP

```bash
composer install
```

### 3. Install Dependensi Node.js

```bash
npm install
```

### 4. Salin File Environment

```bash
cp .env.example .env
```

### 5. Generate Application Key

```bash
php artisan key:generate
```

### 6. Konfigurasi Database

Edit file `.env` dan sesuaikan konfigurasi database:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=siaset
DB_USERNAME=root
DB_PASSWORD=your_password
```

### 7. Jalankan Migrasi Database

```bash
php artisan migrate
```

### 8. Buat Akun Admin Pertama

```bash
php artisan tinker
```

```php
App\Models\User::create([
    'name'     => 'Administrator',
    'username' => 'admin',
    'email'    => 'admin@siaset.sch.id',
    'password' => bcrypt('password'),
    'role'     => 'admin',
]);
```

### 9. Buat Storage Symlink

```bash
php artisan storage:link
```

### 10. Build Assets Frontend

```bash
npm run build
```

---

## ⚙️ Konfigurasi

### Konfigurasi Session (`.env`)

```env
SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_HTTP_ONLY=true
SESSION_SAME_SITE=strict
```

### Konfigurasi untuk Production

> ⚠️ **Penting!** Ubah nilai berikut sebelum deploy ke server production:

```env
APP_ENV=production
APP_DEBUG=false
SESSION_SECURE_COOKIE=true   # Aktifkan saat menggunakan HTTPS
```

---

## ▶️ Menjalankan Aplikasi

### Mode Development

Jalankan kedua perintah ini secara bersamaan (dua terminal berbeda):

```bash
# Terminal 1 — Laravel development server
php artisan serve

# Terminal 2 — Vite HMR (Hot Module Replacement)
npm run dev
```

Akses aplikasi di: **http://localhost:8000**

### Mode Production

```bash
npm run build
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

## 👥 Struktur Role & Akses

Aplikasi menggunakan sistem **Role-Based Access Control (RBAC)** dengan 8 level peran:

| Role | Ikon | Akses |
|---|---|---|
| **Super Admin** | 🔴 | Akses absolut — CRUD semua data, kelola semua modul, dan manajemen pengguna (user management) |
| **Admin** | 🟣 | Akses operasional & master — CRUD aset, lokasi, jenis aset, maintenance, peminjaman, barang habis pakai, import/ekspor, laporan (tanpa manajemen user) |
| **Pimpinan Sekolah** | 🟢 | Khusus pimpinan — hanya dapat mengajukan peminjaman aset dan melihat/unduh laporan |
| **Guru** | 👨‍🏫 | Khusus guru — hanya dapat mengajukan permohonan peminjaman aset mandiri |
| **Siswa** | 🧑‍🎓 | Khusus siswa — hanya dapat mengajukan permohonan peminjaman aset mandiri |
| **Teknisi** | 🔵 | Kelola aset, update kondisi, catat pemeliharaan |
| **Laboran** | 🟡 | Kelola peminjaman, barang habis pakai |
| **Viewer** | ⚪ | Hanya bisa melihat data (read-only) |

> Registrasi publik **dinonaktifkan**. Akun hanya dapat dibuat oleh Super Admin.

---

## 📦 Modul Aplikasi

```
📊 Dashboard          — Statistik ringkasan & grafik aset
📦 Aset               — CRUD aset + QR Code + riwayat + foto
🏷️  Jenis Aset         — Manajemen kategori dan tipe aset
📍 Lokasi             — Manajemen ruangan/gedung/lantai
🔧 Pemeliharaan       — Jadwal & riwayat servis/perbaikan
🤝 Peminjaman         — Sistem peminjaman & pengembalian
🧪 Barang Habis Pakai — Stok & transaksi consumable
📊 Laporan            — Cetak PDF & ekspor Excel
👥 Pengaturan         — Manajemen pengguna & profil akun
```

---

## 📁 Struktur Direktori

```
Sistem-Informasi-Aset/
├── app/
│   ├── Http/
│   │   ├── Controllers/        # AssetController, BorrowingController, dll.
│   │   ├── Middleware/         # CheckRole, IsAdmin, SecurityHeaders
│   │   └── Requests/           # Form Request validation
│   ├── Models/                 # Asset, User, Borrowing, Maintenance, dll.
│   ├── Policies/               # AssetPolicy, BorrowingPolicy, dll.
│   ├── Imports/                # AssetsImport, LocationsImport (Excel)
│   └── Exports/                # AssetsExport (Excel)
├── database/
│   └── migrations/             # Semua file migrasi database
├── resources/
│   ├── css/                    # app.css (Tailwind)
│   ├── js/                     # app.js, table-sort.js (Alpine.js)
│   └── views/
│       ├── layouts/            # app.blade.php, sidebar.blade.php
│       ├── assets/             # Halaman manajemen aset
│       ├── borrowings/         # Halaman peminjaman
│       ├── maintenance/        # Halaman pemeliharaan
│       ├── consumables/        # Halaman barang habis pakai
│       ├── reports/            # Halaman laporan
│       └── settings/           # Halaman pengaturan & pengguna
├── routes/
│   ├── web.php                 # Semua route utama dengan RBAC
│   └── auth.php                # Route autentikasi
└── public/
    ├── images/                 # Logo & favicon
    └── build/                  # Asset hasil build Vite
```

---

## 🔒 Keamanan

Aplikasi telah diimplementasikan dengan berbagai lapisan keamanan:

- ✅ **SQL Injection** — Eloquent ORM + query binding
- ✅ **XSS** — Blade auto-escape + Content-Security-Policy header
- ✅ **CSRF** — Token CSRF di semua form
- ✅ **Brute Force** — Rate limiting 5x/menit pada login
- ✅ **Session Security** — `SameSite=Strict`, `HttpOnly`, regenerasi session
- ✅ **Upload Validation** — Hanya `jpg/jpeg/png/webp`, maks 2MB
- ✅ **RBAC** — Middleware + Policy per controller
- ✅ **Security Headers** — X-Frame-Options, X-Content-Type-Options, HSTS, Referrer-Policy

---

## 🤝 Tim Pengembang

Project ini dikembangkan sebagai **Capstone Project Semester 7** secara kolaboratif oleh tim berikut:

| No | Nama | Peran |
|---|---|---|
| 1 | **Anggi** | Project Lead |
| 2 | **Dzaki** | Backend Developer |
| 3 | **Syamsul Amilien** | Frontend Developer |
| 4 | **Sindi** | UI/UX Designer & Tester |
| 5 | **Putri** | Dokumentasi & Tester |

**Institusi:** SMK Kesehatan Binatama  
**Program Keahlian:** Rekayasa Perangkat Lunak  
**Tahun:** 2025 – 2026

---

<div align="center">

Made with ❤️ by **Tim Capstone SMK Kesehatan Binatama**

</div>
