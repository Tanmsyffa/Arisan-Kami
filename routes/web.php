<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    ArisanGroupController,
    DashboardController,
    PaymentController,
    ProfileController
};
use App\Http\Controllers\Auth\AuthenticatedSessionController;

// Tambahkan di atas route yang ada
Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
Route::post('/login', [AuthenticatedSessionController::class, 'store']);

// Halaman awal
Route::get('/', function () {
    if (auth()->check()) {
        // Redirect ke dashboard sesuai peran
        if (auth()->user()->hasRole('admin')) {
            return redirect()->route('groups.index'); // ke dashboard admin
        }
        return redirect()->route('dashboard'); // ke dashboard member
    }
    return view('index'); // tampilkan landing page jika belum login
})->name('home');


// Autentikasi & hak akses
Route::middleware(['auth'])->group(function () {

    /**
     * --------------------------
     * ADMIN (akses CRUD grup)
     * --------------------------
     */
    Route::middleware('role:admin')->group(function () {
        Route::resource('/admin/groups', ArisanGroupController::class)->names([
            'index'   => 'groups.index',
            'create'  => 'groups.create',
            'store'   => 'groups.store',
            'edit'    => 'groups.edit',
            'update'  => 'groups.update',
            'destroy' => 'groups.destroy',
        ]);
    });

    /**
     * --------------------------
     * MEMBER + ADMIN (dashboard & bayar)
     * --------------------------
     */
    Route::middleware('role:member,admin')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        
        // Route pembayaran - ubah ke GET untuk halaman pembayaran
        Route::get('/pay/{group}', [PaymentController::class, 'pay'])->name('pay');
        
        // Route callback pages (redirect dari Midtrans)
        Route::get('/payment/finish', [PaymentController::class, 'finish'])->name('payment.finish');
        Route::get('/payment/unfinish', [PaymentController::class, 'unfinish'])->name('payment.unfinish');
        Route::get('/payment/error', [PaymentController::class, 'error'])->name('payment.error');
    });

    // routes/web.php

Route::prefix('arisan')->group(function () {
    Route::get('/groups/{group}', [ArisanGroupController::class, 'show'])
         ->name('groups.show');
});

    /**
     * --------------------------
     * BAWAAN LARAVEL BREEZE
     * --------------------------
     */
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/**
 * --------------------------
 * MIDTRANS CALLBACK (tanpa auth)
 * --------------------------
 */
// Route untuk notification callback dari Midtrans (POST)
Route::post('/payment/callback', [PaymentController::class, 'callback'])->name('payment.callback');

// Route login/register dari Breeze
require __DIR__.'/auth.php';