<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AssetController;
use App\Http\Controllers\AssetTypeController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ImportExportController;
use App\Http\Controllers\MaintenanceController; // ✅ Tambahkan controller Maintenance

// ==========================
// 🌐 Public route (tanpa login)
// ==========================
Route::get('/', function () {
    return redirect()->route('login');
});

// Informasi aset via QR Code (tanpa login)
Route::get('/asset/{assetCode}/info', [AssetController::class, 'publicInfo'])
    ->name('assets.public-info');

// ==========================
// 🔒 Route dengan middleware auth & verified
// ==========================
Route::middleware(['auth', 'verified'])->group(function () {

    // 📊 Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // 💻 Assets
    Route::resource('assets', AssetController::class);
    Route::prefix('assets')->name('assets.')->group(function () {
        Route::post('/generate-code', [AssetController::class, 'generateCode'])->name('generate-code');
        Route::get('/{asset}/qrcode', [AssetController::class, 'qrcode'])->name('qrcode');
        Route::get('/{asset}/qrcode/download', [AssetController::class, 'downloadQrCode'])->name('qrcode.download');
        Route::get('/{asset}/qrcode/print', [AssetController::class, 'printLabel'])->name('qrcode.print');
    });

    // 📍 Locations
    Route::resource('locations', LocationController::class);
    Route::get('/locations-template', [LocationController::class, 'downloadTemplate'])->name('locations.template');
    Route::post('/locations-import', [LocationController::class, 'import'])->name('locations.import');

    // 🧩 Asset Types
    Route::resource('asset-types', AssetTypeController::class);

    // 🔧 Maintenance Management (diletakkan setelah Asset Types dan sebelum Reports)
    Route::prefix('maintenance')->name('maintenance.')->group(function () {

        // ⚠️ Route khusus (HARUS di atas {maintenance})
        Route::get('/schedules/upcoming', [MaintenanceController::class, 'upcoming'])->name('upcoming');
        Route::get('/schedules/overdue', [MaintenanceController::class, 'overdue'])->name('overdue');
        Route::get('/asset/{assetId}/history', [MaintenanceController::class, 'assetHistory'])->name('asset-history');

        // 🧰 Standard CRUD routes
        Route::get('/', [MaintenanceController::class, 'index'])->name('index');
        Route::get('/create', [MaintenanceController::class, 'create'])->name('create');
        Route::post('/', [MaintenanceController::class, 'store'])->name('store');
        Route::get('/{maintenance}', [MaintenanceController::class, 'show'])->name('show');
        Route::get('/{maintenance}/edit', [MaintenanceController::class, 'edit'])->name('edit');
        Route::match(['put', 'patch'], '/{maintenance}', [MaintenanceController::class, 'update'])->name('update');
        Route::delete('/{maintenance}', [MaintenanceController::class, 'destroy'])->name('destroy');

        // ✅ Action routes
        Route::patch('/{maintenance}/complete', [MaintenanceController::class, 'complete'])->name('complete');
    });

    // 📈 Reports
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [ReportController::class, 'index'])->name('index');
        Route::get('/pdf', [ReportController::class, 'exportPdf'])->name('pdf');
        Route::get('/excel', [ReportController::class, 'exportExcel'])->name('excel');
    });

    // 📦 Import & Export
    Route::prefix('import')->name('import.')->group(function () {
        Route::get('/template', [ImportExportController::class, 'downloadTemplate'])->name('template');
        Route::post('/excel', [ImportExportController::class, 'importExcel'])->name('excel');
    });

    Route::prefix('export')->name('export.')->group(function () {
        Route::get('/excel', [ImportExportController::class, 'exportExcel'])->name('excel');
        Route::get('/pdf', [ImportExportController::class, 'exportPdf'])->name('pdf');
    });

    // 👤 Profile
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('edit');
        Route::patch('/', [ProfileController::class, 'update'])->name('update');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('destroy');
    });
});

// 🔐 Auth routes (login, register, dll)
require __DIR__ . '/auth.php';
