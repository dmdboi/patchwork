<?php

use Illuminate\Support\Facades\Route;
use TomatoPHP\FilamentCms\Models\Post;

Route::get('/{slug}', function () {

    $page = Post::query()
    ->withTrashed()
    ->where('type', 'builder')
    ->where('slug', request()->slug)
    ->with('postMeta')
    ->first();

    return view('welcome', compact('page'));
});


