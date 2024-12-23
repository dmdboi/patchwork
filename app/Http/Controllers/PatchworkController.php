<?php

namespace App\Http\Controllers;

use App\Models\Form;
use App\Models\Post;
use Illuminate\Http\Request;

class PatchworkController extends Controller
{
    public function index(Request $request)
    {
        $page = Post::query()
            ->where('type', 'page')
            ->where('is_published', true)
            ->where('slug', 'home')
            ->with('postMeta')
            ->first();

        if (! $page) {
            return view('welcome');
        }

        return view('themes/Default/page', compact('page'));
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

        if (! $page) {
            abort(404);
        }

        return view('themes/Default/page', compact('page'));
    }

    // Blog Endpoint
    public function blog(Request $request)
    {
        $collection = request()->collection;
        $slug = request()->slug;

        $post = Post::query()
            ->where('slug', $slug)->where('type', 'post')
            ->where('is_published', true)
            ->whereHas('collection', function ($query) use ($collection) {
                $query->where('slug', $collection);
            })
            ->with('postMeta')
            ->first();

        if (! $post) {
            abort(404);
        }

        return view('themes/Default/blog', compact('post'));
    }

    // Preview Endpoint
    public function preview(Request $request)
    {
        $page = Post::query()
            ->where('slug', request()->slug)->where('type', 'page')
            ->with('postMeta')
            ->first();

        if (! $page) {
            abort(404);
        }

        return view('themes/Default/preview', compact('page'));
    }

    // Editor Endpoint
    public function editor(Request $request)
    {
        $page = Post::query()
            ->where('type', 'page')
            ->where('slug', request()->slug)
            ->with('postMeta')
            ->first();

        return view('editor/editor', compact('page'));
    }

    // Blog Preview
    public function blogPreview(Request $request)
    {
        $collection = request()->collection;
        $slug = request()->slug;

        $post = Post::query()
            ->where('slug', $slug)->where('type', 'post')
            ->whereHas('collection', function ($query) use ($collection) {
                $query->where('slug', $collection);
            })
            ->with('postMeta')
            ->first();

        if (! $post) {
            abort(404);
        }

        return view('themes/Default/blog-preview', compact('post'));
    }

    // Form Preview
    public function formPreview(Request $request)
    {

        $id = request()->slug;

        $form = Form::query()
            ->where('id', $id)
            ->first();

        return view('themes/Default/form-preview', compact('form'));
    }
}
