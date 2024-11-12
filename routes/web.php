<?php

use App\Http\Controllers\ProductCategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductImageController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ProductController::class, 'index'])->name('product.index');
Route::get('/product', [ProductController::class, 'search'])->name('product.search');
Route::get('/product/create', [ProductController::class, 'create'])->name('product.create');
Route::post('/product', [ProductController::class, 'store'])->name('product.store');
Route::get('product/{id}/edit', [ProductController::class, 'edit'])->name('product.edit');
Route::put('product/{id}', [ProductController::class, 'update'])->name('product.update');
Route::delete('product/{id}', [ProductController::class, 'destroy'])->name('product.destroy');

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


// Route::get('products/{id}/images', [ProductImageController::class, 'index'])->name('product.images.index');

Route::get('/products/{id}/images/upload', [ProductImageController::class, 'create'])->name('products.images.upload');
Route::post('/products/{id}/images', [ProductImageController::class, 'store'])->name('products.images.store');
Route::delete('/products/{id}/images/{imageId}', [ProductImageController::class, 'destroy'])->name('products.images.destroy');
