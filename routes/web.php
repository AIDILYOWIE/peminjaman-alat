<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Admin\CategoryController;

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
        if ($user->role === 'petugas') return redirect()->route('staff.approvals.index');
        return redirect()->route('user.borrow.index');
    })->name('dashboard');

    // Admin Routes
    Route::prefix('admin')->name('admin.')->middleware('role:admin')->group(function () {
        // Items Management
        Route::prefix('/items')->name('items.')->group(function () {
            Route::get('/', function () {
                return view('admin.items.index');
            })->name('index');
            Route::get('/create', function () {
                return view('admin.items.create');
            })->name('create');
            Route::put('/{id}/edit', function () {
                return ('edit');
            })->name('edit');
            Route::delete('/{id}', function () {
                return ('delete');
            })->name('delete');
        });

        // Users Management
        Route::prefix('/users')->name('users.')->group(function () {
            Route::get('/', function () {
                return view('admin.users.index');
            })->name('index');
            Route::get('/create', function () {
                return view('admin.users.create');
            })->name('create');
            Route::put('/{id}/edit', function () {
                return ('edit');
            })->name('edit');
            Route::delete('/{id}', function () {
                return ('delete');
            })->name('delete');
        });

        // Categories Management
        Route::prefix('/categories')->name('categories.')->group(function () {
            Route::get('/', [CategoryController::class, 'index'])->name('index');
            Route::get('/create', [CategoryController::class, 'create'])->name('create');
            Route::post('/', [CategoryController::class, 'store'])->name('store');
            Route::get('/{category}/edit', [CategoryController::class, 'edit'])->name('edit');
            Route::put('/{category}', [CategoryController::class, 'update'])->name('update');
            Route::delete('/{category}', [CategoryController::class, 'destroy'])->name('delete');
        });

        // Borrowings Management
        Route::prefix('/borrowings')->name('borrowings.')->group(function () {
            Route::get('/', function () {
                return view('admin.borrowings.index');
            })->name('index');
            Route::get('/create', function () {
                return view('admin.borrowings.create');
            })->name('create');
        });

        // Returns Management
        Route::prefix('/returns')->name('returns.')->group(function () {
            Route::get('/', function () {
                return view('admin.returns.index');
            })->name('index');
        });
    });

    // User Routes (Borrowing)
    Route::prefix('borrow')->name('user.borrow.')->middleware('role:peminjam')->group(function () {
        Route::get('/', function () {
            return view('user.borrow.index');
        })->name('index');
    });

    // Staff Routes (Approvals & Returns)
    Route::prefix('staff')->name('staff.')->middleware('role:petugas')->group(function () {
        Route::get('/approvals', function () {
            return view('staff.approvals.index');
        })->name('approvals.index');

        Route::get('/returns', function () {
            return view('staff.returns.index');
        })->name('returns.index');
    });
});
