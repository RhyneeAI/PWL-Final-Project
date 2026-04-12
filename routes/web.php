<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return view('dashboard');
    // return Inertia::render('dashboard');
})->name('dashboard');

Route::get('/product', function () {
    return view('master-data.product.index');
})->name('product');

Route::get('/category', function () {
    return view('master-data.category.index');
})->name('category');

Route::get('/test', function () {
    return view('test');
})->name('test');

Route::get('/user', function () {
    return view('master-data.user.index');
})->name('user');

// Route::middleware(['auth'])->group(function () {
//     Route::get('dashboard', function () {
//         return Inertia::render('dashboard');
//     })->name('dashboard');
// });

// require __DIR__.'/settings.php';
// require __DIR__.'/auth.php';
