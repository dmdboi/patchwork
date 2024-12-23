<?php

use App\Http\Controllers\MainController;
use App\Http\Controllers\PatchworkController;
use App\Http\Middleware\Admin;
use Illuminate\Support\Facades\Route;

// Admin Routes
Route::middleware(['web', Admin::class])->name('admin.')->group(function () {
    Route::get('/admin/editor/{slug}', [PatchworkController::class, 'editor'])->name('editor');

    Route::get('/preview/{slug}', [PatchworkController::class, 'preview'])->name('preview');
    Route::get('/preview/{collection}/{slug}', [PatchworkController::class, 'blogPreview'])->name('blog-preview');
    Route::get('/preview/forms/{slug}', [PatchworkController::class, 'formPreview'])->name('form-preview');
});

// Public Routes
Route::get('/', [MainController::class, 'index']);
Route::get('/{page}', [MainController::class, 'page']);
Route::get('/{collection}/{slug}', [MainController::class, 'blog']);
