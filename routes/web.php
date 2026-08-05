<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\NegotiationController;
use App\Http\Controllers\OrderMessageController;
use App\Http\Controllers\OrderFileController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// ================= HALAMAN PUBLIK (tidak perlu login) =================
Route::get('/', [ServiceController::class, 'index'])->name('home');
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/jasa', [ServiceController::class, 'index'])->name('services.index');
Route::get('/jasa/{service}', [ServiceController::class, 'show'])->name('services.show');

// ================= HALAMAN UNTUK USER YANG SUDAH LOGIN =================
Route::middleware('auth')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Jasa (ajukan)
    Route::get('/jasa/ajukan', [ServiceController::class, 'create'])->name('services.create');
    Route::post('/jasa', [ServiceController::class, 'store'])->name('services.store');

    // Pesanan
    Route::get('/pesanan', [OrderController::class, 'index'])->name('orders.index');
    Route::post('/pesanan', [OrderController::class, 'store'])->name('orders.store');
    Route::get('/pesanan/{order}', [OrderController::class, 'show'])->name('orders.show');

    // Negosiasi
    Route::post('/pesanan/{order}/nego', [NegotiationController::class, 'store'])->name('negotiations.store');
    Route::patch('/pesanan/{order}/nego/{negotiation}/terima', [NegotiationController::class, 'accept'])->name('negotiations.accept');

    // Diskusi
    Route::post('/pesanan/{order}/pesan', [OrderMessageController::class, 'store'])->name('order-messages.store');

    // File pesanan (upload kebutuhan/hasil, approve, revisi)
    Route::post('/pesanan/{order}/file', [OrderFileController::class, 'store'])->name('order-files.store');
    Route::patch('/pesanan/{order}/setujui', [OrderFileController::class, 'approve'])->name('order-files.approve');
    Route::post('/pesanan/{order}/revisi', [OrderFileController::class, 'requestRevision'])->name('order-files.revise');

    // Pembayaran
    Route::post('/payments', [PaymentController::class, 'store'])->name('payments.store');

    // Review
    Route::post('/pesanan/{order}/review', [ReviewController::class, 'store'])->name('reviews.store');

    // Laporan
    Route::post('/laporan', [ReportController::class, 'store'])->name('reports.store');

    // Profil
    Route::get('/profil', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profil', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profil', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ================= KHUSUS ADMIN =================
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {

    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

    // Approval jasa
    Route::get('/services/pending', [ServiceController::class, 'pending'])->name('admin.services.pending');
    Route::patch('/services/{service}/approve', [AdminController::class, 'approveService'])->name('admin.services.approve');
    Route::patch('/services/{service}/reject', [AdminController::class, 'rejectService'])->name('admin.services.reject');

    // Verifikasi pembayaran
    Route::get('/payments', [PaymentController::class, 'index'])->name('admin.payments.index');
    Route::patch('/payments/{payment}/verify', [PaymentController::class, 'verify'])->name('admin.payments.verify');
    Route::patch('/payments/{payment}/reject', [PaymentController::class, 'reject'])->name('admin.payments.reject');

    // Pencairan dana
    Route::patch('/pesanan/{order}/cairkan', [AdminController::class, 'releaseFunds'])->name('admin.orders.release');

    // Laporan
    Route::get('/reports', [ReportController::class, 'index'])->name('admin.reports.index');
    Route::patch('/reports/{report}/resolve', [ReportController::class, 'resolve'])->name('admin.reports.resolve');
});

// ================= AUTH (l    ogin/register bawaan Breeze) =================
require __DIR__.'/auth.php';
