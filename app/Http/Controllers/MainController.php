<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class MainController extends Controller
{
    //
    public function index(Request $request)
    {
        $page = Post::query()
            ->where('type', 'page')
            ->where('is_published', true)
            ->where('slug', 'home')
            ->with('postMeta')
            ->first();

        if (!$page) {
            return view('welcome');
        }

        return view('theme/pages/page', compact('page'));
    }

    // Page Endpoint
    public function page(Request $request)
    {

        $page = Post::query()
            ->where('type', 'page')
            ->where('is_published', true)
            ->where('slug', request()->slug)
            ->with('postMeta')
            ->first();

        if (!$page) {
            abort(404);
        }

        return view('theme/pages/page', compact('page'));
    }

    // Blog Endpoint
    public function blog(Request $request)
    {
        $collection = request()->collection;
        $slug = request()->slug;

        $post = Post::query()
            ->with('postMeta')
            ->with('collection')
            ->where('slug', $slug)->where('type', 'post')
            ->where('is_published', true)
            ->whereHas('collection', function ($query) use ($collection) {
                $query->where('slug', $collection);
            })
            ->first();

        if (!$post) {
            abort(404);
        }

        if ($post && $post->getAttribute('has_markdown_file')) {
            $post->body = getMarkdownFile($post);
        }

        return view('themes/pages/blog', compact('post'));
    }
}
