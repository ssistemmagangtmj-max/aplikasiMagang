<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\DosenController;
use App\Http\Controllers\KopsController;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Guest routes
Route::middleware('guest')->group(function () {
    Route::get('/', fn() => redirect()->route('login'));
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// Authenticated routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::post('/confirm-password', [AuthController::class, 'confirmPassword'])->name('password.confirm');

    // Profile API
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');

    // Notifications API
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::patch('/notifications/{notification}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::patch('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.readAll');

    // Mahasiswa routes
    Route::middleware('role:mahasiswa')->prefix('mahasiswa')->name('mahasiswa.')->group(function () {
        Route::get('/dashboard', [MahasiswaController::class, 'dashboard'])->name('dashboard');
        Route::get('/magang', [MahasiswaController::class, 'daftarMagang'])->name('magang');
        Route::get('/magang/perusahaan/{company}', [MahasiswaController::class, 'showCompany'])->name('magang.company');
        Route::post('/magang/apply', [MahasiswaController::class, 'applyMagang'])->name('magang.apply');
        Route::get('/magang/custom', [MahasiswaController::class, 'customApplication'])->name('magang.custom');
        Route::post('/magang/custom', [MahasiswaController::class, 'submitCustomApplication'])->name('magang.custom.submit');
        Route::post('/magang/upload-bukti', [MahasiswaController::class, 'uploadBuktiPenerimaan'])->name('magang.upload_bukti');
        Route::get('/laporan', [MahasiswaController::class, 'laporan'])->name('laporan');
        Route::post('/laporan/upload', [MahasiswaController::class, 'uploadLaporan'])->name('laporan.upload');
        Route::get('/laporan/template', [MahasiswaController::class, 'downloadTemplate'])->name('laporan.template');
    });

    // Admin routes
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::get('/pengguna', [AdminController::class, 'pengguna'])->name('pengguna');
        Route::post('/pengguna', [AdminController::class, 'tambahUser'])->name('pengguna.store');
        Route::get('/pengguna/{user}', [AdminController::class, 'detailPengguna'])->name('pengguna.detail');
        Route::put('/pengguna/{user}', [AdminController::class, 'updatePengguna'])->name('pengguna.update');
        Route::delete('/pengguna/{user}', [AdminController::class, 'deletePengguna'])->name('pengguna.destroy');
        Route::patch('/pengguna/{user}/approve', [AdminController::class, 'approveUser'])->name('pengguna.approve');
        Route::patch('/pengguna/{user}/reject', [AdminController::class, 'rejectUser'])->name('pengguna.reject');
        
        Route::get('/perusahaan', [AdminController::class, 'perusahaan'])->name('perusahaan');
        Route::post('/perusahaan', [AdminController::class, 'tambahPerusahaan'])->name('perusahaan.store');
        Route::put('/perusahaan/{company}', [AdminController::class, 'updatePerusahaan'])->name('perusahaan.update');
        Route::delete('/perusahaan/{company}', [AdminController::class, 'deletePerusahaan'])->name('perusahaan.destroy');
        
        Route::get('/mahasiswa-magang', [AdminController::class, 'daftarMahasiswaMagang'])->name('mahasiswa_magang');
        
        Route::get('/pengajuan-magang', [AdminController::class, 'daftarPengajuanMagang'])->name('pengajuan_magang');
        Route::get('/pengajuan-magang/{application}/download-bukti', [AdminController::class, 'downloadBuktiPenerimaan'])->name('pengajuan_magang.download_bukti');
        Route::get('/pengajuan-magang/{application}', [AdminController::class, 'detailPengajuan'])->name('pengajuan_magang.detail');
        Route::patch('/pengajuan-magang/{application}/tolak', [AdminController::class, 'tolakPengajuan'])->name('pengajuan_magang.tolak');
        Route::post('/pengajuan-magang/{application}/terbitkan', [AdminController::class, 'terbitkanSurat'])->name('pengajuan_magang.terbitkan');
        Route::patch('/pengajuan-magang/{application}/approve-bukti', [AdminController::class, 'approveBukti'])->name('pengajuan_magang.approve_bukti');
        Route::patch('/pengajuan-magang/{application}/reject-bukti', [AdminController::class, 'rejectBukti'])->name('pengajuan_magang.reject_bukti');
    });

    // Dosen Pembimbing routes
    Route::middleware('role:dosen')->prefix('dosen')->name('dosen.')->group(function () {
        Route::get('/dashboard', [DosenController::class, 'dashboard'])->name('dashboard');
        Route::get('/laporan', [DosenController::class, 'laporan'])->name('laporan');
        Route::get('/laporan/{user}', [DosenController::class, 'showMahasiswaLaporan'])->name('laporan.detail');
        Route::patch('/laporan/{report}/approve', [DosenController::class, 'approveReport'])->name('laporan.approve');
        Route::post('/laporan/{report}/revise', [DosenController::class, 'reviseReport'])->name('laporan.revise');
    });
});
