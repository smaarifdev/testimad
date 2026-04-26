<?php

use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RestaurantTableController;
use Illuminate\Support\Facades\Route;

Route::get('/', [RestaurantTableController::class, 'index'])->name('tables.index');

Route::prefix('tables')->name('tables.')->group(function () {
    Route::post('/', [RestaurantTableController::class, 'store'])->name('store');
    Route::get('/{table}', [RestaurantTableController::class, 'show'])->name('show');
    Route::delete('/{table}', [RestaurantTableController::class, 'destroy'])->name('destroy');
});

Route::prefix('products')->name('products.')->group(function () {
    Route::get('/', [ProductController::class, 'index'])->name('index');
    Route::post('/', [ProductController::class, 'store'])->name('store');
    Route::patch('/{product}/toggle', [ProductController::class, 'toggle'])->name('toggle');
    Route::delete('/{product}', [ProductController::class, 'destroy'])->name('destroy');
});

Route::prefix('orders/{order}')->name('orders.')->group(function () {
    Route::post('items', [OrderController::class, 'addItem'])->name('items.add');
    Route::patch('items/{item}', [OrderController::class, 'updateItemQuantity'])->name('items.update');
    Route::delete('items/{item}', [OrderController::class, 'removeItem'])->name('items.remove');
    Route::post('close', [OrderController::class, 'close'])->name('close');
});
