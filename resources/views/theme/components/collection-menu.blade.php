

@php
    $collection = $section['data']['collection'];

    if($collection) {
            $posts = \App\Models\Post::where('collection_id', $collection)->orderBy('name')->get();
    }
@endphp

<nav class="max-w-3xl mx-auto space-x-2 text-center">
    @foreach ($posts as $post)
        <a href="/{{ $post->collection->slug }}/{{ $post->slug }}" class="flex items">
            <span class="flex-1 text-blue-500 truncate hover:underline">
                {{ $post->title }}
            </span>
        </a>
    @endforeach
</nav>
