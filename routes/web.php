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
        Route::post('/', [OrdersController::class, 'export'])->name('export');
        Route::post('/fetch', [OrdersController::class, 'fetch'])->name('fetch');
        Route::patch('/{order}/cancel', [OrdersController::class, 'cancel'])->name('cancel');
    });

    Route::resource('orders', OrdersController::class)->except([
        'create', 'store', 'destroy'
    ]);

    Route::prefix('products')->name('products.')->group(function () {
        Route::post('/', [ProductsController::class, 'export'])->name('export');
        Route::post('/fetch', [ProductsController::class, 'fetch'])->name('fetch');
    });

    Route::resource('products', ProductsController::class)->except([
        'create', 'store'
    ]);

    Route::resource('users', UsersController::class)->except([
        'show', 'destroy'
    ]);
});

require __DIR__ . '/auth.php';
