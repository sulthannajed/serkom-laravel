<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;

Route::get('/', [ProductController::class, 'landing'])
    ->name('landing');

Route::resource('products', ProductController::class)
    ->except(['show'])
    ->parameters(['products' => 'products']);
