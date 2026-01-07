<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    ProfileController,
    ProductController,
    CartController,
    CheckoutController,
    DashboardController,
    OrderController,
    UserController,
    InventoryLogController,
    PaymentController,
    PaymentMethodController
};

Route::get('/', function () {
    return view('auth.login');
});

Route::middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    /*
    |--------------------------------------------------------------------------
    | Users
    |   - Admin + Superadmin: create/store only
    |   - Superadmin: all other user management
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:admin|superadmin')->group(function () {
        Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
    });

    Route::middleware('role:superadmin')->group(function () {
        Route::resource('users', UserController::class)->except(['create', 'store']);
    });

    // Products
    Route::get('/products', [ProductController::class, 'index'])
        ->middleware('permission:products.index')
        ->name('products.index');

    Route::get('/products/create', [ProductController::class, 'create'])
        ->middleware('permission:products.create')
        ->name('products.create');

    Route::post('/products', [ProductController::class, 'store'])
        ->middleware('permission:products.create')
        ->name('products.store');

    Route::get('/products/{product}/edit', [ProductController::class, 'edit'])
        ->middleware('permission:products.edit')
        ->name('products.edit');

    Route::put('/products/{product}', [ProductController::class, 'update'])
        ->middleware('permission:products.edit')
        ->name('products.update');

    Route::delete('/products/{product}', [ProductController::class, 'destroy'])
        ->middleware('permission:products.delete')
        ->name('products.destroy');

    // Cart
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add/{product}', [CartController::class, 'add'])->name('cart.add');
    Route::post('/cart/update', [CartController::class, 'updateAll'])->name('cart.updateAll');
    Route::post('/cart/update/{product}', [CartController::class, 'update'])->name('cart.update');
    Route::post('/cart/remove/{product}', [CartController::class, 'remove'])->name('cart.remove');

    // Checkout
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');

    // Orders
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::put('/orders/{order}', [OrderController::class, 'update'])->name('orders.update');
    Route::get('/orders/{order}/receipt', [OrderController::class, 'receipt'])->name('orders.receipt');
    Route::get('/payment-history', [OrderController::class, 'paymentHistory'])->name('payments.history')->middleware('auth');

    // Inventory
    Route::get('/inventory', [InventoryLogController::class, 'index'])->name('inventory.index');
    Route::post('/inventory/{product}/adjust', [InventoryLogController::class, 'adjust'])->name('inventory.adjust');

    // Payments
    Route::get('/payments', [PaymentController::class, 'index'])->name('payments.index');
    Route::post('/payments/{order}', [PaymentController::class, 'store'])->name('payments.store');

    // Payment Methods
    Route::get('/payment-methods', [PaymentMethodController::class, 'index'])->name('methods.index');
    Route::post('/payment-methods', [PaymentMethodController::class, 'store'])->name('methods.store');
    Route::patch('/payment-methods/{method}/toggle', [PaymentMethodController::class, 'toggle'])->name('methods.toggle');
    Route::delete('/payment-methods/{method}', [PaymentMethodController::class, 'destroy'])->name('methods.destroy');
});

require __DIR__ . '/auth.php';
