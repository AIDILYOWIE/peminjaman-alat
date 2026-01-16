<?php

use Illuminate\Support\Facades\Route;

// Authentication Routes
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::post('/login', function () {
    // TODO: Implement authentication logic
    return redirect()->route('dashboard');
})->name('login.post');

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

// Admin Routes
Route::prefix('admin')->name('admin.')->group(function () {
    Route::prefix('/items')->name('items.')->group(function () {
        Route::get('/', function () {
            return view('admin.items.index');
        })->name('index');

        Route::get('/create', function () {
            // Placeholder for create item
            return view('admin.items.create');
        })->name('create');

        Route::put('/{id}/edit', function () {
            return ('edit');
        })->name('edit');

        Route::put('/{id}', function () {
            return ('delete');
        })->name('delete');
    });

    Route::prefix('/users')->name('users.')->group(function () {
        Route::get('/', function () {
            return view('admin.users.index');
        })->name('index');

        Route::get('/create', function () {
            // Placeholder for create item
            return view('admin.users.create');
        })->name('create');

        Route::put('/{id}/edit', function () {
            return ('edit');
        })->name('edit');

        Route::put('/{id}', function () {
            return ('delete');
        })->name('delete');
    });

    Route::prefix('/categories')->name('categories.')->group(function () {
        Route::get('/', function () {
            return view('admin.categories.index');
        })->name('index');

        Route::get('/create', function () {
            // Placeholder for create item
            return view('admin.categories.create');
        })->name('create');

        Route::put('/{id}/edit', function () {
            return ('edit');
        })->name('edit');

        Route::put('/{id}', function () {
            return ('delete');
        })->name('delete');
    });

    Route::prefix('/borrowings')->name('borrowings.')->group(function () {
        Route::get('/', function () {
            return view('admin.borrowings.index');
        })->name('index');

        Route::get('/create', function () {
            return view('admin.borrowings.create');
        })->name('create');
    });
    Route::get('/create', function () {
        return view('admin.borrowings.create');
    })->name('create');
});

// User Routes (Borrowing)
Route::prefix('borrow')->name('user.borrow.')->group(function () {
    Route::get('/', function () {
        // Placeholder for borrowing catalog
        return view('user.borrow.index');
    })->name('index');
});

// Staff Routes (Approvals & Returns)
Route::prefix('staff')->name('staff.')->group(function () {
    Route::get('/approvals', function () {
        // Placeholder for approvals
        return view('staff.approvals.index');
    })->name('approvals.index');

    Route::get('/returns', function () {
        // Placeholder for returns
        return view('staff.returns.index');
    })->name('returns.index');
});
