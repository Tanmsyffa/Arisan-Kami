<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    ArisanGroupController,
    DashboardController,
    PaymentController,
    ProfileController,
    UserController
};
use App\Http\Controllers\Auth\AuthenticatedSessionController;

// Halaman awal
Route::get('/', function () {
    if (auth()->check()) {
        // Redirect ke dashboard sesuai peran
        if (auth()->user()->hasRole('admin')) {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('member.dashboard');
    }
    return view('index');
})->name('home');

// Autentikasi
Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
Route::post('/login', [AuthenticatedSessionController::class, 'store']);

// Autentikasi & hak akses
Route::middleware(['auth'])->group(function () {
    
    // Generic dashboard route that redirects based on role
    Route::get('/dashboard', function () {
        $user = auth()->user();
        
        // Debug: uncomment baris ini untuk debugging
        // dd($user->getRoleNames(), $user->hasRole('admin'), $user->hasRole('member'));
        
        if ($user->hasRole('admin')) {
            return redirect()->route('admin.dashboard');
        } elseif ($user->hasRole('member')) {
            return redirect()->route('member.dashboard');
        }
        
        // Fallback jika tidak ada role
        abort(403, 'Unauthorized access');
    })->name('dashboard');

    /**
     * --------------------------
     * ADMIN ROUTES
     * --------------------------
     */
    Route::prefix('admin')->middleware(['role:admin'])->name('admin.')->group(function () {
        // Dashboard admin
        Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');
        
        // Payment management
        Route::get('/payments', [PaymentController::class, 'index'])->name('payments.index');
        Route::get('/payments/{payment}', [PaymentController::class, 'show'])->name('payments.show');
        Route::get('/payments/{payment}/edit', [PaymentController::class, 'edit'])->name('payments.edit');
        Route::put('/payments/{payment}', [PaymentController::class, 'update'])->name('payments.update');
        Route::delete('/payments/{payment}', [PaymentController::class, 'destroy'])->name('payments.destroy');
        Route::post('/payments/{payment}/verify', [PaymentController::class, 'verify'])->name('payments.verify');

        // Groups management
        Route::resource('groups', ArisanGroupController::class)->except(['show']);

        // User management
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
        Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    });

    /**
     * --------------------------
     * MEMBER ROUTES
     * --------------------------
     */
    Route::prefix('member')->middleware(['role:member'])->name('member.')->group(function () {
        // Dashboard member
        Route::get('/dashboard', [DashboardController::class, 'member'])->name('dashboard');
        
        // Member specific routes
        Route::get('/groups', [ArisanGroupController::class, 'memberGroups'])->name('groups.index');
        Route::get('/payments', [PaymentController::class, 'memberPayments'])->name('payments.index');
    });
    
    // Shared routes (accessible by both admin and member)
    Route::prefix('arisan')->group(function () {
        Route::get('/groups/{group}', [ArisanGroupController::class, 'show'])->name('groups.show');
    });

    /**
     * --------------------------
     * BAWAAN LARAVEL BREEZE
     * --------------------------
     */
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Logout
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
});

/**
 * --------------------------
 * MIDTRANS CALLBACK (tanpa auth)
 * --------------------------
 */
Route::post('/payment/callback', [PaymentController::class, 'callback'])->name('payment.callback');

// Route login/register dari Breeze
require __DIR__.'/auth.php';