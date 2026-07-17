<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Pelanggan\HomeController;
use App\Http\Controllers\Pelanggan\KeranjangController;
use App\Http\Controllers\Pelanggan\CheckoutController;
use App\Http\Controllers\Pelanggan\PesananController;
use App\Http\Controllers\Pelanggan\ProfilController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\KategoriController;
use App\Http\Controllers\Admin\ProdukController;
use App\Http\Controllers\Admin\PesananController as AdminPesananController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\LaporanController;
use Illuminate\Support\Facades\Route;

// ─── Auth ────────────────────────────────────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/login',    [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login',   [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register',[AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// ─── Halaman Utama (publik) ───────────────────────────────────────────────────
Route::get('/', [HomeController::class, 'index'])->name('pelanggan.home');
Route::get('/produk/{id}', [HomeController::class, 'detail'])->name('produk.detail');

// ─── Pelanggan ────────────────────────────────────────────────────────────────
Route::middleware(['auth', 'pelanggan'])->group(function () {

    // Keranjang
    Route::get('/keranjang',              [KeranjangController::class, 'index'])->name('keranjang.index');
    Route::post('/keranjang/tambah',      [KeranjangController::class, 'tambah'])->name('keranjang.tambah');
    Route::patch('/keranjang/{id}',       [KeranjangController::class, 'update'])->name('keranjang.update');
    Route::delete('/keranjang/{id}',      [KeranjangController::class, 'hapus'])->name('keranjang.hapus');

    // Checkout
    Route::get('/checkout',               [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout',              [CheckoutController::class, 'proses'])->name('checkout.proses');

    // Pesanan
    Route::get('/pesanan',                [PesananController::class, 'index'])->name('pesanan.index');
    Route::get('/pesanan/{id}',           [PesananController::class, 'detail'])->name('pesanan.detail');
    Route::get('/pesanan/{id}/bayar',     [PesananController::class, 'uploadBukti'])->name('pesanan.upload-bukti');
    Route::post('/pesanan/{id}/bayar',    [PesananController::class, 'simpanBukti'])->name('pesanan.simpan-bukti');

    // Profil
    Route::get('/profil',                 [ProfilController::class, 'index'])->name('profil.index');
    Route::patch('/profil',               [ProfilController::class, 'update'])->name('profil.update');
    Route::patch('/profil/password',      [ProfilController::class, 'updatePassword'])->name('profil.password');
});

// ─── Admin ────────────────────────────────────────────────────────────────────
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Kategori
    Route::resource('kategori', KategoriController::class)->except(['show']);

    // Produk
    Route::resource('produk', ProdukController::class)->except(['show']);

    // Pesanan
    Route::get('/pesanan',                              [AdminPesananController::class, 'index'])->name('pesanan.index');
    Route::get('/pesanan/{id}',                         [AdminPesananController::class, 'detail'])->name('pesanan.detail');
    Route::patch('/pesanan/{id}/status',                [AdminPesananController::class, 'updateStatus'])->name('pesanan.status');
    Route::patch('/pesanan/pembayaran/{id}/konfirmasi', [AdminPesananController::class, 'konfirmasiPembayaran'])->name('pesanan.konfirmasi');
    Route::patch('/pesanan/pembayaran/{id}/tolak',      [AdminPesananController::class, 'tolakPembayaran'])->name('pesanan.tolak');

    // User
    Route::get('/user',         [UserController::class, 'index'])->name('user.index');
    Route::get('/user/{id}',    [UserController::class, 'show'])->name('user.show');
    Route::get('/user/{id}/edit', [UserController::class, 'edit'])->name('user.edit');
    Route::patch('/user/{id}',  [UserController::class, 'update'])->name('user.update');
    Route::delete('/user/{id}', [UserController::class, 'destroy'])->name('user.destroy');

    // Laporan
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
});
