<?php

use App\Http\Controllers\BarangController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StockMovementController;
use App\Http\Controllers\TransaksiController;
use App\Models\Barang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

require __DIR__.'/auth.php';

Route::middleware(['auth', 'verified'])->group(function () {

    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Barang
    Route::resource('barang', BarangController::class);
    Route::get('barang/{barang}/stok-masuk', [BarangController::class, 'stockIn'])->name('barang.stok-masuk');
    Route::post('barang/{barang}/stok-masuk', [BarangController::class, 'storeStockIn'])->name('barang.stok-masuk.store');

    // Stock movements
    Route::get('stock-movements', [StockMovementController::class, 'index'])->name('stock-movements.index');
    Route::get('stock-movements/create', [StockMovementController::class, 'create'])->name('stock-movements.create');
    Route::post('stock-movements', [StockMovementController::class, 'store'])->name('stock-movements.store');

    // Transaksi
    Route::resource('transaksi', TransaksiController::class);
    Route::get('transaksi/{transaksi}/nota', [TransaksiController::class, 'nota'])->name('transaksi.nota');
    Route::patch('transaksi/{transaksi}/status', [TransaksiController::class, 'updateStatus'])->name('transaksi.status');

    // Laporan
    Route::get('laporan/penjualan', [LaporanController::class, 'penjualan'])->name('laporan.penjualan');
    Route::get('laporan/laba-rugi', [LaporanController::class, 'labaRugi'])->name('laporan.laba-rugi');

    // Profil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // API: cari barang (autocomplete form transaksi)
    Route::get('/api/barang/search', function (Request $request) {
        $q = $request->get('q', '');
        $results = Barang::where('nama_barang', 'like', "%{$q}%")
            ->orWhere('kode_barang', 'like', "%{$q}%")
            ->select('id', 'kode_barang', 'nama_barang', 'harga_jual', 'stok', 'satuan')
            ->limit(10)->get();

        return response()->json($results);
    })->name('api.barang.search');

});
