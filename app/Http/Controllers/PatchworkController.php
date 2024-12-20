<?php

namespace App\Http\Controllers;

use App\Models\Form;
use App\Models\Post;
use Illuminate\Http\Request;

class PatchworkController extends Controller
{
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

        return view('cms/page', compact('page'));
    }

    // Preview Endpoint
    public function preview(Request $request)
    {
        $page = Post::query()
            ->where('slug', request()->slug)->where('type', 'page')
            ->with('postMeta')
            ->first();

        return view('cms/preview', compact('page'));
    }

    // Editor Endpoint
    public function editor(Request $request)
    {
        $page = Post::query()
            ->where('type', 'page')
            ->where('slug', request()->slug)
            ->with('postMeta')
            ->first();

        return view('cms/editor', compact('page'));
    }

    // Blog Preview
    public function blogPreview(Request $request)
    {

        $slug = 'blog/'.request()->slug;

        $post = Post::query()
            ->where('slug', $slug)->where('type', 'post')
            ->with('postMeta')
            ->first();

        return view('cms/blog-preview', compact('post'));
    }

    // Form Preview
    public function formPreview(Request $request)
    {

        $id = request()->slug;

        $form = Form::query()
            ->where('id', $id)
            ->first();

        return view('cms/form-preview', compact('form'));
    }
}
