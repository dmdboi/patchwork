<?php

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
Route::get('/', [PatchworkController::class, 'index']);
Route::get('/{page}', [PatchworkController::class, 'page']);
Route::get('/{collection}/{slug}', [PatchworkController::class, 'blog']);
