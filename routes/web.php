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

// ==========================
// 🌐 Public route (tanpa login)
// ==========================
Route::get('/', fn() => redirect()->route('login'));

// Info aset via QR tanpa login
Route::get('/asset/{assetCode}/info', [AssetController::class, 'publicInfo'])
    ->name('assets.public-info');

// ==========================
// 🔒 ROUTES PROTECTED (auth + verified)
// ==========================
Route::middleware(['auth', 'verified'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Assets
    Route::resource('assets', AssetController::class);
    Route::prefix('assets')->name('assets.')->group(function () {
        Route::post('/generate-code', [AssetController::class, 'generateCode'])->name('generate-code');
        Route::get('/{asset}/qrcode', [AssetController::class, 'qrcode'])->name('qrcode');
        Route::get('/{asset}/qrcode/download', [AssetController::class, 'downloadQrCode'])->name('qrcode.download');
        Route::get('/{asset}/qrcode/print', [AssetController::class, 'printLabel'])->name('qrcode.print');
        Route::get('/{asset}/qrcode/pdf', [AssetController::class, 'downloadLabelPdf'])->name('qrcode.pdf');
    });

    // Locations
    Route::resource('locations', LocationController::class);
    Route::get('/locations-template', [LocationController::class, 'downloadTemplate'])->name('locations.template');
    Route::post('/locations-import', [LocationController::class, 'import'])->name('locations.import');

    // Asset Types
    Route::resource('asset-types', AssetTypeController::class);

    // ==========================
    // 🔧 Maintenance
    // ==========================
    Route::prefix('maintenance')->name('maintenance.')->group(function () {

        // HARUS DITARUH DI ATAS
        Route::get('/schedules/upcoming', [MaintenanceController::class, 'upcoming'])->name('upcoming');
        Route::get('/schedules/overdue', [MaintenanceController::class, 'overdue'])->name('overdue');
        Route::get('/asset/{assetId}/history', [MaintenanceController::class, 'assetHistory'])->name('asset-history');

        // Status updates
        Route::patch('/{maintenance}/in-progress', [MaintenanceController::class, 'markInProgress'])->name('in-progress');
        Route::patch('/{maintenance}/complete',       [MaintenanceController::class, 'complete'])->name('complete');

        // CRUD
        Route::get('/', [MaintenanceController::class, 'index'])->name('index');
        Route::get('/create', [MaintenanceController::class, 'create'])->name('create');
        Route::post('/', [MaintenanceController::class, 'store'])->name('store');
        Route::get('/{maintenance}', [MaintenanceController::class, 'show'])->name('show');
        Route::get('/{maintenance}/edit', [MaintenanceController::class, 'edit'])->name('edit');
        Route::match(['put', 'patch'], '/{maintenance}', [MaintenanceController::class, 'update'])->name('update');
        Route::delete('/{maintenance}', [MaintenanceController::class, 'destroy'])->name('destroy');
    });

    // Reports
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [ReportController::class, 'index'])->name('index');
        Route::get('/pdf', [ReportController::class, 'exportPdf'])->name('pdf');
        Route::get('/view', [ReportController::class, 'viewPdf'])->name('view');
        Route::get('/excel', [ReportController::class, 'exportExcel'])->name('excel');
    });

    // Import Export
    Route::prefix('import')->name('import.')->group(function () {
        Route::get('/template', [ImportExportController::class, 'downloadTemplate'])->name('template');
        Route::post('/excel', [ImportExportController::class, 'importExcel'])->name('excel');
    });

Route::prefix('export')->name('export.')->group(function () {
    Route::get('/excel', [ImportExportController::class, 'exportExcel'])->name('excel');
    Route::get('/pdf', [ImportExportController::class, 'exportPdf'])->name('pdf');
});

    // ==========================
    // 📦 Consumables (Barang Habis Pakai)
    // ==========================
    Route::resource('consumables', App\Http\Controllers\ConsumableController::class);
    Route::get('/consumables/{consumable}/transaction', [App\Http\Controllers\ConsumableController::class, 'transactionForm'])->name('consumables.transaction');
    Route::post('/consumables/{consumable}/transaction', [App\Http\Controllers\ConsumableController::class, 'storeTransaction'])->name('consumables.storeTransaction');

    // ==========================
    // 🤝 Peminjaman (Borrowings)
    // ==========================
    Route::resource('borrowings', App\Http\Controllers\BorrowingController::class);

    // Profile
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('edit');
        Route::patch('/', [ProfileController::class, 'update'])->name('update');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('destroy');
    });

    // Change Password
    Route::get('/change-password', [ProfileController::class, 'changePasswordForm'])->name('change-password');
    Route::post('/change-password', [ProfileController::class, 'updatePassword'])->name('update-password');

    // ==========================
    // 👥 USER MANAGEMENT (Admin Only)
    // ==========================
   Route::middleware(['admin'])->group(function () {
    // Pakai resource TANPA except, atau kalau mau tanpa show:
    Route::resource('users', UserController::class)->except(['show']);
    // Atau:
    // Route::resource('users', UserController::class);
});

});

// Auth routes (Breeze / Fortify)
require __DIR__ . '/auth.php';
