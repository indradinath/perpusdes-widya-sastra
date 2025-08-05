<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::view('/', 'welcome');

Route::view('dashboard', 'welcome')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

// Halaman Umum (Frontend) - Tidak dilindungi autentikasi
Route::get('/', function () {
    return view('frontend1.frontend_template');
})->name('home');

// Route::get('/tentang-kami', function () {
//     return view('frontend.about');
// })->name('about');

// Route::get('/kontak', function () {
//     return view('frontend.contact');
// })->name('contact');

Route::view('admin/dashboard', 'admin.dashboard.index')->name('admin.dashboard.index');
Route::view('admin/user','admin.user.index')->name('admin.user.index');
Route::view('admin/kunjungan','admin.kunjungan.index')->name('admin.kunjungan.index');
Route::view('admin/banner','admin.banner.index')->name('admin.banner.index');
Route::view('admin/buku','admin.buku.index')->name('admin.buku.index');
Route::view('admin/kategori','admin.kategori.index')->name('admin.kategori.index');
Route::view('admin/rak','admin.rak.index')->name('admin.rak.index');
Route::view('admin/transaksi','admin.transaksi.index')->name('admin.transaksi.index');

Route::view('anggota/dashboard', 'anggota.dashboard.index')->name('anggota.dashboard.index');
Route::view('anggota/buku', 'anggota.buku.index')->name('anggota.buku.index');
Route::view('anggota/kunjungan', 'anggota.kunjungan.index')->name('anggota.kunjungan.index');
Route::view('anggota/transaksi', 'anggota.transaksi.index')->name('anggota.transaksi.index');
Route::view('anggota/profile', 'anggota.profile.index')->name('anggota.profile.index');


// Route::middleware(['auth'])->group(function () {
//     Route::middleware('role:Admin')->prefix('admin')->name('admin.')->group(function () {
//         Route::view('admin/dashboard', 'admin.dashboard.index')->name('admin.dashboard.index');
//         Route::view('admin/user','admin.user.index')->name('admin.user.index');
//         Route::view('admin/kunjungan','admin.kunjungan.index')->name('admin.kunjungan.index');
//         Route::view('admin/banner','admin.banner.index')->name('admin.banner.index');
//         Route::view('admin/buku','admin.buku.index')->name('admin.buku.index');
//         Route::view('admin/kategori','admin.kategori.index')->name('admin.kategori.index');
//         Route::view('admin/rak','admin.rak.index')->name('admin.rak.index');
//         Route::view('admin/transaksi','admin.transaksi.index')->name('admin.transaksi.index');
//     });

//     Route::middleware('role:Anggota')->prefix('anggota')->name('anggota.')->group(function () {
//         // Route::view('anggota/dashboard', 'anggota.dashboard.index')->name('dashboard.index');
//         Route::get('dashboard', function(){
//             return view('anggota.dashboard.index');
//         })->name('dashboard.index');
//         Route::view('anggota/buku', 'anggota.buku.index')->name('buku.index');
//         Route::view('anggota/kunjungan', 'anggota.kunjungan.index')->name('kunjungan.index');
//         Route::view('anggota/transaksi', 'anggota.transaksi.index')->name('transaksi.index');
//     });
// });





require __DIR__.'/auth.php';
