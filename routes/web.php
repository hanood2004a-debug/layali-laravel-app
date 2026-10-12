<?php

use App\Http\Controllers\Admin\AboutController;
use App\Http\Controllers\Admin\AccountingController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ExpenseController;
use App\Http\Controllers\Admin\ItemController;
use App\Http\Controllers\Admin\LoyaltyController;
use App\Http\Controllers\Admin\ReviewController as AdminReviewController;
use App\Http\Controllers\LoyaltyCardController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\RestaurantController;
use Illuminate\Support\Facades\Route;

Route::get('/', [RestaurantController::class, 'index'])->name('home');
Route::post('/order', [OrderController::class, 'store'])->name('order.store');
Route::get('/loyalty-card', [LoyaltyCardController::class, 'show'])->name('loyalty.card');
Route::post('/loyalty-card', [LoyaltyCardController::class, 'lookup'])->middleware('throttle:10,1')->name('loyalty.lookup');
Route::post('/reviews', [ReviewController::class, 'store'])->middleware('throttle:6,1')->name('reviews.store');

// دخول الإدارة فقط (لا يوجد حسابات للزبائن)
Route::get('/login', [AuthController::class, 'show'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:10,1')->name('login.submit');
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/orders/poll', [DashboardController::class, 'poll'])->name('orders.poll');
    Route::get('/orders', [DashboardController::class, 'orders'])->name('orders');
    Route::patch('/orders/{order}', [DashboardController::class, 'updateOrder'])->name('orders.update');
    Route::patch('/orders/{order}/payment', [DashboardController::class, 'updatePayment'])->name('orders.payment');
    Route::post('/orders/{order}/redeem', [LoyaltyController::class, 'redeemOrder'])->name('orders.redeem');
    Route::get('/loyalty', [LoyaltyController::class, 'settings'])->name('loyalty');
    Route::post('/loyalty', [LoyaltyController::class, 'saveSettings'])->name('loyalty.save');
    Route::get('/customers', [LoyaltyController::class, 'customers'])->name('customers');
    Route::get('/customers/{customer}', [LoyaltyController::class, 'show'])->name('customers.show');
    Route::post('/customers/{customer}/adjust', [LoyaltyController::class, 'adjust'])->name('customers.adjust');
    Route::get('/accounting', [AccountingController::class, 'index'])->name('accounting');
    Route::get('/expenses', [ExpenseController::class, 'index'])->name('expenses');
    Route::post('/expenses', [ExpenseController::class, 'store'])->name('expenses.store');
    Route::delete('/expenses/{expense}', [ExpenseController::class, 'destroy'])->name('expenses.destroy');
    Route::resource('items', ItemController::class)->except('show');
    Route::get('/reviews', [AdminReviewController::class, 'index'])->name('reviews');
    Route::patch('/reviews/{review}/approve', [AdminReviewController::class, 'approve'])->name('reviews.approve');
    Route::delete('/reviews/{review}', [AdminReviewController::class, 'destroy'])->name('reviews.destroy');
    Route::get('/categories', [CategoryController::class, 'index'])->name('categories');
    Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');
    Route::get('/about', [AboutController::class, 'edit'])->name('about');
    Route::post('/about', [AboutController::class, 'update'])->name('about.update');
});
