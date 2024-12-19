<?php

use App\Http\Controllers\PatchworkController;
use App\Http\Middleware\Admin;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/{slug}', [PatchworkController::class, 'page'] );

// Admin Routes
Route::middleware(['web', Admin::class])->name('admin.')->group(function () {
    Route::get('/preview/{slug}', [PatchworkController::class, 'preview'])->name('preview');
    Route::get('/admin/editor/{slug}', [PatchworkController::class, 'editor'])->name('editor');

    Route::get('/preview/blog/{slug}', [PatchworkController::class, 'blogPreview'])->name('blog-preview');
});

Route::middleware(['web', 'auth', \ProtoneMedia\Splade\Http\SpladeMiddleware::class])->name('admin.')->group(function () {
    Route::get('admin/pages/{model}/builder', [\App\Http\Controllers\BuilderController::class, 'builder'])->name('pages.builder');
    Route::post('admin/pages/{model}/sections', [\App\Http\Controllers\BuilderController::class, 'sections'])->name('pages.sections');
    Route::post('admin/pages/{model}/sections/remove', [\App\Http\Controllers\BuilderController::class, 'remove'])->name('pages.remove');
    Route::get('admin/pages/{model}/meta', [\App\Http\Controllers\BuilderController::class, 'meta'])->name('pages.meta');
    Route::post('admin/pages/{model}/meta', [\App\Http\Controllers\BuilderController::class, 'metaStore'])->name('pages.meta.store');
    Route::post('admin/pages/{model}/clear', [\App\Http\Controllers\BuilderController::class, 'clear'])->name('pages.clear');
});