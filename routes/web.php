<?php

use App\Http\Controllers\OrdersController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\UsersController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::redirect('/', '/login');
Route::middleware(['auth'])->group(function () {
    Route::prefix('orders')->name('orders.')->group(function () {
        Route::get('/', [OrdersController::class, 'index'])->name('index');
        Route::post('/', [OrdersController::class, 'export'])->name('export')->middleware(['can:export-order']);
        Route::post('/fetch', [OrdersController::class, 'fetch'])->name('fetch')->middleware(['can:refresh-tables']);
        Route::prefix('{order}')->group(function () {
            Route::get('/', [OrdersController::class, 'show'])->name('show');
            Route::middleware(['can:modify-order'])->group(function () {
                Route::get('/edit', [OrdersController::class, 'edit'])->name('edit');
                Route::patch('/', [OrdersController::class, 'update'])->name('update');
                Route::patch('/cancel', [OrdersController::class, 'cancel'])->name('cancel');
            });
        });
    });
    Route::prefix('products')->middleware(['can:view-products'])->name('products.')->group(function () {
        Route::get('/', [ProductsController::class, 'index'])->name('index');
        Route::post('/', [ProductsController::class, 'export'])->name('export');
        Route::post('/fetch', [ProductsController::class, 'fetch'])->name('fetch')->middleware(['can:refresh-tables']);
        Route::prefix('{product}')->group(function () {
            Route::get('/', [ProductsController::class, 'show'])->name('show');
            Route::get('/edit', [ProductsController::class, 'edit'])->name('edit');
            Route::patch('/', [ProductsController::class, 'update'])->name('update');
            Route::delete('/delete', [ProductsController::class, 'destroy'])->name('destroy');
        });
    });
    Route::prefix('users')->middleware(['can:add-users'])->name('users.')->group(function () {
        Route::get('/', [UsersController::class, 'index'])->name('index');
        Route::get('/create', [UsersController::class, 'create'])->name('create');
        Route::post('/', [UsersController::class, 'store'])->name('store');
        Route::prefix('{user}')->group(function () {
            Route::get('/edit', [UsersController::class, 'edit'])->name('edit');
            Route::patch('/', [UsersController::class, 'update'])->name('update');
        });
    });
});

require __DIR__.'/auth.php';
