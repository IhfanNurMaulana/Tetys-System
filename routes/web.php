<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GradeController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\ReportController;

Route::middleware('auth')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('home');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Route Halaman Manajemen Kelas
    Route::apiResource('/grade', GradeController::class);

    // Route Halaman Manajemen Siswa
    Route::get('/student/download-excel', [StudentController::class, 'downloadExcel'])
        ->name('student.download-excel');
    Route::post('/student/import', [StudentController::class, 'importExcel'])
        ->name('student.import-excel');
    Route::apiResource('/student', StudentController::class);

    // Route Halaman Profil User
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // Route Halaman Transaksi
    Route::post('/transaction/student', [TransactionController::class, 'getStudent'])
        ->name('transaction.getstudent');
    Route::apiResource('/transaction', TransactionController::class)
        ->only(['index', 'create', 'store']);

    // Route Halaman Laporan
    Route::post('/report/student', [ReportController::class, 'getStudent'])
        ->name('report.getstudent');
    Route::get('/report/download/{type}', [ReportController::class, 'downloadPdf'])
        ->name('report.download');
    Route::apiResource('/report', ReportController::class)
        ->only(['index', 'store']);
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
