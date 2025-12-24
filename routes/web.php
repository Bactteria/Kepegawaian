<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AbsenController;
use App\Http\Controllers\CutiController;
use App\Models\Karyawan;
use App\Models\User;

// Halaman utama (welcome)
Route::get('/', function () {
    return view('welcome');
})->name('home');

// AUTH ROUTES
Route::get('/login', [LoginController::class, 'index'])->name('login')->middleware('guest');
Route::post('/login', [LoginController::class, 'authenticate'])->name('login.process');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// CRUD karyawan – login wajib, kontrol aksi di controller (role-based)
Route::middleware(['auth'])->group(function () {
    Route::resource('/karyawan', KaryawanController::class);
    // Halaman struktur manager-staff bisa dilihat semua user yang login
    Route::get('/management-level', [KaryawanController::class, 'managementLevel'])->name('management.level');

    // Update relasi manager-staff hanya boleh oleh superadmin
    Route::middleware('role:superadmin')->group(function () {
        Route::post('/management-level/update-staff', [KaryawanController::class, 'updateStaffManager'])->name('management.level.updateStaff');
    });
    Route::get('/calendar', [CalendarController::class, 'index'])->name('calendar.index');
    Route::post('/calendar', [CalendarController::class, 'store'])->name('calendar.store');
    Route::put('/calendar/{id}', [CalendarController::class, 'update'])->name('calendar.update');
    Route::delete('/calendar/{id}', [CalendarController::class, 'destroy'])->name('calendar.destroy');

    // Fitur Absen & Form Cuti
    Route::get('/absen', [AbsenController::class, 'index'])->name('absen.index');
    Route::post('/absen', [AbsenController::class, 'store'])->name('absen.store');

    Route::get('/cuti', [CutiController::class, 'index'])->name('cuti.index');
    Route::post('/cuti', [CutiController::class, 'store'])->name('cuti.store');
    
    // User Management – hanya superadmin
    Route::middleware('role:superadmin')->group(function () {
        Route::resource('/users', UserController::class);

        Route::get('/cuti/manage', [CutiController::class, 'manage'])->name('cuti.manage');
        Route::patch('/cuti/{cuti}/status', [CutiController::class, 'updateStatus'])->name('cuti.update-status');
    });
    
    // Dashboard untuk semua user yang login (superadmin & karyawan)
    Route::get('/dashboard', function () {
        $totalKaryawan = Karyawan::count();
        $totalAdmin = User::whereIn('role', ['superadmin', 'admin'])->count();

        $jumlahLaki = Karyawan::where('gender', 'Laki-laki')->count();
        $jumlahPerempuan = Karyawan::where('gender', 'Perempuan')->count();

        // Komposisi generasi berdasarkan tanggal lahir
        $generationCounts = [
            'Baby Boomers' => Karyawan::whereNotNull('tanggal_lahir')
                ->whereBetween('tanggal_lahir', ['1946-01-01', '1964-12-31'])->count(),
            'Gen X' => Karyawan::whereNotNull('tanggal_lahir')
                ->whereBetween('tanggal_lahir', ['1965-01-01', '1980-12-31'])->count(),
            'Gen Y' => Karyawan::whereNotNull('tanggal_lahir')
                ->whereBetween('tanggal_lahir', ['1981-01-01', '1996-12-31'])->count(),
            'Gen Z' => Karyawan::whereNotNull('tanggal_lahir')
                ->whereBetween('tanggal_lahir', ['1997-01-01', '2012-12-31'])->count(),
        ];

        // Komposisi generasi berdasarkan tanggal_lahir
        $generationCounts = [
            'Baby Boomers' => Karyawan::whereNotNull('tanggal_lahir')
                ->whereBetween('tanggal_lahir', ['1946-01-01', '1964-12-31'])->count(),
            'Gen X' => Karyawan::whereNotNull('tanggal_lahir')
                ->whereBetween('tanggal_lahir', ['1965-01-01', '1980-12-31'])->count(),
            'Gen Y' => Karyawan::whereNotNull('tanggal_lahir')
                ->whereBetween('tanggal_lahir', ['1981-01-01', '1996-12-31'])->count(),
            'Gen Z' => Karyawan::whereNotNull('tanggal_lahir')
                ->whereBetween('tanggal_lahir', ['1997-01-01', '2012-12-31'])->count(),
        ];

        return view('karyawan.dashboard', [
            'totalKaryawan' => $totalKaryawan,
            'totalAdmin' => $totalAdmin,
            'jumlahLaki' => $jumlahLaki,
            'jumlahPerempuan' => $jumlahPerempuan,
            'generationCounts' => $generationCounts,
        ]);
    })->name('dashboard');
});

// KARYAWAN -> halaman profile
Route::middleware(['auth', 'role:karyawan'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/profile/change-password', [ProfileController::class, 'changePassword'])->name('profile.change-password');
    Route::put('/profile/update-password', [ProfileController::class, 'updatePassword'])->name('profile.update-password');
});
