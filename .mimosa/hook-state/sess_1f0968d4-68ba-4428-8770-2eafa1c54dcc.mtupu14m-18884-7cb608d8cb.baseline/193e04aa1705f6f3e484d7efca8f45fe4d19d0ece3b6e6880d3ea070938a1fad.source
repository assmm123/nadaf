<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ReportExportController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SearchController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/lang/{locale}', function (string $locale) {
    if (in_array($locale, ['ar', 'en'])) {
        session(['locale' => $locale]);
    }

    return back();
})->name('lang.switch');

Route::get('/currency/{code}', function (string $code) {
    if (in_array($code, ['usd', 'syp'])) {
        session(['currency' => $code]);
    }

    return back();
})->name('currency.switch');

Route::get('/c/{slug}', [CategoryController::class, 'show'])->name('category.show');
Route::get('/p/{slug}', [ProductController::class, 'show'])->name('product.show');
Route::get('/search', [SearchController::class, 'index'])->name('search');
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::get('/page/{slug}', [PageController::class, 'show'])->name('page.show');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.attempt');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.store');
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');
    Route::get('/checkout/success/{code}', [CheckoutController::class, 'success'])->name('checkout.success');

    Route::get('/account', [AccountController::class, 'profile'])->name('account.profile');
    Route::post('/account', [AccountController::class, 'updateProfile'])->name('account.profile.update');
    Route::get('/account/orders', [AccountController::class, 'orders'])->name('account.orders');
    Route::get('/account/orders/{code}', [AccountController::class, 'order'])->name('account.order');
    Route::get('/account/orders/{code}/invoice', [AccountController::class, 'invoice'])->name('account.invoice');
});

// فاتورة الطلب — للأدمن (طباعة)
Route::get('/admin/orders/{order}/invoice', [InvoiceController::class, 'show'])
    ->middleware(['auth', 'admin'])
    ->name('admin.invoice');

// تصدير تقارير المبيعات والأرباح — للأدمن
Route::get('/admin/reports/export', [ReportExportController::class, 'export'])
    ->middleware(['auth', 'admin'])
    ->name('admin.reports.export');
