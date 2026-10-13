<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AssetController;
use App\Http\Controllers\AssetTypeController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ImportExportController;
use App\Http\Controllers\MaintenanceController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ConsumableController;
use App\Http\Controllers\BorrowingController;

// ==========================================
// 🌐 PUBLIC ROUTES (Bebas Login, Dilindungi Throttle)
// ==========================================
Route::get('/', fn() => redirect()->route('login'));

// Info aset via scan QR Code publik (Dibatasi 60 request/menit untuk cegah scraping/DoS)
Route::get('/asset/{assetCode}/info', [AssetController::class, 'publicInfo'])
    ->name('assets.public-info')
    ->middleware('throttle:60,1')
    ->where('assetCode', '[A-Za-z0-9\-_\.\/]+');

// ==========================================
// 🔒 AUTHENTICATED ROUTES (Harus Login & Terverifikasi)
// ==========================================
Route::middleware(['auth', 'verified'])->group(function () {

    // ------------------------------------------
    // 1. DASHBOARD & PROFIL (Semua Role)
    // ------------------------------------------
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('edit');
        Route::patch('/', [ProfileController::class, 'update'])->name('update');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('destroy');
    });

    Route::get('/change-password', [ProfileController::class, 'changePasswordForm'])->name('change-password');
    Route::post('/change-password', [ProfileController::class, 'updatePassword'])->name('update-password');

    // ------------------------------------------
    // 2. LAPORAN & EKSPOR DATA (Semua Role boleh baca & unduh)
    // ------------------------------------------
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [ReportController::class, 'index'])->name('index');
        Route::get('/pdf', [ReportController::class, 'exportPdf'])->name('pdf');
        Route::get('/view', [ReportController::class, 'viewPdf'])->name('view');
        Route::get('/excel', [ReportController::class, 'exportExcel'])->name('excel');
    });

    Route::prefix('export')->name('export.')->group(function () {
        Route::get('/excel', [ImportExportController::class, 'exportExcel'])->name('excel');
        Route::get('/pdf', [ImportExportController::class, 'exportPdf'])->name('pdf');
    });

    // ------------------------------------------
    // 3. READ-ONLY ENDPOINTS (Aset, Lokasi, Jenis, Maintenance, Barang, Peminjaman)
    // Semua role (admin, teknisi, viewer) berhak melihat data & cetak label
    // ------------------------------------------
    Route::get('/assets', [AssetController::class, 'index'])->name('assets.index');
    Route::get('/assets/{asset}', [AssetController::class, 'show'])->name('assets.show')->whereNumber('asset');
    Route::get('/assets/{asset}/qrcode', [AssetController::class, 'qrcode'])->name('assets.qrcode')->whereNumber('asset');
    Route::get('/assets/{asset}/qrcode/download', [AssetController::class, 'downloadQrCode'])->name('assets.qrcode.download')->whereNumber('asset');
    Route::get('/assets/{asset}/qrcode/print', [AssetController::class, 'printLabel'])->name('assets.qrcode.print')->whereNumber('asset');
    Route::get('/assets/{asset}/qrcode/pdf', [AssetController::class, 'downloadLabelPdf'])->name('assets.qrcode.pdf')->whereNumber('asset');

    Route::get('/locations', [LocationController::class, 'index'])->name('locations.index');
    Route::get('/locations/{location}', [LocationController::class, 'show'])->name('locations.show')->whereNumber('location');

    Route::get('/asset-types', [AssetTypeController::class, 'index'])->name('asset-types.index');

    Route::get('/maintenance', [MaintenanceController::class, 'index'])->name('maintenance.index');
    Route::get('/maintenance/schedules/upcoming', [MaintenanceController::class, 'upcoming'])->name('maintenance.upcoming');
    Route::get('/maintenance/schedules/overdue', [MaintenanceController::class, 'overdue'])->name('maintenance.overdue');
    Route::get('/maintenance/asset/{assetId}/history', [MaintenanceController::class, 'assetHistory'])->name('maintenance.asset-history')->whereNumber('assetId');
    Route::get('/maintenance/{maintenance}', [MaintenanceController::class, 'show'])->name('maintenance.show')->whereNumber('maintenance');

    Route::get('/consumables', [ConsumableController::class, 'index'])->name('consumables.index');
    Route::get('/consumables/{consumable}', [ConsumableController::class, 'show'])->name('consumables.show')->whereNumber('consumable');

    Route::get('/borrowings', [BorrowingController::class, 'index'])->name('borrowings.index');
    Route::get('/borrowings/{borrowing}', [BorrowingController::class, 'show'])->name('borrowings.show')->whereNumber('borrowing');


    // ------------------------------------------
    // 4. LEVEL OPERASIONAL (ADMIN, TEKNISI, LABORAN)
    // Viewer dilarang keras mengakses endpoint manipulasi ini!
    // ------------------------------------------
    Route::middleware(['role:admin,teknisi,laboran'])->group(function () {
        // Edit Aset (Update kondisi/lokasi/status)
        Route::get('/assets/{asset}/edit', [AssetController::class, 'edit'])->name('assets.edit')->whereNumber('asset');
        Route::match(['put', 'patch'], '/assets/{asset}', [AssetController::class, 'update'])->name('assets.update')->whereNumber('asset');

        // Maintenance (Buat jadwal, edit, eksekusi status proses/selesai)
        Route::prefix('maintenance')->name('maintenance.')->group(function () {
            Route::get('/create', [MaintenanceController::class, 'create'])->name('create');
            Route::post('/', [MaintenanceController::class, 'store'])->name('store');
            Route::get('/{maintenance}/edit', [MaintenanceController::class, 'edit'])->name('edit')->whereNumber('maintenance');
            Route::match(['put', 'patch'], '/{maintenance}', [MaintenanceController::class, 'update'])->name('update')->whereNumber('maintenance');
            Route::patch('/{maintenance}/in-progress', [MaintenanceController::class, 'markInProgress'])->name('in-progress')->whereNumber('maintenance');
            Route::patch('/{maintenance}/complete', [MaintenanceController::class, 'complete'])->name('complete')->whereNumber('maintenance');
        });

        // Peminjaman Aset (Catat pinjam & update pengembalian)
        Route::prefix('borrowings')->name('borrowings.')->group(function () {
            Route::get('/create', [BorrowingController::class, 'create'])->name('create');
            Route::post('/', [BorrowingController::class, 'store'])->name('store');
            Route::get('/{borrowing}/edit', [BorrowingController::class, 'edit'])->name('edit')->whereNumber('borrowing');
            Route::match(['put', 'patch'], '/{borrowing}', [BorrowingController::class, 'update'])->name('update')->whereNumber('borrowing');
        });

        // Barang Habis Pakai (Tambah item, edit, dan catat transaksi keluar-masuk)
        Route::prefix('consumables')->name('consumables.')->group(function () {
            Route::get('/create', [ConsumableController::class, 'create'])->name('create');
            Route::post('/', [ConsumableController::class, 'store'])->name('store');
            Route::get('/{consumable}/edit', [ConsumableController::class, 'edit'])->name('edit')->whereNumber('consumable');
            Route::match(['put', 'patch'], '/{consumable}', [ConsumableController::class, 'update'])->name('update')->whereNumber('consumable');
            Route::get('/{consumable}/transaction', [ConsumableController::class, 'transactionForm'])->name('transaction')->whereNumber('consumable');
            Route::post('/{consumable}/transaction', [ConsumableController::class, 'storeTransaction'])->name('storeTransaction')->whereNumber('consumable');
        });
    });


    // ------------------------------------------
    // 5. LEVEL MASTER & ADMIN (ADMINISTRATOR SAJA)
    // Teknisi & Viewer dilarang mengakses tindakan master ini!
    // ------------------------------------------
    Route::middleware(['role:admin'])->group(function () {
        // Master Aset: Buat aset baru, generate kode, dan hapus aset
        Route::get('/assets/create', [AssetController::class, 'create'])->name('assets.create');
        Route::post('/assets', [AssetController::class, 'store'])->name('assets.store');
        Route::post('/assets/generate-code', [AssetController::class, 'generateCode'])->name('assets.generate-code');
        Route::delete('/assets/{asset}', [AssetController::class, 'destroy'])->name('assets.destroy')->whereNumber('asset');

        // Master Lokasi: Buat, edit, hapus, import Excel
        Route::get('/locations/create', [LocationController::class, 'create'])->name('locations.create');
        Route::post('/locations', [LocationController::class, 'store'])->name('locations.store');
        Route::get('/locations/{location}/edit', [LocationController::class, 'edit'])->name('locations.edit')->whereNumber('location');
        Route::match(['put', 'patch'], '/locations/{location}', [LocationController::class, 'update'])->name('locations.update')->whereNumber('location');
        Route::delete('/locations/{location}', [LocationController::class, 'destroy'])->name('locations.destroy')->whereNumber('location');
        Route::get('/locations-template', [LocationController::class, 'downloadTemplate'])->name('locations.template');
        Route::post('/locations-import', [LocationController::class, 'import'])->name('locations.import');

        // Master Jenis Aset: Buat, edit, hapus
        Route::get('/asset-types/create', [AssetTypeController::class, 'create'])->name('asset-types.create');
        Route::post('/asset-types', [AssetTypeController::class, 'store'])->name('asset-types.store');
        Route::get('/asset-types/{asset_type}/edit', [AssetTypeController::class, 'edit'])->name('asset-types.edit')->whereNumber('asset_type');
        Route::match(['put', 'patch'], '/asset-types/{asset_type}', [AssetTypeController::class, 'update'])->name('asset-types.update')->whereNumber('asset_type');
        Route::delete('/asset-types/{asset_type}', [AssetTypeController::class, 'destroy'])->name('asset-types.destroy')->whereNumber('asset_type');

        // Hapus Data Operasional (Hanya Admin yang berhak menghapus rekam data)
        Route::delete('/maintenance/{maintenance}', [MaintenanceController::class, 'destroy'])->name('maintenance.destroy')->whereNumber('maintenance');
        Route::delete('/borrowings/{borrowing}', [BorrowingController::class, 'destroy'])->name('borrowings.destroy')->whereNumber('borrowing');
        Route::delete('/consumables/{consumable}', [ConsumableController::class, 'destroy'])->name('consumables.destroy')->whereNumber('consumable');

        // Import Master Data Aset via Excel
        Route::prefix('import')->name('import.')->group(function () {
            Route::get('/template', [ImportExportController::class, 'downloadTemplate'])->name('template');
            Route::post('/excel', [ImportExportController::class, 'importExcel'])->name('excel');
        });

        // Manajemen User (Akun dibuat & dikelola sepenuhnya oleh Admin)
        Route::resource('users', UserController::class)->except(['show']);
    });

});

// Auth routes (Login, Logout, Password)
require __DIR__ . '/auth.php';

// Fallback untuk endpoint yang tidak sah / tidak ditemukan: auto redirect balik agar tidak ada coba-coba
Route::fallback(function () {
    if (auth()->check()) {
        return redirect()->route('dashboard')->with('error', 'Halaman atau endpoint yang Anda tuju tidak ditemukan.');
    }
    return redirect()->route('login')->with('error', 'Halaman tidak ditemukan. Silakan login terlebih dahulu.');
});
