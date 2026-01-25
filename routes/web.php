<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ItemController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\BorrowingController;
use App\Http\Controllers\Admin\ReturnController;
use App\Http\Controllers\Staff\ApprovalController;
use App\Http\Controllers\Staff\ReturnController as StaffReturnController;
use App\Http\Controllers\User\BorrowController;

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::get('/', function () {
        return redirect()->route('login');
    });
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/dashboard', function () {
        $user = Auth::user();
        if ($user->role === 'admin') return view('dashboard');
        if ($user->role === 'petugas') return redirect()->route('staff.transactions.index');
        return redirect()->route('user.borrow.index');
    })->name('dashboard');

    // Admin Routes
    Route::prefix('admin')->name('admin.')->middleware('role:admin')->group(function () {
        // Borrowings Management
        Route::prefix('/borrowings')->name('borrowings.')->group(function () {
            Route::get('/export', [BorrowingController::class, 'export'])->name('export');
            Route::get('/', [BorrowingController::class, 'index'])->name('index');
            Route::get('/create', [BorrowingController::class, 'create'])->name('create');
            Route::post('/', [BorrowingController::class, 'store'])->name('store');
            Route::put('/{borrowing}', [BorrowingController::class, 'update'])->name('update');
            Route::patch('/{borrowing}/status', [BorrowingController::class, 'updateStatus'])->name('update_status');
            Route::patch('/{borrowing}/reschedule', [BorrowingController::class, 'reschedule'])->name('reschedule');
            Route::delete('/{borrowing}', [BorrowingController::class, 'destroy'])->name('delete');
        });

        // Items Management (Alat)
        Route::prefix('/items')->name('items.')->group(function () {
            Route::get('/export', [ItemController::class, 'export'])->name('export');
            Route::post('/import', [ItemController::class, 'import'])->name('import');
            Route::get('/template', [ItemController::class, 'downloadTemplate'])->name('template');
            Route::get('/', [ItemController::class, 'index'])->name('index');
            Route::get('/create', [ItemController::class, 'create'])->name('create');
            Route::post('/', [ItemController::class, 'store'])->name('store');
            Route::put('/{item}', [ItemController::class, 'update'])->name('update');
            Route::delete('/{item}', [ItemController::class, 'destroy'])->name('delete');
        });

        // Users Management
        Route::prefix('users')->name('users.')->group(function () {
            Route::get('/export', [UserController::class, 'export'])->name('export');
            Route::get('/', [UserController::class, 'index'])->name('index');
            Route::get('/create', [UserController::class, 'create'])->name('create');
            Route::post('/', [UserController::class, 'store'])->name('store');
            Route::put('/{user}', [UserController::class, 'update'])->name('update');
            Route::delete('/{user}', [UserController::class, 'destroy'])->name('delete');
        });

        // Categories Management
        Route::prefix('/categories')->name('categories.')->group(function () {
            Route::get('/export', [CategoryController::class, 'export'])->name('export');
            Route::post('/import', [CategoryController::class, 'import'])->name('import');
            Route::get('/template', [CategoryController::class, 'downloadTemplate'])->name('template');
            Route::get('/', [CategoryController::class, 'index'])->name('index');
            Route::get('/create', [CategoryController::class, 'create'])->name('create');
            Route::post('/', [CategoryController::class, 'store'])->name('store');
            Route::get('/{category}/edit', [CategoryController::class, 'edit'])->name('edit');
            Route::put('/{category}', [CategoryController::class, 'update'])->name('update');
            Route::delete('/{category}', [CategoryController::class, 'destroy'])->name('delete');
        });

        // Returns Management
        Route::prefix('/returns')->name('returns.')->group(function () {
            Route::get('/', [ReturnController::class, 'index'])->name('index');
            Route::get('/create', [ReturnController::class, 'create'])->name('create');
            Route::post('/', [ReturnController::class, 'store'])->name('store');
        });
    });

    // User Routes (Borrowing)
    Route::prefix('borrow')->name('user.borrow.')->middleware('role:peminjam')->group(function () {
        Route::get('/', [BorrowController::class, 'index'])->name('index');
        Route::get('/checkout', [BorrowController::class, 'checkout'])->name('checkout');
        Route::post('/checkout', [BorrowController::class, 'store'])->name('store');
        Route::get('/history', [BorrowController::class, 'history'])->name('history');
        Route::get('/invoice/{borrowing}', [BorrowController::class, 'invoice'])->name('invoice');
    });

    // Staff Routes (Transactions Unified)
    Route::prefix('staff')->name('staff.')->middleware('role:petugas')->group(function () {
        Route::prefix('/transactions')->name('transactions.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Staff\TransactionController::class, 'index'])->name('index');
            Route::patch('/{borrowing}/approve', [\App\Http\Controllers\Staff\TransactionController::class, 'approveRequest'])->name('approve-request');
            Route::patch('/{borrowing}/return', [\App\Http\Controllers\Staff\TransactionController::class, 'processReturn'])->name('process-return');
            Route::get('/{borrowing}/invoice', [\App\Http\Controllers\Staff\TransactionController::class, 'invoice'])->name('invoice');
        });
    });
});
