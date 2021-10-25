<?php

use App\Http\Controllers\OrdersController;
use App\Http\Controllers\ProductsController;
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
        Route::post('/', [OrdersController::class, 'export'])->name('export');
        Route::prefix('{order}')->group(function () {
            Route::get('/', [OrdersController::class, 'show'])->name('show');
            Route::get('/edit', [OrdersController::class, 'edit'])->name('edit');
            Route::patch('/', [OrdersController::class, 'update'])->name('update');
            Route::delete('/delete', [OrdersController::class, 'destroy'])->name('destroy');
        });
    });
    Route::prefix('products')->name('products.')->group(function () {
        Route::get('/', [ProductsController::class, 'index'])->name('index');
        Route::post('/', [ProductsController::class, 'export'])->name('export');
        Route::prefix('{product}')->group(function () {
            Route::get('/', [ProductsController::class, 'show'])->name('show');
            Route::get('/edit', [ProductsController::class, 'edit'])->name('edit');
            Route::patch('/', [ProductsController::class, 'update'])->name('update');
            Route::delete('/delete', [ProductsController::class, 'destroy'])->name('destroy');
        });
    });
});

require __DIR__.'/auth.php';
