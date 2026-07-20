<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\VipController as AdminVipController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\DepositController as AdminDepositController;
use App\Http\Controllers\Admin\WithdrawalController as AdminWithdrawalController;
use App\Http\Controllers\Admin\PaymentMethodController as AdminPaymentMethodController;
use App\Http\Controllers\Admin\ExchangeRateController as AdminExchangeRateController;
use App\Http\Controllers\Admin\NotificationController as AdminNotificationController;
use App\Http\Controllers\Admin\ReferralController as AdminReferralController;
use App\Http\Controllers\Client\AuthController as ClientAuthController;
use App\Http\Controllers\Client\DashboardController as ClientDashboardController;
use App\Http\Controllers\Client\VipController as ClientVipController;
use App\Http\Controllers\Client\DepositController as ClientDepositController;
use App\Http\Controllers\Client\WithdrawalController as ClientWithdrawalController;
use App\Http\Controllers\Client\CheckInController as ClientCheckInController;
use App\Http\Controllers\Client\ReferralController as ClientReferralController;
use App\Http\Controllers\Client\NotificationController as ClientNotificationController;
use App\Http\Controllers\Client\ProfileController as ClientProfileController;
use App\Http\Controllers\Client\EarningsSimulatorController as ClientEarningsSimulatorController;

// Redirect root to login
Route::get('/', function () {
    return redirect('/login');
});

// Client Authentication Routes
Route::get('/login', [ClientAuthController::class, 'showLogin'])->name('client.login');
Route::post('/login', [ClientAuthController::class, 'login']);
Route::get('/register', [ClientAuthController::class, 'showRegister'])->name('client.register');
Route::post('/register', [ClientAuthController::class, 'register']);

// Client Routes (Protected)
Route::middleware(['auth', 'isClient'])->prefix('client')->name('client.')->group(function () {
    Route::post('/logout', [ClientAuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [ClientDashboardController::class, 'index'])->name('dashboard');
    
    // VIPs
    Route::get('/vips', [ClientVipController::class, 'plans'])->name('vips.index');
    Route::get('/vips/mes', [ClientVipController::class, 'mine'])->name('vips.mine');
    Route::post('/vips/{vip}/buy', [ClientVipController::class, 'buy'])->name('vips.buy');
    
    // Deposits
    Route::get('/deposits', [ClientDepositController::class, 'index'])->name('deposits.index');
    Route::post('/deposits', [ClientDepositController::class, 'store'])->name('deposits.store');
    
    // Withdrawals
    Route::get('/withdrawals', [ClientWithdrawalController::class, 'index'])->name('withdrawals.index');
    Route::post('/withdrawals', [ClientWithdrawalController::class, 'store'])->name('withdrawals.store');
    
    // Check-in
    Route::post('/checkin', [ClientCheckInController::class, 'store'])->name('checkin.store');
    
    // Referrals
    Route::get('/referrals', [ClientReferralController::class, 'index'])->name('referrals.index');
    
    // Notifications
    Route::get('/notifications', [ClientNotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{notification}/mark-read', [ClientNotificationController::class, 'markRead'])->name('notifications.markRead');
    
    // Profile
    Route::get('/profile', [ClientProfileController::class, 'index'])->name('profile.index');
    Route::post('/profile', [ClientProfileController::class, 'update'])->name('profile.update');
    
    // Earnings Simulator
    Route::get('/earnings-simulator', [ClientEarningsSimulatorController::class, 'index'])->name('earnings-simulator');
});

// Admin Authentication Routes
Route::get('/admin/login', [AdminAuthController::class, 'showLogin'])->name('admin.login');
Route::post('/admin/login', [AdminAuthController::class, 'login']);
Route::post('/admin/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

// Admin Routes (Protected)
Route::middleware(['auth', 'isAdmin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    
    // VIPs
    Route::get('/vips', [AdminVipController::class, 'index'])->name('vips.index');
    Route::get('/vips/create', [AdminVipController::class, 'create'])->name('vips.create');
    Route::post('/vips', [AdminVipController::class, 'store'])->name('vips.store');
    Route::get('/vips/{vip}/edit', [AdminVipController::class, 'edit'])->name('vips.edit');
    Route::put('/vips/{vip}', [AdminVipController::class, 'update'])->name('vips.update');
    Route::delete('/vips/{vip}', [AdminVipController::class, 'destroy'])->name('vips.destroy');
    
    // Users
    Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
    Route::get('/users/{user}', [AdminUserController::class, 'show'])->name('users.show');
    Route::post('/users/{user}/status', [AdminUserController::class, 'updateStatus'])->name('users.updateStatus');
    Route::post('/users/{user}/balance', [AdminUserController::class, 'updateBalance'])->name('users.updateBalance');
    Route::delete('/users/{user}', [AdminUserController::class, 'destroy'])->name('users.destroy');
    
    // Deposits
    Route::get('/deposits', [AdminDepositController::class, 'index'])->name('deposits.index');
    Route::post('/deposits/{deposit}/approve', [AdminDepositController::class, 'approve'])->name('deposits.approve');
    Route::post('/deposits/{deposit}/reject', [AdminDepositController::class, 'reject'])->name('deposits.reject');
    Route::delete('/deposits/{deposit}', [AdminDepositController::class, 'destroy'])->name('deposits.destroy');
    
    // Withdrawals
    Route::get('/withdrawals', [AdminWithdrawalController::class, 'index'])->name('withdrawals.index');
    Route::post('/withdrawals/{withdrawal}/approve', [AdminWithdrawalController::class, 'approve'])->name('withdrawals.approve');
    Route::post('/withdrawals/{withdrawal}/reject', [AdminWithdrawalController::class, 'reject'])->name('withdrawals.reject');
    Route::delete('/withdrawals/{withdrawal}', [AdminWithdrawalController::class, 'destroy'])->name('withdrawals.destroy');
    
    // Payment Methods
    Route::get('/payment-methods', [AdminPaymentMethodController::class, 'index'])->name('payment-methods.index');
    Route::get('/payment-methods/create', [AdminPaymentMethodController::class, 'create'])->name('payment-methods.create');
    Route::post('/payment-methods', [AdminPaymentMethodController::class, 'store'])->name('payment-methods.store');
    Route::get('/payment-methods/{paymentMethod}/edit', [AdminPaymentMethodController::class, 'edit'])->name('payment-methods.edit');
    Route::put('/payment-methods/{paymentMethod}', [AdminPaymentMethodController::class, 'update'])->name('payment-methods.update');
    Route::delete('/payment-methods/{paymentMethod}', [AdminPaymentMethodController::class, 'destroy'])->name('payment-methods.destroy');
    
    // Exchange Rates
    Route::get('/exchange-rates', [AdminExchangeRateController::class, 'index'])->name('exchange-rates.index');
    Route::post('/exchange-rates', [AdminExchangeRateController::class, 'store'])->name('exchange-rates.store');
    Route::put('/exchange-rates/{exchangeRate}', [AdminExchangeRateController::class, 'update'])->name('exchange-rates.update');
    Route::delete('/exchange-rates/{exchangeRate}', [AdminExchangeRateController::class, 'destroy'])->name('exchange-rates.destroy');
    
    // Notifications
    Route::get('/notifications', [AdminNotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/send', [AdminNotificationController::class, 'send'])->name('notifications.send');
    Route::delete('/notifications/{notification}', [AdminNotificationController::class, 'destroy'])->name('notifications.destroy');
    
    // Referrals
    Route::get('/referrals', [AdminReferralController::class, 'index'])->name('referrals.index');
    Route::delete('/referrals/{referral}', [AdminReferralController::class, 'destroy'])->name('referrals.destroy');
});