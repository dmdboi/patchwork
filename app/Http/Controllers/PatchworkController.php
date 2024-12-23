<?php

namespace App\Http\Controllers;

use App\Models\Form;
use App\Models\Post;
use Illuminate\Http\Request;

class PatchworkController extends Controller
{
    // Editor Endpoint
    public function editor(Request $request)
    {
        $page = Post::query()
            ->where('type', 'page')
            ->where('slug', request()->slug)
            ->with('postMeta')
            ->first();

        return view('admin/editor', compact('page'));
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

        return view('theme/pages/preview', compact('page'));
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

        return view('theme/pages/blog-preview', compact('post'));
    }

    // Form Preview
    public function formPreview(Request $request)
    {

        $id = request()->slug;

        $form = Form::query()
            ->where('id', $id)
            ->first();

        return view('theme/pages/form-preview', compact('form'));
    }
}
