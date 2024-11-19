<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductCategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductImageController;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';



// Route::get('/buyerDash', [HomeController::class, 'index'])->name('home.index');

// Route::get('/index', [ProductController::class, 'index'])->name('product.index');

Route::group(['middleware' => ['role:buyer']], function () {
    Route::get('/buyerDash', [HomeController::class, 'index'])->name('home.index');
});



Route::group(['middleware' => ['role:seller'], 'prefix' => 'product'], function () {
    Route::get('/index', [ProductController::class, 'index'])->name('product.index');
    Route::get('/create', [ProductController::class, 'create'])->name('product.create');
    Route::post('/', [ProductController::class, 'store'])->name('product.store');
    Route::get('{id}/edit', [ProductController::class, 'edit'])->name('product.edit');
    Route::put('{id}', [ProductController::class, 'update'])->name('product.update');
    Route::delete('{id}', [ProductController::class, 'destroy'])->name('product.destroy');
});


Route::get('/product', [ProductController::class, 'search'])->name('product.search');


Route::get('/category', [ProductCategoryController::class, 'index'])->name('category.index');
Route::get('/category/search', [ProductCategoryController::class, 'search'])->name('category.search');
Route::get('/category/create', [ProductCategoryController::class, 'create'])->name('category.create');
Route::post('/category/store', [ProductCategoryController::class, 'store'])->name('category.store');
Route::get('category/{id}/edit', [ProductCategoryController::class, 'edit'])->name('category.edit');
Route::put('category/{id}', [ProductCategoryController::class, 'update'])->name('category.update');
Route::delete('category/{id}', [ProductCategoryController::class, 'destroy'])->name('category.destroy');


Route::get('/product/images/{id}', [ProductController::class, 'getImages'])->name('product.images');

Route::delete('/delete-image/{image}', [ProductController::class, 'deleteImage'])->name('product.deleteImage');



Route::get('/product-images/create', [ProductImageController::class, 'create'])->name('product_images.create');
Route::post('/product-images', [ProductImageController::class, 'store'])->name('product_images.store');


// // Route::get('products/{id}/images', [ProductImageController::class, 'index'])->name('product.images.index');

Route::get('/products/{id}/images/upload', [ProductImageController::class, 'create'])->name('products.images.upload');
Route::post('/products/{id}/images', [ProductImageController::class, 'store'])->name('products.images.store');
Route::delete('/products/{id}/images/{imageId}', [ProductImageController::class, 'destroy'])->name('products.images.destroy');


Route::get('/card', [ProductController::class, 'cardIndex'])->name('card.index');
