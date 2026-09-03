<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\NegotiationController;
use App\Http\Controllers\OrderMessageController;
use App\Http\Controllers\OrderFileController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ConversationController;
use App\Http\Controllers\PriceOfferController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ==============================================
// ROUTE PUBLIC (Tidak perlu login)
// ==============================================
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::post('/midtrans/notification', [PaymentController::class, 'notification'])
    ->name('midtrans.notification');

// [PERBAIKAN URUTAN] Halaman daftar jasa (INDEX) - PUBLIC
Route::get('/jasa', [ServiceController::class, 'index'])->name('services.index');

// Auth (Login, Register, dll) bawaan Breeze
require __DIR__.'/auth.php';

// ==============================================
// ROUTE USER (Harus login)
// ==============================================
Route::middleware(['auth'])->group(function () {

    // Dashboard user
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // --- NOTIFIKASI ---
    Route::get('/notifikasi', [NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifikasi/unread-count', [NotificationController::class, 'unreadCount'])->name('notifications.unread-count')->middleware('ensureApiRequest');
    Route::post('/notifikasi/read-all', [NotificationController::class, 'readAll'])->name('notifications.read-all');
    Route::get('/notifikasi/{notification}/open', [NotificationController::class, 'open'])->name('notifications.open');
    Route::post('/notifikasi/{notification}/read', [NotificationController::class, 'read'])->name('notifications.read');
    Route::post('/notifikasi/{notification}/ack', [NotificationController::class, 'ack'])->name('notifications.ack');
    Route::get('/notifikasi/pending', [NotificationController::class, 'pending'])->name('notifications.pending');

    // --- CHAT JASA ---
    Route::get('/messages', [ConversationController::class, 'index'])->name('conversations.index');
    Route::get('/seller/messages', [ConversationController::class, 'sellerIndex'])->name('conversations.seller-index');
    Route::post('/jasa/{service}/diskusi', [ConversationController::class, 'start'])->name('conversations.start');
    Route::get('/messages/{conversation}', [ConversationController::class, 'show'])->name('conversations.show');
    Route::post('/messages/{conversation}', [ConversationController::class, 'store'])->name('conversations.store');
    Route::post('/conversations/{conversation}/offers', [PriceOfferController::class, 'store'])->name('price-offers.store');
    Route::post('/offers/{priceOffer}/accept', [PriceOfferController::class, 'accept'])->name('price-offers.accept');
    Route::post('/offers/{priceOffer}/reject', [PriceOfferController::class, 'reject'])->name('price-offers.reject');


    // --- AJUAN JASA (DIPINDAHKAN KE SINI, SEBELUM WILDCARD) ---
    Route::get('/jasa/ajukan', [ServiceController::class, 'create'])->name('services.create');
    Route::post('/jasa', [ServiceController::class, 'store'])->name('services.store');

    // --- JASA SAYA (DIPINDAHKAN KE SINI, SEBELUM WILDCARD) ---
    Route::get('/jasa/saya', [ServiceController::class, 'myServices'])->name('services.my');
    Route::get('/jasa/{id}/edit', [ServiceController::class, 'edit'])->name('services.edit');
    Route::put('/jasa/{id}', [ServiceController::class, 'update'])->name('services.update');

    // --- PESANAN ---
    Route::get('/pesanan', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/pesanan/buat/{service}', [OrderController::class, 'create'])->name('orders.create');
    Route::post('/pesanan', [OrderController::class, 'store'])->name('orders.store');
    Route::get('/pesanan/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::get('/pesanan/{order}/conversation', [OrderController::class, 'conversation'])->name('orders.conversation');

    // --- NEGOSIASI ---
    Route::post('/negosiasi', [NegotiationController::class, 'store'])->name('negotiations.store');
    Route::post('/negosiasi/{negotiation}/terima', [NegotiationController::class, 'accept'])->name('negotiations.accept');

    // --- DISKUSI ---
    Route::post('/pesanan/{order}/messages', [OrderMessageController::class, 'store'])->name('order-messages.store');

    // --- FILE ---
    Route::post('/pesanan/{order}/files', [OrderFileController::class, 'store'])->name('order-files.store');
    Route::post('/order-files/{order}/approve', [OrderFileController::class, 'approve'])->name('order-files.approve');
    Route::post('/order-files/{order}/revision', [OrderFileController::class, 'requestRevision'])->name('order-files.revise');
    Route::post('/pesanan/{order}/start-work', [OrderFileController::class, 'startWork'])->name('orders.start-work');

    // --- PEMBAYARAN ---
    Route::post('/pesanan/{order}/payment', [PaymentController::class, 'store'])->name('payments.store');
    Route::get('/orders/{order}/payment', [PaymentController::class, 'showQris'])->name('orders.payment.show');
    Route::post('/orders/{order}/payment/qris', [PaymentController::class, 'createQris'])->name('orders.payment.qris');
    Route::get('/orders/{order}/payment/check', [PaymentController::class, 'checkStatus'])->name('orders.payment.check');

    // --- REVIEW ---
    Route::post('/jasa/{service}/review', [ReviewController::class, 'store'])->name('reviews.store');

    // --- LAPORAN ---
    Route::post('/laporan', [ReportController::class, 'store'])->name('reports.store');
    
    // --- REQUEST KATEGORI ---
    Route::get('/request-kategori', function() {
        return view('category-request.create');
    })->name('category-request.create');
    Route::post('/request-kategori', [App\Http\Controllers\CategoryRequestController::class, 'store'])->name('category-request.store');

    // --- DOMPET / PENCARIAN DANA ---
    Route::get('/dompet', [WalletController::class, 'index'])->name('wallet.index');
    Route::get('/dompet/tarik', [WalletController::class, 'withdrawCreate'])->name('wallet.withdraw.create');
    Route::post('/dompet/tarik', [WalletController::class, 'withdrawStore'])->name('wallet.withdraw.store')->middleware('throttle:3,5');
    Route::get('/dompet/tarik/{payoutRequest}/verifikasi', [WalletController::class, 'withdrawVerify'])->name('wallet.withdraw.verify');
    Route::get('/dompet/tarik/{payoutRequest}/konfirmasi', [WalletController::class, 'withdrawConfirm'])->name('wallet.withdraw.confirm');
    Route::delete('/dompet/tarik/{payoutRequest}/batal', [WalletController::class, 'withdrawCancel'])->name('wallet.withdraw.cancel');
    Route::post('/wallet/withdraw/{payoutRequest}/process', [WalletController::class, 'withdrawProcess'])->name('wallet.withdraw.process');

    // --- PROFIL ---
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ==============================================
// ROUTE ADMIN (Harus login & punya role admin)
// ==============================================
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {

    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    
    // Services Management (ALL services)
    Route::get('/services', [AdminController::class, 'servicesIndex'])->name('services.index');
    Route::get('/services/pending', [AdminController::class, 'pendingServices'])->name('services.pending');
    Route::get('/services/{service}/preview', [AdminController::class, 'previewService'])->name('services.preview');
    Route::post('/services/{service}/approve', [AdminController::class, 'approveService'])->name('services.approve');
    Route::post('/services/{service}/reject', [AdminController::class, 'rejectService'])->name('services.reject');

    Route::get('/escrow', [App\Http\Controllers\AdminEscrowController::class, 'index'])->name('escrow.index');
    Route::post('/escrow/{escrowTransaction}/confirm', [App\Http\Controllers\AdminEscrowController::class, 'confirm'])->name('escrow.confirm');
    Route::post('/escrow/{escrowTransaction}/reject', [App\Http\Controllers\AdminEscrowController::class, 'reject'])->name('escrow.reject');

    Route::post('/orders/{order}/release', [AdminController::class, 'releaseFunds'])->name('orders.release');
    Route::post('/orders/{order}/refund', [AdminController::class, 'refundOrder'])->name('orders.refund');

    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
Route::post('/reports/{report}/resolve', [ReportController::class, 'resolve'])->name('reports.resolve');

    // Pencairan dana (PAYOUT)
    Route::get('/payouts', [AdminController::class, 'payoutIndex'])->name('payouts.index');
    Route::post('/payouts/{payoutRequest}/process', [AdminController::class, 'payoutProcess'])->name('payout.process');
    Route::post('/payouts/{payoutRequest}/reject', [AdminController::class, 'payoutReject'])->name('payout.reject');
    Route::post('/payouts/{payoutRequest}/retry', [AdminController::class, 'payoutRetry'])->name('payout.retry');

    // Kelola Kategori
Route::get('/categories', [App\Http\Controllers\AdminCategoryController::class, 'index'])->name('categories.index');
Route::get('/categories/data', [App\Http\Controllers\AdminCategoryController::class, 'data'])->name('categories.data');
Route::get('/categories/create', [App\Http\Controllers\AdminCategoryController::class, 'create'])->name('categories.create');
Route::post('/categories', [App\Http\Controllers\AdminCategoryController::class, 'store'])->name('categories.store');
Route::get('/categories/{category}/edit', [App\Http\Controllers\AdminCategoryController::class, 'edit'])->name('categories.edit');
Route::put('/categories/{category}', [App\Http\Controllers\AdminCategoryController::class, 'update'])->name('categories.update');
Route::delete('/categories/{category}', [App\Http\Controllers\AdminCategoryController::class, 'destroy'])->name('categories.destroy');

// Kelola Subkategori
Route::get('/subcategories', [App\Http\Controllers\AdminSubcategoryController::class, 'index'])->name('subcategories.index');
Route::get('/subcategories/data', [App\Http\Controllers\AdminSubcategoryController::class, 'data'])->name('subcategories.data');
Route::get('/subcategories/create', [App\Http\Controllers\AdminSubcategoryController::class, 'create'])->name('subcategories.create');
Route::post('/subcategories', [App\Http\Controllers\AdminSubcategoryController::class, 'store'])->name('subcategories.store');
Route::get('/subcategories/{subcategory}/edit', [App\Http\Controllers\AdminSubcategoryController::class, 'edit'])->name('subcategories.edit');
Route::put('/subcategories/{subcategory}', [App\Http\Controllers\AdminSubcategoryController::class, 'update'])->name('subcategories.update');
Route::delete('/subcategories/{subcategory}', [App\Http\Controllers\AdminSubcategoryController::class, 'destroy'])->name('subcategories.destroy');
});

// ==============================================
// [PERBAIKAN UTAMA] ROUTE DETAIL JASA (WILDCARD)
// DITARUH DI PALING BAWAH AGAR TIDAK MEMAKAN ROUTE LAIN
// ==============================================
Route::get('/jasa/{service}', [ServiceController::class, 'show'])->name('services.show');
