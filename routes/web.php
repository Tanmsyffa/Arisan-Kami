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

// Halaman awal / Landing Page
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

// Autentikasi Routes
Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
Route::post('/login', [AuthenticatedSessionController::class, 'store']);

// Routes yang memerlukan autentikasi
Route::middleware(['auth'])->group(function () {
    
    // Generic dashboard route yang redirect berdasarkan role
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Admin routes
    Route::prefix('admin')->middleware(['role:admin'])->name('admin.')->group(function () {
        
        // Dashboard Admin
        Route::get('/dashboard', [DashboardController::class, 'admin'])->name('dashboard');
        
        // Arisan Groups Management
        Route::get('/groups', [ArisanGroupController::class, 'index'])->name('groups.index');
        Route::get('/groups/create', [ArisanGroupController::class, 'create'])->name('groups.create');
        Route::post('/groups', [ArisanGroupController::class, 'store'])->name('groups.store');
        Route::get('/groups/{group}/edit', [ArisanGroupController::class, 'edit'])->name('groups.edit');
        Route::put('/groups/{group}', [ArisanGroupController::class, 'update'])->name('groups.update');
        Route::delete('/groups/{group}', [ArisanGroupController::class, 'destroy'])->name('groups.destroy');
        
        // Payment Management
        Route::get('/payments', [PaymentController::class, 'index'])->name('payments.index');
        Route::get('/payments/create', [PaymentController::class, 'create'])->name('payments.create');
        Route::post('/payments', [PaymentController::class, 'store'])->name('payments.store');
        Route::get('/payments/{payment}', [PaymentController::class, 'show'])->name('payments.show');
        Route::get('/payments/{payment}/edit', [PaymentController::class, 'edit'])->name('payments.edit');
        Route::put('/payments/{payment}', [PaymentController::class, 'update'])->name('payments.update');
        Route::delete('/payments/{payment}', [PaymentController::class, 'destroy'])->name('payments.destroy');
        
        // Payment Actions
        Route::post('/payments/{payment}/verify', [PaymentController::class, 'verify'])->name('payments.verify');
        Route::post('/payments/{payment}/reject', [PaymentController::class, 'reject'])->name('payments.reject');
        Route::post('/payments/{payment}/approve', [PaymentController::class, 'approve'])->name('payments.approve');
        
        // User Management
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
        Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');
        Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
        
        // User Actions
        Route::post('/users/{user}/activate', [UserController::class, 'activate'])->name('users.activate');
        Route::post('/users/{user}/deactivate', [UserController::class, 'deactivate'])->name('users.deactivate');
        Route::post('/users/{user}/reset-password', [UserController::class, 'resetPassword'])->name('users.reset-password');
        
        // Reports & Analytics
        Route::get('/reports', [DashboardController::class, 'reports'])->name('reports.index');
        Route::get('/reports/groups', [DashboardController::class, 'groupReports'])->name('reports.groups');
        Route::get('/reports/payments', [DashboardController::class, 'paymentReports'])->name('reports.payments');
        Route::get('/reports/users', [DashboardController::class, 'userReports'])->name('reports.users');
        
        // Export Routes
        Route::get('/export/groups', [ArisanGroupController::class, 'export'])->name('export.groups');
        Route::get('/export/payments', [PaymentController::class, 'export'])->name('export.payments');
        Route::get('/export/users', [UserController::class, 'export'])->name('export.users');
        
        // Settings
        Route::get('/settings', [DashboardController::class, 'settings'])->name('settings.index');
        Route::put('/settings', [DashboardController::class, 'updateSettings'])->name('settings.update');
    });

    // Member routes
    Route::prefix('member')->middleware(['role:member'])->name('member.')->group(function () {
        
        // Dashboard Member
        Route::get('/dashboard', [DashboardController::class, 'member'])->name('dashboard');
        
        // Member Groups
        Route::get('/groups', [ArisanGroupController::class, 'memberGroups'])->name('groups.index');
        Route::get('/groups/available', [ArisanGroupController::class, 'availableGroups'])->name('groups.available');
        Route::post('/groups/{group}/join', [ArisanGroupController::class, 'joinGroup'])->name('groups.join');
        Route::post('/groups/{group}/leave', [ArisanGroupController::class, 'leaveGroup'])->name('groups.leave');
        
        // Member Payments
        Route::get('/payments', [PaymentController::class, 'memberPayments'])->name('payments.index');
        Route::get('/payments/create', [PaymentController::class, 'createPayment'])->name('payments.create');
        Route::post('/payments', [PaymentController::class, 'storePayment'])->name('payments.store');
        Route::get('/payments/{payment}', [PaymentController::class, 'showPayment'])->name('payments.show');
        
        // Payment History
        Route::get('/payments/history', [PaymentController::class, 'paymentHistory'])->name('payments.history');
        Route::get('/payments/{payment}/receipt', [PaymentController::class, 'downloadReceipt'])->name('payments.receipt');
        
        // Member Profile
        Route::get('/profile', [ProfileController::class, 'memberProfile'])->name('profile.show');
        Route::get('/profile/edit', [ProfileController::class, 'editMemberProfile'])->name('profile.edit');
        Route::put('/profile', [ProfileController::class, 'updateMemberProfile'])->name('profile.update');
        
        // Notifications
        Route::get('/notifications', [DashboardController::class, 'notifications'])->name('notifications.index');
        Route::post('/notifications/{notification}/read', [DashboardController::class, 'markAsRead'])->name('notifications.read');
        Route::post('/notifications/read-all', [DashboardController::class, 'markAllAsRead'])->name('notifications.read-all');
    });
    
    // Shared routes
    Route::prefix('arisan')->group(function () {
        // Group Details
        Route::get('/groups/{group}', [ArisanGroupController::class, 'show'])->name('groups.show');
        Route::get('/groups/{group}/members', [ArisanGroupController::class, 'showMembers'])->name('groups.members');
        Route::get('/groups/{group}/schedule', [ArisanGroupController::class, 'showSchedule'])->name('groups.schedule');
        Route::get('/groups/{group}/payments', [ArisanGroupController::class, 'showPayments'])->name('groups.payments');
        
        // Group Actions
        Route::post('/groups/{group}/draw', [ArisanGroupController::class, 'drawWinner'])->name('groups.draw');
        Route::post('/groups/{group}/start', [ArisanGroupController::class, 'startGroup'])->name('groups.start');
        Route::post('/groups/{group}/complete', [ArisanGroupController::class, 'completeGroup'])->name('groups.complete');
    });

    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Change Password
    Route::get('/change-password', [ProfileController::class, 'changePassword'])->name('profile.change-password');
    Route::put('/change-password', [ProfileController::class, 'updatePassword'])->name('profile.update-password');
    
    // Logout
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
});

Route::prefix('api')->middleware(['auth'])->group(function () {
    // Get data for datatables
    Route::get('/groups/data', [ArisanGroupController::class, 'getData'])->name('api.groups.data');
    Route::get('/payments/data', [PaymentController::class, 'getData'])->name('api.payments.data');
    Route::get('/users/data', [UserController::class, 'getData'])->name('api.users.data');
    
    // Real-time notifications
    Route::get('/notifications/unread', [DashboardController::class, 'getUnreadNotifications'])->name('api.notifications.unread');
    
    // Dashboard stats
    Route::get('/dashboard/stats', [DashboardController::class, 'getStats'])->name('api.dashboard.stats');
});

// Midtrans Payment Callback
Route::post('/payment/callback', [PaymentController::class, 'callback'])->name('payment.callback');

// Midtrans Notification Handler
Route::post('/payment/notification', [PaymentController::class, 'handleNotification'])->name('payment.notification');


// Public Pages
Route::get('/about', function () {
    return view('pages.about');
})->name('about');

Route::get('/contact', function () {
    return view('pages.contact');
})->name('contact');

Route::post('/contact', [DashboardController::class, 'submitContact'])->name('contact.submit');

// Terms and Privacy
Route::get('/terms', function () {
    return view('pages.terms');
})->name('terms');

Route::get('/privacy', function () {
    return view('pages.privacy');
})->name('privacy');

// Fallback untuk route yang tidak ditemukan
Route::fallback(function () {
    return response()->view('errors.404', [], 404);
});

// Include authentication routes dari Laravel Breeze
require __DIR__.'/auth.php';